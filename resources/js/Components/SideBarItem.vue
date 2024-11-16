<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'bg-white montserrat-bold'
        : 'bg-transparent montserrat-light border-transparent text-sm hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="[classes, 'px-3 py-2 flex items-center w-40 rounded-lg ']">
        <slot />
    </Link>
</template>
