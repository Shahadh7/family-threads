<template>
    
    <v-container fluid class="pa-0 max-h-52">
      <div
          ref="vueFlowRef"
          class="vueflow-canvas"
          @drop="drop"
          @dragover="allowDrop"
          @touchmove.prevent
        >
          <VueFlow
            v-model:nodes="nodes"
            v-model:edges="edges"
            :node-types="nodeTypes"
            :fit-view="true"
            @connect="onConnect"
          >
            <Panel class="absolute top-3 right-3">
              <div class="flex gap-4 flex-col">
                <button
                  v-for="node in listNodes"
                  :key="node.type"
                  draggable="true"
                  :data-node-type="node.type"
                  @dragstart="dragStart"
                  class="drag-item"
                >
                <span :class="[node.class, node.icon, ]"></span>
              </button>
              </div>
            </Panel>
            <Controls />
            <div class="flex justify-end gap-3 absolute bottom-3 right-3 z-10">
              <v-btn color="secondary" @click="exportTree">Export</v-btn>
              <v-btn color="secondary" @click="saveTree" v-if="!props.treeData && $page.props.auth.user.role === 'family_admin'">Save</v-btn>
              <v-btn color="secondary" @click="updateTree" v-if="props.treeData && $page.props.auth.user.role === 'family_admin'">Update</v-btn>
              <!-- <v-btn color="secondary" @click="loadTree">Load</v-btn> -->
            </div>
          </VueFlow>
        </div>

      <!-- Export Dialog -->
      <v-dialog v-model="dialogVisible" max-width="600px">
        <v-card>
          <v-card-title class="headline">Export Family Tree</v-card-title>
          <v-card-text>
            <pre>{{ dialogData }}</pre>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="dialogVisible = false">Cancel</v-btn>
            <v-btn color="primary" text @click="downloadJSON">Download</v-btn>
            <v-btn color="primary" text @click="copyToClipboard">Copy</v-btn>
            <v-btn color="primary" text @click="dialogVisible = false">Confirm</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <!-- Snackbar for Notifications -->
      <v-snackbar v-model="snackbar" :timeout="3000" top right>
        {{ snackbarText }}
        <template #action>
          <v-btn text @click="snackbar = false">Close</v-btn>
        </template>
      </v-snackbar>
    </v-container>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Panel, VueFlow, useVueFlow } from '@vue-flow/core'
import { Controls } from '@vue-flow/controls'
import { MiniMap } from '@vue-flow/minimap'
import { Background } from '@vue-flow/background'
import '@vue-flow/core/dist/style.css'
import '@vue-flow/core/dist/theme-default.css'
import '@vue-flow/controls/dist/style.css'

import MaleNode from './nodes/MaleNode.vue'
import FemaleNode from './nodes/FemaleNode.vue'
import ChildNode from './nodes/ChildNode.vue'

import { v4 as uuidv4 } from 'uuid'
import { markRaw } from 'vue'
import { router } from '@inertiajs/vue3'


const props= defineProps({
  treeData: {
    type: Object,
    required: true
  }
})

const tree = ref((props.treeData) === null ? {nodes: [], edges: []} : JSON.parse(props.treeData.tree_data) );


// Define node types using markRaw
const nodeTypes = {
  MaleNode: markRaw(MaleNode),
  FemaleNode: markRaw(FemaleNode),
  ChildNode: markRaw(ChildNode),
}

// Define the list of draggable nodes
const listNodes = [
  {
    type: 'MaleNode',
    icon: 'mdi mdi-human-male text-4xl',
    class: 'male-node-preview',
    color: '#cce5ff',
  },
  {
    type: 'FemaleNode',
    icon: 'mdi mdi-human-female text-4xl',
    class: 'female-node-preview',
    color: '#f8d7da',
  },
  // {
  //   type: 'ChildNode',
  //   label: 'Child',
  //   class: 'child-node-preview',
  //   color: '#d4edda',
  // },
]

// References and reactive state
const vueFlowRef = ref(null)
const dialogVisible = ref(false)
const dialogData = ref('')
const snackbar = ref(false)
const snackbarText = ref('')

const nodes = ref(tree.value.nodes)
const edges = ref(tree.value.edges)

// Vue Flow instance
const { addNodes, addEdges } = useVueFlow()

// Drag and Drop Functions
const dragStart = (event) => {
  const nodeType = event.target.getAttribute('data-node-type')
  event.dataTransfer.setData('node-type', nodeType)
}

const allowDrop = (event) => {
  event.preventDefault()
}

const drop = (event) => {
  event.preventDefault()
  const nodeType = event.dataTransfer.getData('node-type')
  if (nodeType) {
    const rect = vueFlowRef.value.getBoundingClientRect()
    const posX = event.clientX - rect.left
    const posY = event.clientY - rect.top

    addNode(nodeType, posX, posY)
  }
}

// Function to add a node to Vue Flow
const addNode = (type, x, y) => {
  const nodeName =
    type === 'MaleNode'
      ? 'Male Member'
      : type === 'FemaleNode'
      ? 'Female Member'
      : 'Child'

  const newNode = {
    id: uuidv4(),
    type: type,
    position: { x, y },
    data: { label: nodeName },
  }

  nodes.value.push(newNode)
}

// Function to handle connection creation
const onConnect = (connection) => {
  const { source, target } = connection

  // Determine the relationship based on the source node type
  const sourceNode = nodes.value.find((n) => n.id === source)

  // Add the new edge to the edges array with a label and style
  const newEdge = {
    id: uuidv4(),
    ...connection,
    type: 'smoothstep',
    animated: true,
    labelBgPadding: [8, 4],
    labelBgBorderRadius: 4,
    style: { stroke: sourceNode.type === 'MaleNode' ? '#007bff' : '#e83e8c' },
  }

  edges.value.push(newEdge)
}

// Export Function
const exportTree = () => {
  const exportData = {
    nodes: nodes.value,
    edges: edges.value,
  }
  dialogData.value = JSON.stringify(exportData, null, 2)
  dialogVisible.value = true
}

// Download JSON
const downloadJSON = () => {
  const blob = new Blob([dialogData.value], { type: 'application/json' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = 'family-tree.json'
  link.click()
  URL.revokeObjectURL(url)
  showSnackbar('Downloaded family tree as JSON!')
}

// Copy to Clipboard
const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(dialogData.value)
    showSnackbar('Copied to clipboard!')
  } catch (err) {
    console.error('Failed to copy: ', err)
    showSnackbar('Failed to copy!')
  }
}

// Save to Database
const saveTree = () => {
  const family_tree = {
    nodes: nodes.value,
    edges: edges.value,
  }
  router.post('/family-tree', {tree_data: JSON.stringify(family_tree)})

  showSnackbar('Family tree saved successfully!')
}

const updateTree = () => {
  const family_tree = {
    nodes: nodes.value,
    edges: edges.value,
  }
  router.put('/family-tree', {tree_data: JSON.stringify(family_tree)})
  showSnackbar('Family tree updated successfully!')
}

// Load from Local Storage
const loadTree = () => {
  const savedData = localStorage.getItem('familyTree')
  if (savedData) {
    const parsedData = JSON.parse(savedData)
    nodes.value = parsedData.nodes || []
    edges.value = parsedData.edges || []
    showSnackbar('Family tree loaded from local storage!')
  } else {
    showSnackbar('No saved family tree found.')
  }
}

// Snackbar Function
const showSnackbar = (message) => {
  snackbarText.value = message
  snackbar.value = true
}

</script>

<style scoped>

.drag-item {
  margin-bottom: 10px;
  cursor: grab;
}

.male-node-preview,
.female-node-preview,
.child-node-preview {
  padding: 10px;
  border-radius: 4px;
  text-align: center;
  color: #fff;
}

.male-node-preview {
  background-color: #007bff;
}

.female-node-preview {
  background-color: #e83e8c;
}

.child-node-preview {
  background-color: #28a745;
}

.vueflow-canvas {
  width: 100%;
  height: 600px;
  background: #ffffff;
  position: relative;
}
</style>
