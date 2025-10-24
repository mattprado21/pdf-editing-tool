import { createApp } from 'vue'
import FabricPDFEditor from './components/FabricPDFEditor.vue'

const mountEl = document.getElementById('app')
const initialFile = mountEl?.dataset?.initialFile || ''

createApp(FabricPDFEditor, { initialFile }).mount('#app')


