<script setup>
import { onMounted, ref, computed } from "vue";
import ActionButton from "@/Components/ActionButton.vue";
import MemoryItemCard from "@/Components/MemoryItemCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import moment from "moment";

const props = defineProps({
    memoryItems: {
        type: Array,
        required: true,
    },
});

const AllItems = ref([]);

const showModal = ref(false); // State for modal visibility
const selectedItem = ref(null); // State for selected item details

const viewItemDetails = (item) => {
    selectedItem.value = item; // Set the selected item
    showModal.value = true; // Show the modal
};


const getRandomColor = () => {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};

onMounted(() => {

});
</script>

<template>
    <Head title="Time Thread" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Time Thread</h2>
        </template>

        <div>
            <v-timeline align="start">
                <v-timeline-item
                    v-for="(item, i) in props.memoryItems"
                    :key="i"
                    :dot-color="getRandomColor()"
                    size="small"
                >
                    <template v-slot:opposite>
                        <div
                            :class="`pt-1 headline font-weight-bold text-${getRandomColor()}`"
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
                                :src="item.file.url"
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
                        :src="selectedItem?.file.url"
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
