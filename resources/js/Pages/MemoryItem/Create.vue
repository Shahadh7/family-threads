<script setup>
// import ActionButton from "@/Components/ActionButton.vue";
// import MemoryItemCard from "@/Components/MemoryItemCard.vue";
import MemoryItemForm from "@/Components/MemoryItemForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const breadCrumbs = ref([
    {
        title: "Memory Items",
        disabled: false,
        href: "/memory-item",
    },
    {
        title: "Add",
        disabled: true,
        href: "/memory-item/create",
    },
]);

</script>

<template>
    <Head title="Add Memory Item" />

    <AuthenticatedLayout :name="$page.props.auth.user.name">
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Memory Items</h2>
        </template>
        <div>
            <v-breadcrumbs :items="breadCrumbs">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
            </v-breadcrumbs>
        </div>

        <div class="p-6 bg-white border-b border-gray-200 max-h-custom">
            <MemoryItemForm />
        </div>

    </AuthenticatedLayout>
</template>
<style scoped>
.max-h-custom {
    max-height: calc(100vh - 280px);
    overflow-y: scroll;
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

.underline {
    width: 70px; /* Adjust width as needed */
    height: 4px; /* Adjust thickness */
    background-color: #e2d65e; /* Yellow color */
    margin-top: 4px; /* Add spacing from breadcrumb text */
    border-radius: 5px; /* Rounded edges */
}

.v-breadcrumbs{
    padding-left: 0 !important;
}
</style>
