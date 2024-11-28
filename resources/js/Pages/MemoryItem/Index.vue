<script setup>
import { onMounted, ref, computed } from "vue";
import ActionButton from "@/Components/ActionButton.vue";
import MemoryItemCard from "@/Components/MemoryItemCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";


const props = defineProps({
    memoryItems: {
        type: Array,
        required: true,
    },
});

const currentActive = ref("MemoryThread");
const confirmDialog = ref(false);
const selectedItemId = ref(null);
const AllItems = ref(props.memoryItems);
const snackbar = ref(true); 
const snackbarText = ref('');

const setActive = async(tag) => {
    await fetchMemoryItems(tag);
    currentActive.value = tag;
};



const navigateToAddMemoryItem = () => {
    router.get(route('memory-items.create'));
}

const rearrangeArray = (array) => {
    // Example rearrangement: Sort by `created_at` date descending
    return array.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
};

const fetchMemoryItems = async(type) => {
    axios.get(`/memory-item-by-type/${type}`).then((response) => {
        AllItems.value = response.data;
    })
}


const removeItem = async () => {
    if (selectedItemId.value) {
        try {
            await axios.delete(`/memory-item/${selectedItemId.value}`);
            AllItems.value = AllItems.value.filter((item) => item.id !== selectedItemId.value);
            confirmDialog.value = false;
            snackbarText.value = "Memory item deleted successfully.";
            snackbar.value = true;
        } catch (error) {
            console.error("Error removing item:", error);
        }
    }
};

const confirmRemove = (id) => {
    selectedItemId.value = id;
    confirmDialog.value = true;
};

</script>


<template>
    <Head title="Memory Items" />
    <AuthenticatedLayout>
        <v-snackbar v-model="snackbar" :timeout="3000" color="green" v-if="$page.props.flash != null">
            {{ $page.props.flash}}
            <template v-slot:action="{ attrs }">
            <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
                Close
            </v-btn>
            </template>
        </v-snackbar>

        <v-snackbar v-model="snackbar" :timeout="3000" color="red" v-if="snackbarText != ''" >
            {{  snackbarText }}
            <template v-slot:action="{ attrs }">
            <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
                Close
            </v-btn>
            </template>
        </v-snackbar>

        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Memory Items</h2>
        </template>

        <div class="flex justify-between items-center max-w-7xl border-b border-gray-200">
            <div class="flex gap-1 my-5">
                <div
                    class="tag"
                    :class="{ 'tag-active': currentActive === 'MemoryThread' }"
                    @click="setActive('MemoryThread')"
                >
                    <span :class="['text-xs', currentActive === 'MemoryThread' ? 'montserrat-bold' : 'montserrat-light']">Memory Threads</span>
                </div>
                <div
                    class="tag"
                    :class="{ 'tag-active': currentActive === 'Keepsake' }"
                    @click="setActive('Keepsake')"
                >
                    <span :class="['text-xs', currentActive === 'Keepsake' ? 'montserrat-bold' : 'montserrat-light']">Keep Sake Items</span>
                </div>
                <div
                    class="tag"
                    :class="{ 'tag-active': currentActive === 'TimeCapsule' }"
                    @click="setActive('TimeCapsule')"
                >
                    <span :class="['text-xs', currentActive === 'TimeCapsule' ? 'montserrat-bold' : 'montserrat-light']">Time Capsules</span>
                </div>
            </div>
            <ActionButton class="px-1 py-1" @click="navigateToAddMemoryItem">+</ActionButton>
        </div>
        <template v-if="AllItems.length === 0">
            <p class="my-5 text-md montserrat-light">No records available at the moment.</p>
        </template>
        <div class="py-5">
            <div class="max-w-7xl">
                <div
                    class="overflow-hidden shadow-sm sm:rounded-lg max-h-custom overflow-y-scroll"
                >
                    <div :class="['p-6 border-gray-200', AllItems.length === 0 ? '' : 'border-b']">
                        <div class="row gap-5">
                            <div class="col" v-for="item in AllItems" :key="item.id">
                                <MemoryItemCard :memoryItem="item" @removeItem="confirmRemove(item.id)" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <v-dialog v-model="confirmDialog" persistent max-width="400">
            <v-card>
                <v-card-title class="text-h6">Confirm Removal</v-card-title>
                <v-card-text>Are you sure you want to remove this item?</v-card-text>
                <v-card-actions>
                    <v-btn text color="red" @click="confirmDialog = false">Cancel</v-btn>
                    <v-btn text color="green" @click="removeItem">Confirm</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
