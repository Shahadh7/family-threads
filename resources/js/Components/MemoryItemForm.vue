<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";


const props = defineProps({
    memoryItem: {
        type: Object,
        required: false
    },
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

    if(!editing.value) {
        router.post("/memory-item", form);

    }else {
        router.put("/memory-item/" + form.id, form);
    }
    
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

watch(
    () => usersList.value.length,
    () => {
        if (usersList.value.length > 0 && form.type == "Keepsake") {
            let user = usersList.value.find(user => user.value == form.added_by_user_id);
            if (user) {
                form.user = user;
            }
        }
    }
)

onMounted(async() => {
    await getUsersList();
    if (props.memoryItem) {
        editing.value = true;
        if(props.memoryItem.type == "MemoryThread") {
            showMemoryThreadFields.value = !showMemoryThreadFields.value;
            form.id = props.memoryItem.id;
            form.type = props.memoryItem.type;
            form.public = props.memoryItem.public == 1 ? true : false;
            form.title = props.memoryItem.title;
            form.description = props.memoryItem.description;
            form.location = props.memoryItem.memory_thread.location;
            form.date = props.memoryItem.memory_thread.date;
            form.added_by_user_id = props.memoryItem.added_by_user_id;
            if(form.public == false) {
                form.can_be_viewed_by = JSON.parse(props.memoryItem.can_be_viewed_by);
            }
            
        }
        else if(props.memoryItem.type == "Keepsake") {
            showKeepSakeFields.value = true;
            form.id = props.memoryItem.id;
            form.type = props.memoryItem.type;
            form.public = props.memoryItem.public == 1 ? true : false;
            form.title = props.memoryItem.title;
            form.description = props.memoryItem.description;
            form.added_by_user_id = props.memoryItem.added_by_user_id;
            if(form.public == false) {
                form.can_be_viewed_by = JSON.parse(props.memoryItem.can_be_viewed_by);
            }
            

        }else if(props.memoryItem.type == "TimeCapsule") {
            showTimeCapsuleFields.value = !showTimeCapsuleFields.value;
            form.id = props.memoryItem.id;
            form.type = props.memoryItem.type;
            form.public = props.memoryItem.public == 1 ? true : false;
            form.title = props.memoryItem.title;
            form.description = props.memoryItem.description;
            form.added_by_user_id = props.memoryItem.added_by_user_id;
            if(form.public == false) {
                form.can_be_viewed_by = JSON.parse(props.memoryItem.can_be_viewed_by);
            }
            form.open_date = props.memoryItem.time_capsule.open_date;
        }
    }

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
        
        {{ $page.props.errors }}
        <p class="text-md montserrat-bold"><span>{{ editing ? "Edit" : "Add" }}</span> Memory Item</p>
        <form @submit.prevent="submit">
            <v-combobox
                label="Select a Memory Item Type"
                v-model="form.type"
                :items="items"
                item-value="value" 
                item-text="title" 
                variant="outlined"
                :class="['max-w-xl mt-4', $page.props.errors.type ? 'mb-2' : 'mb-8']"
                id="input-combo1"
                @update:model-value="showAdditionalFields"
                v-bind:hide-selected="true"
                hide-details="auto"
            ></v-combobox>
            <InputError class="mb-2" :message="$page.props.errors.type" />
            <v-text-field
                label="Title"
                variant="outlined"
                id="input-title"
                v-model="form.title"
                :class="['max-w-xl mt-4', $page.props.errors.title ? 'mb-2' : 'mb-8']"
                hide-details="auto"
            ></v-text-field>
            <InputError class="mb-2" :message="$page.props.errors.title" />
            <v-textarea
                label="Description"
                variant="outlined"
                :class="['max-w-xl mt-4', $page.props.errors.description ? 'mb-2' : 'mb-8']"
                id="input-description"
                v-model="form.description"
                hide-details="auto"
            ></v-textarea>
            <InputError class="mb-2" :message="$page.props.errors.description" />
            <template v-if="showMemoryThreadFields">
                <v-text-field
                    label="Location"
                    variant="outlined"
                    :class="['max-w-xl mt-4', $page.props.errors.location ? 'mb-2' : 'mb-8']"
                    id="input-location"
                    v-model="form.location"
                    hide-details="auto"
                ></v-text-field>
                <InputError class="mb-2" :message="$page.props.errors.location" />
                <VueDatePicker 
                    :class="['max-w-xl mt-4 border-gray-700', $page.props.errors.date ? 'mb-2' : 'mb-8']"
                    placeholder="Date"    
                    v-model="form.date"
                ></VueDatePicker>
                <InputError class="mb-2" :message="$page.props.errors.date" />
            </template>

            <template v-else-if="showKeepSakeFields">
                <v-combobox
                    label="Given To"
                    v-model="form.user"
                    item-value="value" 
                    item-text="title"
                    :items="usersList"
                    variant="outlined"
                    :class="['max-w-xl mt-4', $page.props.errors.given_to_user_id ? 'mb-2' : 'mb-8']"
                    id="input-combo3"
                    hide-details="auto"
                ></v-combobox>
                <InputError class="mb-2" :message="$page.props.errors.given_to_user_id" />
            </template>

            <template v-else-if="showTimeCapsuleFields">
                <VueDatePicker 
                    :class="['max-w-xl mt-4 border-gray-700', $page.props.errors.open_date ? 'mb-2' : 'mb-8']"
                    placeholder="Select a date to open the TimeCapsule"    
                    v-model="form.open_date"
                ></VueDatePicker>
                <InputError class="mb-2" :message="$page.props.errors.open_date" />
            </template>
            <v-file-input 
                label="File input"  
                :class="['max-w-xl mt-4 border-gray-700', $page.props.errors.file ? 'mb-2' : 'mb-8']"
                variant="outlined" 
                id="input-file"
                accept="image/*, video/*"
                @input="form.file = $event.target.files[0]"
                @click:clear="form.file = null"
                hide-details="auto"
            ></v-file-input>
            <InputError class="mb-2" :message="$page.props.errors.file" />
            <v-checkbox label="Public" v-model="form.public" hide-details="auto"></v-checkbox>

            <v-combobox
                label="Select users who can view"
                v-model="form.can_be_viewed_by"
                :items="usersList"
                item-value="value"
                item-text="title"
                variant="outlined"
                :class="['max-w-xl mt-4 border-gray-700', $page.props.errors.can_be_viewed_by ? 'mb-2' : 'mb-8']"
                id="input-combo1"
                multiple
                chips
                v-if="!form.public"
                v-bind:hide-selected="true"
                hide-details="auto"
            ></v-combobox>
            <InputError class="mb-2" :message="$page.props.errors.can_be_viewed_by" v-if="!form.public" />
            <div class="flex justify-end max-w-xl">
                <SecondaryButton class="mt-4" @click="navigateToMain"> Cancel </SecondaryButton>
                <PrimaryButton class="mt-4 ml-4 rounded-lg" type="submit" v-if="!editing"> Save </PrimaryButton>
                <PrimaryButton class="mt-4 ml-4 rounded-lg" type="submit" v-else-if="editing"> Update </PrimaryButton>
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
