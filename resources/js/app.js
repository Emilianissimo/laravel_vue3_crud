require('./bootstrap');

import { createApp } from 'vue'
import News from './App.vue'

const app = createApp({})

app.component('news', News)

app.mount('#app')
