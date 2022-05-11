import * as Vuex from 'vuex';
import products from "./modules/products";

const debug = process.env.NODE_ENV !== "production"

export default new Vuex.Store({
    modules: {
        products
    },
    strict: debug
})