<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { computed, onMounted, ref } from "vue";
import moment from "moment";
import axios from "axios";

const props = defineProps({
    memoryItems: {
        type: Array,
        required: true,
    },
});

const user = computed(() => usePage().props.auth.user);

const currentActive = ref("AllItems");

const setActive = async (tag) => {
    currentActive.value = tag;
    fetchMemoryItemsByTag(tag);
};

const AllItems = ref(props.memoryItems);
const timeRemainingMap = ref({}); // Reactive map for time remaining
const timeExpiredMap = ref({}); // Tracks whether the time has expired for each item
const intervalId = ref(null);

const toggleLike = (item) => {
    item.liked = !item.liked; // Toggle the liked state
};

const updateTimeCapsules = () => {
    let allExpired = true; 

    AllItems.value.forEach((item) => {
        if (item.type === "TimeCapsule") {
            const now = moment().utc();
            const openDate = moment(item.time_capsule.open_date).utc();
            const diff = openDate.diff(now);

            if (diff <= 0) {
                timeExpiredMap.value[item.id] = true;
                timeRemainingMap.value[item.id] = "Expired";
            } else {
                allExpired = false;
                timeExpiredMap.value[item.id] = false;
                const duration = moment.duration(diff);
                const days = Math.floor(duration.asDays());
                const hours = duration.hours();
                const minutes = duration.minutes();
                const seconds = duration.seconds();
                timeRemainingMap.value[
                    item.id
                ] = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }
    });

    // Stop the interval when all time capsules are expired
    if (allExpired) {
        clearInterval(intervalId.value);
        intervalId.value = null; 
    }
};

onMounted(async () => {
    updateTimeCapsules();
    intervalId.value = setInterval(updateTimeCapsules, 1000);
});

const rearrangeArray = (array) => {
    return array.sort(
        (a, b) => new Date(b.created_at) - new Date(a.created_at)
    );
};

const fetchMemoryItemsByTag = async (tag) => {
    axios
        .get(`/dashboard/memory-items/${tag}`)
        .then((response) => (AllItems.value = response.data));
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Home</h2>
        </template>

        <div class="flex gap-1 my-5 border-b pb-3 border-gray-200 max-w-5xl">
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'AllItems' }"
                @click="setActive('AllItems')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'AllItems'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >All Items</span
                >
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'MemoryThread' }"
                @click="setActive('MemoryThread')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'MemoryThread'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >Memory Threads</span
                >
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'Keepsake' }"
                @click="setActive('Keepsake')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'Keepsake'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >Keep Sake Items</span
                >
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'TimeCapsule' }"
                @click="setActive('TimeCapsule')"
            >
                <span
                    :class="[
                        'text-xs',
                        currentActive === 'TimeCapsule'
                            ? 'montserrat-bold'
                            : 'montserrat-light',
                    ]"
                    >Time Capsules</span
                >
            </div>
        </div>
        <template v-if="AllItems.length === 0">
            <p class="my-5 text-md montserrat-light">No records available at the moment.</p>
        </template>

        <div class="py-5">
            <div class="max-w-5xl">
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg max-h-custom overflow-y-scroll"
                >
                    <div :class="['pb-6', AllItems.length === 0 ? '' : 'border-b border-gray-200']">
                        <div
                            class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-5"
                        >
                            <div
                                v-for="item in AllItems"
                                :key="item.id"
                                class="flex flex-col gap-2 bg-white p-5 rounded-3xl"
                            >
                                <div class="flex justify-between items-center">
                                    <div
                                        class="flex gap-2 justify-center items-center"
                                    >
                                        <span class="inline-flex rounded-md">
                                            <template v-if="item.profile_image">
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
                                                        item.user.name
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
                                                {{ item.user.name }}
                                            </p>
                                            <p class="text-xs montserrat-light">
                                                {{
                                                    moment(
                                                        item.created_at
                                                    ).fromNow()
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <span
                                            :class="[
                                                'mdi text-3xl',
                                                item.liked
                                                    ? 'mdi-heart text-yellow-500'
                                                    : 'mdi-heart-outline text-gray-400',
                                            ]"
                                            @click="toggleLike(item)"
                                        ></span>
                                        <span
                                            class="mdi mdi-chat-outline text-3xl text-gray-400"
                                        ></span>
                                    </div>
                                </div>
                                <template v-if="item.type !== 'TimeCapsule'">
                                    <div class="flex">
                                        <img
                                            class="w-full object-contain"
                                            :src="item.file.url"
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
                                <template v-if="item.type === 'TimeCapsule'">
                                    <div v-if="timeExpiredMap[item.id] == true">
                                        <div class="flex">
                                            <img
                                                class="w-full object-contain"
                                                :src="item.file.url"
                                                alt="image"
                                            />
                                        </div>
                                        <div class="flex flex-col mt-4">
                                            <p
                                                class="text-xs montserrat-bold mb-3"
                                            >
                                                {{ item.title }}
                                            </p>
                                            <p class="text-sm montserrat-light">
                                                {{ item.description }}
                                            </p>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div
                                            class="w-full h-96 flex justify-center items-center bg-slate-200 rounded-md"
                                        >
                                            <p>
                                                Opens in:
                                                {{
                                                    timeRemainingMap[item.id] ||
                                                    "Calculating..."
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </template>

                                <template v-if="item.type === 'Keepsake'">
                                    <div class="flex gap-2 items-center mt-3">
                                        <p class="montserrat-light text-sm">
                                            Passed on :
                                        </p>
                                        <v-chip
                                            class="w-24 flex justify-center items-center"
                                            ><span
                                                class="text-sm montserrat-light"
                                                >{{ item.user.name }}</span
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
                                                    item.keep_sake
                                                        .passed_on_user.name
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
