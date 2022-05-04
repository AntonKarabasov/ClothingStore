<template>
  <div>
    vue app {{ productsCount }}
  </div>
</template>

<script>
  export default {
    computed: {
      productsCount: () => {
        return 123;
      }
    }
  }
</script>