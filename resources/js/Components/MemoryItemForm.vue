<script setup>
import { onMounted, reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";
import axios from "axios";


const props = defineProps({
    item: {
        type: Object,
        required: false
    }

})


const form = reactive({
    id: "",
    thread_type: "",
    open_date: "",
    title: "",
    description: "",
    file: "",
    location: "",
    memThreadDate: "",
    public: true,
    canviewbyUsers: [],
    created_by: "",
    created_at: "",
    profile_picture: "",
    givenFromTo: "",
});

const items = [
    { title: "Memory Thread", value: "Memory Thread" },
    { title: "Keep Sake", value: "Keep Sake" },
    { title: "Time Capsule", value: "Time Capsule" },
];

const submit = () => {
    // router.post("/memory-item", form);

};

const navigateToMain = () => {
    router.get("/memory-item");
}

const getUsersList = async() => {
    axios.get('/get-users').then((response) => {
        usersList.value = response.data.map((user) => ({
            title: user.name,
            value: user.name
        }))
    })
}



const clearFields = () => {
    form.thread_type = "";
    form.open_date = "";
    form.title = "";
    form.description = "";
    form.file = "";
    form.location = "";
    form.date = "";
}

const usersList = ref([
    { title: "Shahadh", value: "Shahadh" },
    { title: "John", value: "John" },
])

const showMemoryThreadFields = ref(false);
const showKeepSakeFields = ref(false);
const showTimeCapsuleFields = ref(false);


const saveToLocalStorage = () => {

    form.id = Math.floor(Math.random() * 1000);

    form.created_by = localStorage.getItem("currentUser");

    form.created_at = new Date();

    // Retrieve existing thread list from localStorage
    let threadList = JSON.parse(localStorage.getItem("threadList")) || [];

    // Convert file to Base64 (if there's a file)
    if (form.file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const fileData = e.target.result;

            // Add file data to the form
            const threadData = {
                ...form,
                file: fileData,
            };

            // Add the form data to the thread list
            threadList.push(threadData);

            // Save updated thread list to localStorage
            localStorage.setItem("threadList", JSON.stringify(threadList));

            // Reset form fields after saving
            clearFields();
            alert("Memory item saved successfully!");
        };
        reader.readAsDataURL(form.file);
    } else {
        // Add form data without file to the thread list
        threadList.push({ ...form });

        // Save updated thread list to localStorage
        localStorage.setItem("threadList", JSON.stringify(threadList));

        // Reset form fields after saving
        clearFields();
        alert("Memory item saved successfully!");
    }
    router.get("/memory-item");
};

const updateToLocalStorage = () => {
    // Retrieve existing thread list from localStorage
    let threadList = JSON.parse(localStorage.getItem("threadList")) || [];

    // Find the index of the item to update
    const itemIndex = threadList.findIndex(item => item.id === form.id);

    if (itemIndex !== -1) {
        // Update the item with new values from the form
        threadList[itemIndex] = {
            ...threadList[itemIndex], // Retain any existing properties
            ...form, // Overwrite with updated properties from the form
        };

        // If there's a file, handle Base64 conversion
        if (form.file && form.file instanceof File) {
            const reader = new FileReader();
            reader.onload = (e) => {
                threadList[itemIndex].file = e.target.result; // Update file as Base64
                // Save updated thread list back to localStorage
                localStorage.setItem("threadList", JSON.stringify(threadList));
                alert("Memory item updated successfully!");
                clearFields();
                router.get("/memory-item");
            };
            reader.readAsDataURL(form.file);
        } else {
            // Save updated thread list back to localStorage
            localStorage.setItem("threadList", JSON.stringify(threadList));
            alert("Memory item updated successfully!");
            clearFields();
            router.get("/memory-item");
        }
    } else {
        alert("Item not found for updating.");
    }
};


const showAdditionalFields = () => {
    
    switch (form.thread_type.title) {
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

onMounted(async() => {
    const currentItemId = localStorage.getItem('currentEditingItem') || null;
    if (currentItemId) {
        
        const itemToEdit = JSON.parse(localStorage.getItem('threadList')).find(item => item.id == currentItemId);


        if (itemToEdit) {
            form.id = itemToEdit.id;
            form.thread_type = itemToEdit.thread_type;
            form.open_date = itemToEdit.open_date;
            form.title = itemToEdit.title;
            form.description = itemToEdit.description;
            form.file = itemToEdit.file;
            form.location = itemToEdit.location;
            form.date = itemToEdit.date;
            form.public = itemToEdit.public;
            form.canviewbyUsers = itemToEdit.canviewbyUsers;
            form.created_by = itemToEdit.created_by;
            form.created_at = itemToEdit.created_at;
            form.profile_picture = itemToEdit.profile_picture;
            form.givenFromTo = itemToEdit.givenFromTo;
            editing.value = true;
        }
    }

    await getUsersList();
});

const editing = ref(false);

</script>
<template>
    <div class="p-4 w-full">
        <p class="text-md montserrat-bold"><span>{{ editing ? "Edit" : "Add" }}</span> Memory Item</p>
        <form @submit.prevent="submit">
            <v-combobox
                label="Select a Memory Item Type"
                v-model="form.thread_type"
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
                <v-combobox
                    label="Given To"
                    v-model="form.givenFromTo"
                    :items="usersList"
                    variant="outlined"
                    class="max-w-xl mt-4 mb-4"
                    id="input-combo3"
                ></v-combobox>
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

            <v-combobox
                label="Select users who can view"
                v-model="form.canviewbyUsers"
                :items="usersList"
                variant="outlined"
                class="max-w-xl mt-4 mb-4"
                id="input-combo1"
                multiple
                chips
                v-if="!form.public"
            ></v-combobox>
            <div class="flex justify-end max-w-xl">
                <SecondaryButton class="mt-4" @click="navigateToMain"> Cancel </SecondaryButton>
                <PrimaryButton class="mt-4 ml-4 rounded-lg" @click="saveToLocalStorage" v-if="!editing"> Save </PrimaryButton>
                <PrimaryButton class="mt-4 ml-4 rounded-lg" @click="updateToLocalStorage" v-else-if="editing"> Update </PrimaryButton>
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
