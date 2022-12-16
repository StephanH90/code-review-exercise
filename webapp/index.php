<?php $page_title = "Home ★ Productive"; ?>
<?php require "view/blocks/page_start.php"; ?>
<h1>Welcome to Productive!</h1>

<h2>Our most popular products</h2>

<div id="app">
  <div style="margin-bottom: 1em;">
    <label for="category-filter">Nach Kategorie filtern: </label>
    <select
      name="category-filter"
      v-model="selectedCategoryId"
    >
      <option value="">Kein Filter</option>
      <option
        v-for="category in categories"
        :key="category.category_id"
        :value="category.category_id"
      >
        {{category.name}}
      </option>
    </select>
  </div>

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
      <tr v-for="product in filteredProducts" :data-test-product-id="product.product_id">
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
        categories: [],
        selectedCategoryId: ""
      }
    },
    computed: {
      filteredProducts () {
        if (this.selectedCategoryId === "") {
          return this.products
        } else {
          return this.products.filter((product) => {
            return product.id_category === this.selectedCategoryId
          })
        }
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
      const allCategories = await categoriesResult.json()
      this.categories = allCategories.filter((category) => category.active === "1")
    }
  }).mount('#app')
</script>

<?php require "view/blocks/page_end.php"; ?>