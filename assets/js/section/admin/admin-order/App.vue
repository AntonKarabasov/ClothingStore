<template>
  <div class="table-additional-selection">
    <OrderProductAdd/>
    <hr>
      <OrderProductItem
        v-for="(orderProduct, index) in orderProducts"
        :key="orderProduct.id"
        :order-product="orderProduct"
        :index="index"
      />
    <hr>
    <TotalPriceBlock/>
  </div>
</template>

<script>
  import { mapState, mapActions } from "vuex";
  import OrderProductItem from "./components/OrderProductItem";
  import OrderProductAdd from "./components/OrderProductAdd";
  import TotalPriceBlock from "./components/TotalPriceBlock";

  export default {
    components: {OrderProductAdd, OrderProductItem, TotalPriceBlock},
    created() {
      this.getCategories();
      this.getOrderProducts();
    },
    computed: {
      ...mapState("products", ["orderProducts"]),
    },
    methods: {
      ...mapActions("products", ["getCategories", "getOrderProducts"])
    }
  }
</script>
