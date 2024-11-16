<template>
    <button
        class="flex items-center rounded-lg bg-black px-3 py-1 text-2xl font-bold  text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-slate-950 focus:ring-offset-2 active:bg-gray-900"
    >
        <slot />
    </button>
</template>
<style scoped>
.bg-black {
    background-color: #000000;
}
</style>