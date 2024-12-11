<template>
  <div class="node-container">
    <!-- Output Handle for Connecting to Child -->
    <Handle type="source" position="left" id="output" :style="{ height: '16px', width: '16px', backgroundColor: '#da4ad1', zIndex : '2' }"/>
    <v-card class="female-node w-60" outlined>
      <v-card-title>Female</v-card-title>
      <v-card-text>
        <v-text-field
          v-model="name"
          :label="$page.props.auth.user.role !== 'family_admin' ? '' : 'Enter name' "
          dense
          outlined
          @change="handleUpdate"
          :disabled="$page.props.auth.user.role !== 'family_admin'"
          class="text-black"
        ></v-text-field>
      </v-card-text>
    </v-card>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Handle, useVueFlow } from '@vue-flow/core'

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

const { updateNode } = useVueFlow()

const name = ref(props.data.label)

const handleUpdate = () => {
  updateNode(props.id, { data: { label: name.value } })
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

.female-node {
  background-color: #f8d7f6;
}
</style>
