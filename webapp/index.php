<?php $page_title = "Home ★ Productive"; ?>
<?php require "view/blocks/page_start.php"; ?>
<h1>Welcome to Productive!</h1>

<h2>Our most popular products</h2>

<div id="app">
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Stock</th>
      </tr>
    </thead>
    <tbody id="products-table">
      <tr v-for="product in products" :data-test-product-id="product.product_id">
        <td>{{product.name}}</td>
        <td
          v-if="product.id_category && categoryForProduct(product)"
        >
          {{categoryForProduct(product).name}}  
        </td>
        <td v-else>Keine Kategorie</td>
        <td>{{product.price}}</td>
        <td
          :style="{ color: stockColor(product) }"
          :data-test-stock-id="product.product_id"
        >{{product.stock}}</td>
      </tr>
    </tbody>
  </table>
</div>

<script>
  const { createApp } = Vue

  createApp({
    data() {
      return {
        products: [],
        categories: []
      }
    },
    methods: {
      stockColor(product) {
        return product.stock <= 3 ? 'red' : 'inherit';
      },
      categoryForProduct(product) {
        return this.categories?.find((category) => category.category_id === product.id_category)
      }
    },
    async mounted() {
      // Fetch products
      const productsResult = await fetch('API/V1/popular-products')
      // Fetch categories
      const categoriesResult = await fetch('API/V1/Categories')
      
      this.products = await productsResult.json()
      this.categories = await categoriesResult.json()
    }
  }).mount('#app')
</script>

<?php require "view/blocks/page_end.php"; ?>