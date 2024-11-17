<script setup>
import { onMounted, ref, computed } from "vue";
import ActionButton from "@/Components/ActionButton.vue";
import MemoryItemCard from "@/Components/MemoryItemCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";

const currentActive = ref("Memory Threads");

const setActive = (tag) => {
    currentActive.value = tag;
};

const AllItems = ref([]);

const filteredItems = computed(() => {
    switch (currentActive.value) {
        case "All Items":
            return AllItems.value;
        case "Memory Threads":
            return AllItems.value.filter((item) => item.thread_type.value === "Memory Thread");
        case "Time Capsules":
            return AllItems.value.filter((item) => item.thread_type.value === "Time Capsule");
        case "Keep Sake Items":
            return AllItems.value.filter((item) => item.thread_type.value === "Keep Sake");
        default:
            return AllItems.value;
    }
});

const navigateToAddMemoryItem = () => {
    router.get(route('memory-items.create'));
};

const removeItem = (id) => {
    AllItems.value = AllItems.value.filter((item) => item.id !== id);
    // Update localStorage after removing the item
    let findItem = JSON.parse(localStorage.getItem("threadList"));
    findItem = findItem.filter((item) => item.id !== id);
    localStorage.setItem("threadList", JSON.stringify(findItem));
};

// Convert Base64 to Blob URL
const base64ToImage = (base64String) => {
    const binary = atob(base64String.split(",")[1]);
    const array = [];
    for (let i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
    }
    const blob = new Blob([new Uint8Array(array)], { type: "image/jpeg" }); // Update type based on the file type
    return URL.createObjectURL(blob);
};

// Rearrange Array
const rearrangeArray = (array) => {
    // Example rearrangement: Sort by `created_at` date descending
    return array.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
};

// Fetch thread list from localStorage on component mount
onMounted(() => {
    const savedThreads = JSON.parse(localStorage.getItem("threadList")) || [];

    const currentUser = localStorage.getItem("currentUser");

    const filteredThreads = savedThreads.filter((item) => item.created_by === currentUser);

    // Convert Base64 image to Blob URL and rearrange the array
    const processedThreads = filteredThreads.map((item) => {
        if (item.file) {
            item.file = base64ToImage(item.file); // Convert base64 to Blob URL
        }
        return item;
    });

    AllItems.value = rearrangeArray(processedThreads); // Rearrange the array

    localStorage.removeItem("currentEditingItem");
});
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
