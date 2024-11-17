<script setup>
import FamilyTree from "@/Components/FamilyTree.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";


defineProps({
    treeData: {
        type: Object,
        required: true
    }
})

</script>

<template>
    <Head title="Memory Items" />
    <AuthenticatedLayout :name="$page.props.auth.user.name">
        <template #header>
            <h2 class="text-3xl leading-tight text-gray-800">Family Tree</h2>
        </template>

        <FamilyTree  class="my-12" :treeData="treeData"/>
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
