<?php $page_title = "Home ★ Productive"; ?>
<?php require "view/blocks/page_start.php"; ?>
<h1>Welcome to Productive!</h1>

<h2>Our most popular products</h2>

<!-- Plain JS implementation -->
<div style="margin-bottom: 1em;">
  <label for="category-filter">Nach Kategorie filtern: </label>
  <select id="category-filter" name="category-filter">
    <option value="">Kein Filter</option>
  </select>
</div>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Price</th>
      <th>Stock</th>
    </tr>
  </thead>
  <tbody id="products-table">
  </tbody>
</table>

<script>
const updateProductTable = async function (table, productsToInsert) {
  const columns = ['name', 'price', 'stock'];

  // Insert the table rows for the found products
  const newTableRows = productsToInsert.map((product) => {
    let thing = document.createElement('tr');

    const tds = [];

    for (let i = 0; i < columns.length; i++) {
      let column = columns[i];

      let td = document.createElement('td');
      if(column == 'stock') {
        if (product[column] <= 3) {
          td.style.color = 'red'
        } else {
          td.style.color = 'black';
        }
      }
      td.innerText = product[column]; // dunno lol
      thing.appendChild(td);
    }

    return thing
  })

  table.replaceChildren(...newTableRows)
}

const init = async function () { // an async wrapper function which allows me to use await instead of .then()
  const result1 = await fetch('API/V1/popular-products')
  const products = await result1.json()

  console.log(products)

  updateProductTable(document.querySelector('#products-table'), products);
}

init();
</script>

<?php require "view/blocks/page_end.php"; ?>