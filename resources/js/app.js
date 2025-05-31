import './bootstrap'
import '../css/app.css'
import * as bootstrap from 'bootstrap'
import { createApp } from 'vue';
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'

import Vue3Toastify from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const pinia = createPinia()
const app = createApp(App)
app.use(router);
app.use(pinia);
app.use(Vue3Toastify, {
  autoClose: 3000,
  theme: 'colored',
  clearOnUrlChange: false,
})
app.mount('#app')
