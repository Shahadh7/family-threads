<script setup>
import { ref } from 'vue';
import ActionButton from './ActionButton.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    memoryItem: {
        type: Object,
        required: true
    }
})

const currentEditingItem = ref(null);

const saveCurentEditingItem = (id) => {
    localStorage.setItem('currentEditingItem', id);
}


</script>
<template>
    <div class="memory-item-card">
        <div class="memory-item-card-image relative">
            <img :src="memoryItem.file" alt="memory-item" />
            <div class="flex flex-col absolute gap-2 top-3 right-3">
                <ActionButton @click="$emit('removeItem', memoryItem.id)">
                    <span class="mdi mdi-close text-lg"></span>
                </ActionButton>
                <Link @click="saveCurentEditingItem(memoryItem.id)" :href="route('memory-items.show', memoryItem.id)" >
                    <ActionButton>
                        <span class="mdi mdi-pencil text-lg"></span>
                    </ActionButton>
                </Link>
            </div>
        </div>
    </div>
</template>
<style scoped>

.memory-item-card {
    width: 400px;
    height: 400px;
    border-radius: 10px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
}

.memory-item-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: box-shadow 0.3s ease-in-out;
}

.memory-item-card-image {
    width: 100%;
    height: 100%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}
img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
img:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease-in-out;
}

</style>