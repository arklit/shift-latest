import {createApp} from 'vue/dist/vue.esm-bundler';
import {store} from "./store/store.js";
import FormBuilder from "./components/FormBuilder.vue";

if (document.getElementById("app") !== null) {
    const app = createApp({})
        .component('form-builder', FormBuilder)
        .use(store)
        .mount('#app')
}

