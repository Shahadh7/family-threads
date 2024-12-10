<template>
  <div class="node-container">
    <!-- Input Handles for Parents -->
    <Handle type="target" position="left" id="input-father" />
    <Handle type="target" position="left" id="input-mother" />
    <v-card class="child-node" outlined>
      <v-card-title>Child</v-card-title>
      <v-card-text>
        <v-text-field
          v-model="name"
          label="Enter name"
          dense
          outlined
          @change="updateNode"
        ></v-text-field>
      </v-card-text>
    </v-card>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Handle } from '@vue-flow/core'

const props = defineProps({
  id: {
    type: String,
    required: true,
  },
  data: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['updateNode'])

const name = ref(props.data.label)

const updateNode = () => {
  emit('updateNode', { id: props.id, name: name.value })
}

watch(
  () => props.data.label,
  (newVal) => {
    if (newVal !== name.value) {
      name.value = newVal
    }
  }
)
</script>

<style scoped>
.node-container {
  position: relative;
}

.child-node {
  background-color: #d4edda;
}
</style>
