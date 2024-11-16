<script setup>
import ActionButton from "@/Components/ActionButton.vue";
import MemoryItemCard from "@/Components/MemoryItemCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const currentActive = ref("Memory Threads");

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
        image: 'images/img-1.jpg'
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
        image: 'images/img-1.jpg'
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
        image: 'images/img-1.jpg'
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
        image: 'images/img-1.jpg'
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
        image: 'images/img-1.jpg'

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
        image: 'images/img-1.jpg'

    },
    {
        id: 7,
        title: "Memory Thread 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 8,
        title: "Time Capsule 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 9,
        title: "Keep Sake Item 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Keep Sake Item",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 10,
        title: "Memory Thread 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 11,
        title: "Time Capsule 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'

    },
    {
        id: 12,
        title: "Time Capsule 3",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'

    },
    {
        id: 13,
        title: "Memory Thread 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 14,
        title: "Time Capsule 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 15,
        title: "Keep Sake Item 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Keep Sake Item",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 16,
        title: "Memory Thread 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 17,
        title: "Time Capsule 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'

    },
    {
        id: 18,
        title: "Time Capsule 3",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'

    },
    {
        id: 19,
        title: "Memory Thread 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 20,
        title: "Time Capsule 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 21,
        title: "Keep Sake Item 1",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Keep Sake Item",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 22,
        title: "Memory Thread 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Memory Thread",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'
    },  
    {
        id: 23,
        title: "Time Capsule 2",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/shahadh.png",
        media_type: 'image',
        image: 'images/img-1.jpg'

    },
    {
        id: 24,
        title: "Time Capsule 3",
        description: 'lorem ipsum dolor sit amet',
        thread_type: "Time Capsule",
        created_by: "Shahadh",
        created_at: "2024-10-09",
        profile_picture: "/images/img-4.webp",
        media_type: 'image',
        image: 'images/img-4.webp'

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

const navigateToAddMemoryItem = () => {
    router.get(route('memory-items.create'));
}

const removeItem = (id) => {
    AllItems.value = AllItems.value.filter((item) => item.id !== id);
}

</script>

<template>
    <Head title="Memory Items" />

    <AuthenticatedLayout :name="$page.props.auth.user.name">
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Memory Items</h2>
        </template>

        <div class="flex justify-between items-center max-w-7xl border-b border-gray-200">
            <div class="flex gap-1 my-5">
                <div
                    class="tag"
                    :class="{ 'tag-active': currentActive === 'Memory Threads' }"
                    @click="setActive('Memory Threads')"
                >
                    <span :class="['text-xs', currentActive === 'Memory Threads' ? 'montserrat-bold' : 'montserrat-light']">Memory Threads</span>
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
            <ActionButton class="px-1 py-1" @click="navigateToAddMemoryItem">+</ActionButton>
        </div>
        <template v-if="filteredItems.length === 0">
            <p class="my-5 text-md montserrat-light">No records available at the moment.</p>
        </template>
        <div class="py-5">
            <div class="max-w-7xl">
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg max-h-custom overflow-y-scroll"
                >
                    <div :class="['p-6 border-gray-200', filteredItems.length === 0 ? '' : 'border-b']">
                        <div class="row gap-5">
                            <div class="col" v-for="item in filteredItems" :key="item.id">
                                <MemoryItemCard :memoryItem="item" @removeItem="removeItem" />
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
    padding: 5px 30px 0px 0px;
    color: #000000;
    text-align: left;
}
.tag-active {
    font-family: "Montserrat", sans-serif;
    font-optical-sizing: auto;
    font-weight: 900;
    font-style: normal;
    text-decoration-line: underline;
    text-decoration-thickness: 3px;
    text-underline-offset: 10px;
    text-decoration-color: #d9ce5b;
    color: #5b5b27;
}

.row {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
}
.col {
    display: flex;
    width: 32%;
}

</style>
