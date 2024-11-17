<!-- src/components/nodeHeader.vue -->
<template>
  <v-card-title class="header">{{ title }}</v-card-title>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
})
</script>

<style scoped>
.header {
  background: #494949;
  padding: 10px 15px;
  color: #fff; /* Improve text visibility */
}
</style>
