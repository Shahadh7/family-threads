<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const currentActive = ref("All Items");

const setActive = (tag) => {
    currentActive.value = tag;
};

const AllItems = ref([
    {
        id: 1,
        title: "Memory Thread 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/shahadh.png'
    },  
    {
        id: 2,
        title: "Time Capsule 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/shahadh.png'
    },  
    {
        id: 3,
        title: "Keep Sake Item 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Keep Sake Item",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/shahadh.png'
    },  
    {
        id: 4,
        title: "Memory Thread 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/shahadh.png'
    },  
    {
        id: 5,
        title: "Time Capsule 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/shahadh.png'

    },
    {
        id: 6,
        title: "Time Capsule 3",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/shahadh.png'

    },
])

const filteredItems = computed(() => {
    switch (currentActive.value) {
        case "All Items":
            return AllItems.value;
        case "Memory Threads":
            return AllItems.value.filter((item) => item.thread_type === "Memory Thread");
        case "Time Capsules":
            return AllItems.value.filter((item) => item.thread_type === "Time Capsule");
        case "Keep Sake Items":
            return AllItems.value.filter((item) => item.thread_type === "Keep Sake Item");
        default:
            return AllItems.value;
    }
})

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
                <span :class="['text-xs', currentActive === 'All Items' ? 'montserrat-bold' : 'montserrat-light']">All Items</span>
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'Memory Threads' }"
                @click="setActive('Memory Threads')"
            >
                <span :class="['text-xs', currentActive === 'Memory Threads' ? 'montserrat-bold' : 'montserrat-light']">Memory Thread</span>
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'Keep Sake Items' }"
                @click="setActive('Keep Sake Items')"
            >
                <span :class="['text-xs', currentActive === 'Keep Sake Items' ? 'montserrat-bold' : 'montserrat-light']">Keep Sake Items</span>
            </div>
            <div
                class="tag"
                :class="{ 'tag-active': currentActive === 'Time Capsules' }"
                @click="setActive('Time Capsules')"
            >
                <span :class="['text-xs', currentActive === 'Time Capsules' ? 'montserrat-bold' : 'montserrat-light']">Time Capsules</span>
            </div>
        </div>

        <div class="py-5">
            <div class="max-w-5xl">
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg max-h-custom overflow-y-scroll"
                >
                <div class="pb-6  border-b border-gray-200">
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
                                        <img
                                            class="w-10 h-10 rounded-full object-cover border-4 border-solid border-black"
                                            :src="item.profile_picture"
                                            alt="profile"
                                        />
                                        <div class="flex flex-col">
                                            <p class="text-sm montserrat-bold">
                                                {{ item.created_by }}
                                            </p>
                                            <p class="text-xs montserrat-light">
                                                {{ item.created_at }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <span class="mdi mdi-heart-outline text-3xl text-gray-400"></span>
                                        <span class="mdi mdi-chat-outline text-3xl text-gray-400"></span>
                                    </div>
                                </div>
                                <div class="flex">
                                    <img
                                        class="w-full object-contain"
                                        :src="item.image"
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
}
.tag-active {
    background-color: #e2d65e;
    font-family: "Montserrat", sans-serif;
    font-optical-sizing: auto;
    font-weight: 900;
    font-style: normal;
}
</style>
