import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from "./src/app/App.vue";


const app = createApp(App)
app.use(createPinia())
app.mount('#app')
 //
 // * Добавляем CSRF-защиту
 // */
let el = document.querySelector('meta[name="csrf-token"]');
let token = el
    ? el.getAttribute('content')
    : null;
axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
