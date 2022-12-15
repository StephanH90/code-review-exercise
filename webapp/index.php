<?php $page_title = "Home ★ Productive"; ?>
<?php require "view/blocks/page_start.php"; ?>
<h1>Welcome to Productive!</h1>

<h2>Our most popular products</h2>

<div id="app">
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
      </tr>
    </thead>
    <tbody id="products-table">
      <tr v-for="product in products">
        <td>{{product.name}}</td>
        <td>{{product.price}}</td>
        <td :style="{ color: stockColor(product) }">{{product.stock}}</td>
      </tr>
    </tbody>
  </table>
</div>

<script>
  const { createApp } = Vue

  createApp({
    data() {
      return {
        products: []
      }
    },
    methods: {
      stockColor(product) {
        return product.stock <= 3 ? 'red' : 'inherit';
      }
    },
    async mounted() {
      const result = await fetch('API/V1/popular-products')
      this.products = await result.json()
    }
  }).mount('#app')
</script>

<?php require "view/blocks/page_end.php"; ?>