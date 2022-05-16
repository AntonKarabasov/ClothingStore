<template>
  <div class="row">
    <div class="col-lg-12 order-block">
      <div class="order-content">
        <Alert/>
        <div v-if="showCartContent">
          <CartProductList/>
          <TotalPrice/>
          <a
              class="btn btn-success mb-3 text-white"
              @click="makeOrder"
          >
            MAKE ORDER
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CartProductList from "./components/CartProductList";
import TotalPrice from "./components/TotalPrice";
import Alert from "./components/Alert";
import {mapActions, mapMutations, mapState} from "vuex";
export default {
  name: "App",
  components: {TotalPrice, CartProductList, Alert},
  created() {
    this.getCart();
  },
  computed: {
    ...mapState("cart", ["cart", "isSentForm"]),
    showCartContent() {
      return !this.isSentForm && Object.keys(this.cart).length;
    }
  },
  methods: {
    ...mapActions("cart", ["getCart", "makeOrder"]),

  }
}
</script>

<style scoped>

</style>