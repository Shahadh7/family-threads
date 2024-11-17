<script setup>
import { onMounted, ref, computed } from "vue";
import ActionButton from "@/Components/ActionButton.vue";
import MemoryItemCard from "@/Components/MemoryItemCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import moment from "moment";

const AllItems = ref([]);

const showModal = ref(false); // State for modal visibility
const selectedItem = ref(null); // State for selected item details

const viewItemDetails = (item) => {
    selectedItem.value = item; // Set the selected item
    showModal.value = true; // Show the modal
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
    return array.sort(
        (a, b) => new Date(b.created_at) - new Date(a.created_at)
    );
};

const getRandomColor = () => {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};

// Fetch thread list from localStorage on component mount
onMounted(() => {
    const savedThreads = JSON.parse(localStorage.getItem("threadList")) || [];

    const currentUser = localStorage.getItem("currentUser");

    const filteredThreads = savedThreads.filter(
        (item) => item.created_by === currentUser
    );

    // Convert Base64 image to Blob URL and rearrange the array
    const processedThreads = filteredThreads.map((item) => {
        if (item.file) {
            item.file = base64ToImage(item.file); // Convert base64 to Blob URL
        }
        return item;
    });

    const colorAddedThreads = processedThreads.map((item) => {
        item.color = getRandomColor();
        return item;
    });

    AllItems.value = rearrangeArray(colorAddedThreads); // Rearrange the array

    localStorage.removeItem("currentEditingItem");
});
</script>

<template>
    <Head title="Time Thread" />
    <AuthenticatedLayout :name="$page.props.auth.user.name">
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Time Thread</h2>
        </template>

        <div>
            <v-timeline align="start">
                <v-timeline-item
                    v-for="(item, i) in AllItems"
                    :key="i"
                    :dot-color="item.color"
                    size="small"
                >
                    <template v-slot:opposite>
                        <div
                            :class="`pt-1 headline font-weight-bold text-${item.color}`"
                            v-text="
                                moment(item.created_at).format('YYYY - MM - DD')
                            "
                        ></div>
                    </template>
                    <div>
                        <div class="bg-yellow-50 p-4 flex rounded-2xl">
                            <div
                                style="width: 70%"
                                class="flex items-center justify-center"
                            >
                                <button
                                    class="bg-black rounded-lg px-6 py-2 text-xl font-bold text-white"
                                    @click="viewItemDetails(item)"
                                >
                                    View
                                </button>
                            </div>
                            <img
                                :src="item.file"
                                alt="time thread image"
                                class="w-32"
                            />
                        </div>
                    </div>
                </v-timeline-item>
            </v-timeline>
        </div>
    </AuthenticatedLayout>
    <template>
        <v-dialog v-model="showModal" max-width="600px">
            <v-card>
                <v-card-title>
                    <span>View Detail</span>
                </v-card-title>
                <v-card-text>
                    <h2
                        :class="`mt-n1 headline font-weight-bold text-2xl mb-4 text-${selectedItem?.color}`"
                    >
                        {{ selectedItem?.title }}
                    </h2>
                    <div class="my-5">
                        {{ selectedItem?.description }}
                    </div>
                    <img
                        :src="selectedItem?.file"
                        alt="Item image"
                        class="w-full rounded"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" @click="showModal = false"
                        >Close</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </template>
</template>
<style scoped>
.max-h-custom {
    max-height: calc(100vh - 280px);
}
</style>
