<script setup>
import FamilyTree from "@/Components/FamilyTree.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";

defineProps({
    treeData: {
        type: Object,
        required: true,
    },
});

const showModal = ref(false);

const showInvitationModal = () => {
    showModal.value = true; // Open the modal when the button is clicked
};

const closeModal = () => {
    showModal.value = false; // Close the modal
};

const email = ref("");

const snackbar = ref(true); 
const snackbarText = ref('');

const sendInvitation = () => {
    
    axios.post('/invite-user', {
        email: email.value,
    }).then((response) => {
        snackbarText.value = "Invitation sent successfully.";
    }).catch((error) => {
        snackbarText.value = "Error sending invitation.";
    })

    closeModal();
};

</script>

<template>
    <Head title="Memory Items" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Family Tree</h2>
        </template>
        <v-snackbar v-model="snackbar" :timeout="3000" color="green" v-if="snackbarText != ''" >
            {{  snackbarText }}
            <template v-slot:action="{ attrs }">
            <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
                Close
            </v-btn>
            </template>
        </v-snackbar>
        <div class="flex justify-end">
            <button
                class="bg-slate-800 text-white px-4 py-2 rounded-lg montserrat-light"
                @click="showInvitationModal"
                v-if="$page.props.auth.user.role === 'family_admin'"
            >
                Send Invitation
            </button>
        </div>

        <FamilyTree class="my-6" :treeData="treeData" />

        <Modal :show="showModal" @close="closeModal">
            <template #default>
                <!-- Modal content (email form) -->
                <h3 class="text-2xl font-semibold p-6 montserrat-light">Send Invitation</h3>
                <form @submit.prevent="sendInvitation">
                    <div class="px-6">
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700 montserrat-light"
                            >Email Address</label
                        >
                        <input
                            type="email"
                            id="email"
                            v-model="email"
                            required
                            class="mt-2 p-2 w-full border border-gray-300 rounded-md text-sm montserrat-light"
                            placeholder="Enter email address"
                        />
                    </div>
                    <div class="mt-4 flex justify-end p-6">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg mr-2 montserrat-light"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg montserrat-light"
                        >
                            Send Invitation
                        </button>
                    </div>
                </form>
            </template>
        </Modal>
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
