<script setup>
import { reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";

const form = reactive({
    memory_item_type: "",
    open_date: "",
    title: "",
    description: "",
    file: "",
    location: "",
    date: "",
    public: false
});

const items = [
    { title: "Memory Thread", value: "Memory Thread" },
    { title: "Keep Sake", value: "Keep Sake" },
    { title: "Time Capsule", value: "Time Capsule" },
];

const submit = () => {
    router.post("/memory-item", form);
};

const navigateToMain = () => {
    router.get("/memory-item");
}

const clearFields = () => {
    form.memory_item_type = "";
    form.open_date = "";
    form.title = "";
    form.description = "";
    form.file = "";
    form.location = "";
    form.date = "";
}

const showMemoryThreadFields = ref(false);
const showKeepSakeFields = ref(false);
const showTimeCapsuleFields = ref(false);

const showAdditionalFields = () => {
    
    switch (form.memory_item_type.title) {
        case "Memory Thread":
            showMemoryThreadFields.value = !showMemoryThreadFields.value;
            showKeepSakeFields.value = false;
            showTimeCapsuleFields.value = false;
            break;
        case "Keep Sake":
            showMemoryThreadFields.value = false;
            showKeepSakeFields.value = true;
            showTimeCapsuleFields.value = false;
            break;
        case "Time Capsule":
            showMemoryThreadFields.value = false;
            showKeepSakeFields.value = false;
            showTimeCapsuleFields.value = true;
            break;
        default:
            showMemoryThreadFields.value = false;
            showKeepSakeFields.value = false;
            showTimeCapsuleFields.value = false;
            break;
    }
}

</script>
<template>
    <div class="p-4 w-full">
        <p class="text-md montserrat-bold">Add Memory Item</p>
        <form @submit.prevent="submit">
            <v-combobox
                label="Select a Memory Item Type"
                v-model="form.memory_item_type"
                :items="items"
                variant="outlined"
                class="max-w-xl mt-4 mb-4"
                id="input-combo1"
                @update:model-value="showAdditionalFields"
            ></v-combobox>
            <v-text-field
                label="Title"
                variant="outlined"
                class="max-w-xl mt-4 mb-4"
                id="input-title"
                v-model="form.title"
            ></v-text-field>
            <v-textarea
                label="Description"
                variant="outlined"
                class="max-w-xl mt-4 mb-4"
                id="input-description"
                v-model="form.description"
            ></v-textarea>
            <template v-if="showMemoryThreadFields">
                <v-text-field
                    label="Location"
                    variant="outlined"
                    class="max-w-xl mt-4 mb-4"
                    id="input-location"
                    v-model="form.location"
                ></v-text-field>
                <VueDatePicker 
                    class="max-w-xl mt-4 mb-9 border-gray-700"
                    placeholder="Date"    
                    v-model="form.open_date"
                ></VueDatePicker>
                
            </template>

            <template v-else-if="showKeepSakeFields">
                
            </template>

            <template v-else-if="showTimeCapsuleFields">
                <VueDatePicker 
                    class="max-w-xl mt-4 mb-4 border-gray-700"
                    placeholder="Select a date to open the time capsule"    
                    v-model="form.open_date"
                ></VueDatePicker>
            </template>
            <v-file-input 
                label="File input" 
                class="max-w-xl mt-4 mb-4" 
                variant="outlined" 
                id="input-file"
                accept="image/*, video/*"
                @input="form.file = $event.target.files[0]"
                @click:clear="form.file = null"
            ></v-file-input>
            <v-checkbox label="Public" v-model="form.public"></v-checkbox>

            <div class="flex justify-end max-w-xl">
                <SecondaryButton class="mt-4" @click="navigateToMain"> Cancel </SecondaryButton>
                <PrimaryButton class="mt-4 ml-4 rounded-lg" type="submit"> Save </PrimaryButton>
            </div>
        </form>
    </div>
</template>
<style scoped>
.dp__theme_light{
    --dp-border-color-focus: #000000;
    --dp-border-color-hover: #000000;
    --dp-menu-border-color: #000000;
    --dp-border-color: #959595;
    --dp-input-padding: 12px;
    --dp-text-color: #000000;
}
</style>
