<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";
import moment from "moment";
import axios from "axios";

const currentActive = ref("All Items");

const setActive = (tag) => {
    currentActive.value = tag;
};

const AllItems = ref([]);
const timeRemainingMap = ref({}); // Reactive map for time remaining
const timeExpiredMap = ref({}); // Tracks whether the time has expired for each item

const filteredItems = computed(() => {
    switch (currentActive.value) {
        case "All Items":
            return AllItems.value;
        case "Memory Threads":
            return AllItems.value.filter(
                (item) => item.thread_type.value === "Memory Thread"
            );
        case "Time Capsules":
            return AllItems.value.filter(
                (item) => item.thread_type.value === "Time Capsule"
            );
        case "Keep Sake Items":
            return AllItems.value.filter(
                (item) => item.thread_type.value === "Keep Sake"
            );
        default:
            return AllItems.value;
    }
});

const getCurrentUser = async () => {
    axios.get("/current-user").then((response) => {
        localStorage.setItem("currentUser", response.data.name);
    });
};

const toggleLike = (item) => {
    item.liked = !item.liked; // Toggle the liked state
};

// Update remaining time for all "Time Capsule" items
const updateTimeCapsules = () => {
    AllItems.value.forEach((item) => {
        if (item.thread_type.value === "Time Capsule") {
            const now = moment(); // Current time
            const diff = moment(item.open_date).diff(now); // Calculate time difference
            if (diff <= 0) {
                timeExpiredMap.value[item.id] = true; // Mark as expired
                timeRemainingMap.value[item.id] = "Expired";
            } else {
                timeExpiredMap.value[item.id] = false;
                const duration = moment.duration(diff);
                const days = Math.floor(duration.asDays());
                const hours = duration.hours();
                const minutes = duration.minutes();
                const seconds = duration.seconds();
                timeRemainingMap.value[item.id] = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }
    });
};

// Fetch thread list from localStorage on component mount
onMounted(async () => {
    await getCurrentUser();

    const savedThreads = JSON.parse(localStorage.getItem("threadList")) || [];
    const currentUser = localStorage.getItem("currentUser");

    const filteredThreads = savedThreads.filter(
        (item) =>
            item.created_by === currentUser ||
            item.public === true ||
            item.canviewbyUsers.some((user) => user.value === currentUser)
    );

    const processedThreads = filteredThreads.map((item) => {
        if (item.file) {
            item.file = base64ToImage(item.file); // Convert Base64 to Blob URL
        }
        return item;
    });

    AllItems.value = rearrangeArray(processedThreads); // Rearrange the array

    updateTimeCapsules(); // Initial update
    setInterval(updateTimeCapsules, 1000); // Update every second
});

const base64ToImage = (base64String) => {
    const binary = atob(base64String.split(",")[1]);
    const array = [];
    for (let i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
    }
    const blob = new Blob([new Uint8Array(array)], { type: "image/jpeg" });
    return URL.createObjectURL(blob);
};

const rearrangeArray = (array) => {
    return array.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :name="$page.props.auth.user.name">
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Home</h2>
        </template>

        <div class="flex gap-1 my-5 border-b pb-3 border-gray-200 max-w-5xl">
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'All Items' }"
                @click="setActive('All Items')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'All Items'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >All Items</span
                >
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'Memory Threads' }"
                @click="setActive('Memory Threads')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'Memory Threads'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >Memory Thread</span
                >
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'Keep Sake Items' }"
                @click="setActive('Keep Sake Items')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'Keep Sake Items'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >Keep Sake Items</span
                >
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'Time Capsules' }"
                @click="setActive('Time Capsules')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'Time Capsules'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >Time Capsules</span
                >
            </div>
        </div>

        <div class="py-5">
            <div class="max-w-5xl">
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg max-h-custom overflow-y-scroll"
                >
                    <div class="pb-6 border-b border-gray-200">
                        <div
                            class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-5"
                        >
                            <div
                                v-for="item in filteredItems"
                                :key="item.id"
                                class="flex flex-col gap-2 bg-white p-5 rounded-3xl"
                            >
                                <div class="flex justify-between items-center">
                                    <div class="flex gap-2">
                                        <span class="inline-flex rounded-md">
                                            <template v-if="image">
                                                <img
                                                    :src="item.profile_image"
                                                    alt="Avatar"
                                                    class="h-14 w-14 rounded-full object-cover border-4 border-solid border-black"
                                                    @error="handleImageError"
                                                />
                                            </template>
                                            <template v-else>
                                                <span
                                                    class="h-14 w-14 rounded-full flex items-center justify-center bg-gray-500 text-white font-bold border-4 border-solid border-black"
                                                >
                                                    {{
                                                        item.created_by
                                                            .charAt(0)
                                                            .toUpperCase()
                                                    }}
                                                </span>
                                            </template>
                                        </span>
                                        <div class="flex flex-col">
                                            <p
                                                class="text-sm montserrat-bold mb-2"
                                            >
                                                {{ item.created_by }}
                                            </p>
                                            <p class="text-xs montserrat-light">
                                                {{ item.created_at }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <span
                                            :class="[
                                                'mdi text-3xl',
                                                item.liked ? 'mdi-heart text-yellow-500' : 'mdi-heart-outline text-gray-400',
                                            ]"
                                            @click="toggleLike(item)"
                                        ></span>
                                        <span class="mdi mdi-chat-outline text-3xl text-gray-400"></span>
                                    </div>
                                </div>
                                <template
                                    v-if="
                                        item.thread_type.value !==
                                        'Time Capsule'
                                    "
                                >
                                    <div class="flex">
                                        <img
                                            class="w-full object-contain"
                                            :src="item.file"
                                            alt="image"
                                        />
                                    </div>
                                    <div class="flex flex-col mt-4">
                                        <p class="text-xs montserrat-bold mb-3">
                                            {{ item.title }}
                                        </p>
                                        <p class="text-sm montserrat-light">
                                            {{ item.description }}
                                        </p>
                                    </div>
                                </template>
                                <template v-if="item.thread_type.value === 'Time Capsule'">
                                    <div v-if="timeExpiredMap[item.id]">
                                        <div class="flex">
                                            <img
                                                class="w-full object-contain"
                                                :src="item.file"
                                                alt="image"
                                            />
                                        </div>
                                        <div class="flex flex-col mt-4">
                                            <p class="text-xs montserrat-bold mb-3">
                                                {{ item.title }}
                                            </p>
                                            <p class="text-sm montserrat-light">
                                                {{ item.description }}
                                            </p>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div class="w-full h-96 flex justify-center items-center bg-slate-200 rounded-md">
                                            <p>
                                                Opens in:
                                                {{ timeRemainingMap[item.id] || 'Calculating...' }}
                                            </p>
                                        </div>
                                    </div>
                                </template>
                                <template
                                    v-if="
                                        item.thread_type.value === 'Keep Sake'
                                    "
                                >
                                    <div class="flex gap-2 items-center mt-3">
                                        <p class="montserrat-light text-sm">
                                            Passed on :
                                        </p>
                                        <v-chip
                                            class="w-24 flex justify-center items-center"
                                            ><span
                                                class="text-sm montserrat-light"
                                                >{{ item.created_by }}</span
                                            ></v-chip
                                        >
                                        <span
                                            class="mdi mdi-arrow-right-bold"
                                        ></span>
                                        <v-chip
                                            class="w-24 flex justify-center items-center"
                                            ><span
                                                class="text-sm montserrat-light"
                                                >{{
                                                    item.givenFromTo.value
                                                }}</span
                                            ></v-chip
                                        >
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.max-h-custom {
    max-height: calc(100vh - 280px);
}
.tag {
    border-radius: 10px;
    padding: 5px 30px;
    color: #000000;
    cursor: pointer;
}
.tag-active {
    background-color: #e2d65e;
    font-family: "Montserrat", sans-serif;
    font-optical-sizing: auto;
    font-weight: 900;
    font-style: normal;
}
</style>
