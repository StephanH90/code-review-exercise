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
        <th
          v-for="column in ['name', 'category', 'price', 'stock']"
          :key="column"
          @click="sortBy === column ? sortBy = `-${column}` : sortBy = column"
        >
            {{column}} 
            <span v-if="sortBy === column">↓</span>
            <span v-else-if="sortBy === `-${column}`">↑</span>
            <span v-else>↕</span>
        </th>
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
        selectedCategoryId: "",
        sortBy: ''
      }
    },
    computed: {
      filteredProducts () {
        let products

        // Filter products by category
        if (this.selectedCategoryId === "") {
          products = this.products
        } else {
          products = this.products.filter((product) => product.id_category === this.selectedCategoryId)
        }

        // Sort products
        if (this.sortBy !== '') {
          return products.sort((a, b) => {
            if (this.sortBy[0] === "-") {
              const sortAttr = this.sortBy.slice(1) // remove the leading '-', ex: '-name' => 'name'

              if (isNaN(a[sortAttr])) {
                // we are comparing a string
                return a[sortAttr].localeCompare(b[sortAttr]);
              } else {
                return a[sortAttr] - b[sortAttr];
              }
            } else {
              if (isNaN(a[this.sortBy])) {
                // we are comparing a string
                return b[this.sortBy].localeCompare(a[this.sortBy]);
              } else {
                return b[this.sortBy] - a[this.sortBy];
              }
            }
          })
        }
        
        return products
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