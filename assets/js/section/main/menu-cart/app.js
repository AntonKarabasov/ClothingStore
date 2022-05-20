import * as Vue from 'vue';
import App from './App';
import store from "./store";

if (document.getElementById('appMainMenuCart')) {
    Vue.createApp(App).use(store).mount('#appMainMenuCart');
}

window.vueMenuCartInstance = {};
window.vueMenuCartInstance.addCartProduct =
    (productData) => store.dispatch('cart/addCartProduct', productData);

window.vueMenuCartInstance.cleanCartForMainShowCart =
    () => store.dispatch('cart/cleanCartForMainShowCart')