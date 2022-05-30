require('./bootstrap');

import { createApp } from 'vue'
import App from "./App.vue"
import News from './components/NewsComponent.vue'
import { createRouter, createWebHistory } from 'vue-router'
import HomePageComponent from './components/HomePageComponent.vue'

import NProgress from 'nprogress';
import '../../node_modules/nprogress/nprogress.css'

const app = createApp({})

app.component('app', App)

const routes = [
    {
        path: "/",
        name: "Home",
        component: HomePageComponent,
    },
    {
        path: "/news",
        name: "News",
        component: News,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeResolve((to, from, next) => {
    if (to.name) {
        NProgress.start()
    }
    next()
})

router.afterEach((to, from) => {
    NProgress.done()
})

app.use(router)

app.mount('#app')
