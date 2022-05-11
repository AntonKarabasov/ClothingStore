import * as Vue from 'vue';
import App from './App';
import store from "./store";

if (document.getElementById('app')) {
    Vue.createApp(App).use(store).mount('#app');
}