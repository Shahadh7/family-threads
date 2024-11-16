<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
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
            <h2 class="text-3xl leading-tight text-gray-800">Memory Items</h2>
        </template>

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

        <div class="py-5">
            <div class="max-w-5xl">
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg max-h-custom overflow-y-scroll"
                >
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
</style>
