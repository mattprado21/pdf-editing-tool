import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import LandingPage from './components/LandingPage.vue'
import PDFEditor from './components/PDFEditor.vue'
import '../css/app.css';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: LandingPage },
        { path: '/editor', component: PDFEditor, props: true }
    ]
})

const app = createApp(App)
app.use(router)
app.mount('#app')
