document.getElementById("searchForm").addEventListener("submit", function (event) {
    event.preventDefault();
  
    const query = document.getElementById("searchInput").value;
  
    fetch(`search.php?q=${query}`)
      .then(response => response.json())
      .then(results => displayResults(results))
      .catch(error => console.error("Error:", error));
  });
  
  function displayResults(results) {
    const resultsDiv = document.getElementById("searchResults");
    resultsDiv.innerHTML = ""; // Clear previous results
  
    if (results.length === 0) {
      resultsDiv.innerHTML = "<p>No products found</p>";
      return;
    }
  
    results.forEach(product => {
      const productDiv = document.createElement("div");
      productDiv.innerHTML = `
        <h3>${product.name}</h3>
        <p>Price: ₹${product.price}</p>
        <p>${product.details}</p>
         <p>${product.image}</p>
      `;
      resultsDiv.appendChild(productDiv);
    });
  }
  