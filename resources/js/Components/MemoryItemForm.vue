<script setup>
import { onMounted, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";


const props = defineProps({
    item: {
        type: Object,
        required: false
    }

})


const form = useForm({
    id: "",
    title: null,
    description: null,
    file: null,
    public: true,
    type: null,
    location: null,
    date: null,
    open_date: null,
    given_to_user_id: null,
    user: null,
    can_be_viewed_by: [],
    added_by_user_id: null,
});

const items = [
    { title: "Memory Thread", value: "MemoryThread" },
    { title: "Keep Sake", value: "Keepsake" },
    { title: "Time Capsule", value: "TimeCapsule" },
];

const submit = () => {

    if (form.user && form.user.value) {
        form.given_to_user_id = form.user.value; // Set the value as the ID
    } else {
        form.given_to_user_id = null; // Set to null if no user is selected
    }

    router.post("/memory-item", form);
};

const update = () => {
    // router.put("/memory-item/" + form.id, form);
};

const navigateToMain = () => {
    router.get("/memory-item");
}

const getUsersList = async() => {
    axios.get('/get-users').then((response) => {
        usersList.value = response.data.map((user) => ({
            title: user.name,
            value: user.id
        }))
    })
}



const clearFields = () => {
    form.type = "";
    form.open_date = "";
    form.title = "";
    form.description = "";
    form.file = "";
    form.location = "";
    form.date = "";
}

const usersList = ref([])

const showMemoryThreadFields = ref(false);
const showKeepSakeFields = ref(false);
const showTimeCapsuleFields = ref(false);



const showAdditionalFields = () => {
    
    switch (form.type.value) {
        case "MemoryThread":
            showMemoryThreadFields.value = !showMemoryThreadFields.value;
            showKeepSakeFields.value = false;
            showTimeCapsuleFields.value = false;
            break;
        case "Keepsake":
            showMemoryThreadFields.value = false;
            showKeepSakeFields.value = true;
            showTimeCapsuleFields.value = false;
            break;
        case "TimeCapsule":
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

    form.type = form.type.value
}

onMounted(async() => {

    await getUsersList();
});

const editing = ref(false);

const date = ref(new Date());

const format = (date) => {
  const day = date.getDate();
  const month = date.getMonth() + 1;
  const year = date.getFullYear();

  const hours = date.getHours();
  const minutes = date.getMinutes();
  const seconds = date.getSeconds();

  return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}

</script>
<template>
    <div class="p-4 w-full">
        <p class="text-md montserrat-bold"><span>{{ editing ? "Edit" : "Add" }}</span> Memory Item</p>
        <form @submit.prevent="submit">
            <v-combobox
                label="Select a Memory Item Type"
                v-model="form.type"
                :items="items"
                item-value="value" 
                item-text="title" 
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
                    v-model="form.date"
                ></VueDatePicker>
                
            </template>

            <template v-else-if="showKeepSakeFields">
                <v-combobox
                    label="Given To"
                    v-model="form.user"
                    item-value="value" 
                    item-text="title"
                    :items="usersList"
                    variant="outlined"
                    class="max-w-xl mt-4 mb-4"
                    id="input-combo3"
                ></v-combobox>
            </template>

            <template v-else-if="showTimeCapsuleFields">
                <VueDatePicker 
                    class="max-w-xl mt-4 mb-4 border-gray-700"
                    placeholder="Select a date to open the TimeCapsule"    
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

            <v-combobox
                label="Select users who can view"
                v-model="form.can_be_viewed_by"
                :items="usersList"
                item-value="value" 
                item-text="title"
                variant="outlined"
                class="max-w-xl mt-4 mb-4"
                id="input-combo1"
                multiple
                chips
                v-if="!form.public"
            ></v-combobox>
            <div class="flex justify-end max-w-xl">
                <SecondaryButton class="mt-4" @click="navigateToMain"> Cancel </SecondaryButton>
                <PrimaryButton class="mt-4 ml-4 rounded-lg" @click="submit" v-if="!editing"> Save </PrimaryButton>
                <PrimaryButton class="mt-4 ml-4 rounded-lg" @click="update" v-else-if="editing"> Update </PrimaryButton>
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
