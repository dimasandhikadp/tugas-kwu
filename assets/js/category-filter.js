const categoryButtons = document.querySelectorAll(".category-btn");
      const products = document.querySelectorAll(".product-card");

      function filterCategory(selectedCategory) {
        categoryButtons.forEach((btn) => {
          if (btn.dataset.category === selectedCategory) {
            btn.classList.remove("border-gray-100");
            btn.classList.add("border-blue-500", "shadow-lg");
          } else {
            btn.classList.remove("border-blue-500", "shadow-lg");
            btn.classList.add("border-gray-100");
          }
        });

        products.forEach((product) => {
          if (product.dataset.category === selectedCategory) {
            product.classList.remove("hidden");
          } else {
            product.classList.add("hidden");
          }
        });
      }

      categoryButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const selectedCategory = button.dataset.category;
          filterCategory(selectedCategory);
          
          const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?c=' + selectedCategory;
          window.history.pushState({path:newUrl}, '', newUrl);
        });
      });

      window.addEventListener("DOMContentLoaded", () => {
        const urlParams = new URLSearchParams(window.location.search);
        const categoryParam = urlParams.get('c');

        if (categoryParam) {
          filterCategory(categoryParam);
        } else {
          filterCategory("ikan"); 
        }
      });