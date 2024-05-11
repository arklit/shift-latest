import {createRouter, createWebHistory} from 'vue-router'
import Home from '../../pages/Home.vue'
import App from '@/app/App.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: App
        },
    ]
})

export default router
