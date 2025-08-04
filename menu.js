// ======= Modal Open/Close =======
const cartModal = document.getElementById("cart-modal");
const cartButton = document.getElementById("floating-cart");
const closeModal = document.querySelector(".close");

cartButton.addEventListener("click", () => {
  cartModal.style.display = "block";
});

closeModal.addEventListener("click", () => {
  cartModal.style.display = "none";
});

window.addEventListener("click", (e) => {
  if (e.target === cartModal) {
    cartModal.style.display = "none";
  }
});

// ======= Cart State =======
let cart = [];

function updateCartDisplay() {
  const cartItemsContainer = document.getElementById("cart-items");
  const cartTotal = document.querySelector("#cart-total span");
  const cartCount = document.getElementById("cart-count");

  cartItemsContainer.innerHTML = "";

  let total = 0;
  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    total += itemTotal;

    const cartItem = document.createElement("div");
    cartItem.classList.add("cart-item");

    cartItem.innerHTML = `
      <div class="cart-item-info">
        <strong>${item.name}</strong>
        <div class="cart-item-price">RM ${item.price.toFixed(2)}</div>
      </div>
      <div class="cart-item-controls">
        <button class="quantity-btn minus" data-index="${index}">-</button>
        <div class="quantity">${item.quantity}</div>
        <button class="quantity-btn plus" data-index="${index}">+</button>
        <div class="item-total">RM ${(item.price * item.quantity).toFixed(2)}</div>
        <button class="remove-item" data-index="${index}">&times;</button>
      </div>
    `;

    cartItemsContainer.appendChild(cartItem);
  });

  cartTotal.textContent = total.toFixed(2);
  cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);

  addQuantityListeners();
}

// ======= Add to Cart =======
document.querySelectorAll(".add-to-cart").forEach((btn) => {
  btn.addEventListener("click", () => {
    const card = btn.closest(".menu-card");
    const name = card.querySelector("h3").textContent;
    const price = parseFloat(card.querySelector("p").textContent.replace("RM", "").trim());

    const existing = cart.find((item) => item.name === name);
    if (existing) {
      existing.quantity++;
    } else {
      cart.push({ name, price, quantity: 1 });
    }

    updateCartDisplay();
  });
});

// ======= Quantity Change =======
function addQuantityListeners() {
  document.querySelectorAll(".quantity-btn.plus").forEach((btn) =>
    btn.addEventListener("click", () => {
      const index = btn.dataset.index;
      cart[index].quantity++;
      updateCartDisplay();
    })
  );

  document.querySelectorAll(".quantity-btn.minus").forEach((btn) =>
    btn.addEventListener("click", () => {
      const index = btn.dataset.index;
      if (cart[index].quantity > 1) {
        cart[index].quantity--;
      } else {
        cart.splice(index, 1);
      }
      updateCartDisplay();
    })
  );

  document.querySelectorAll(".remove-item").forEach((btn) =>
    btn.addEventListener("click", () => {
      const index = btn.dataset.index;
      cart.splice(index, 1);
      updateCartDisplay();
    })
  );
}

// ======= Clear All Button =======
document.getElementById("clear-all-btn").addEventListener("click", () => {
  cart = [];
  updateCartDisplay();
});

// ======= Submit Order Button (POST to admin_order.php) =======
document.getElementById("submit-order-btn").addEventListener("click", () => {
  const tableInput = document.getElementById("table-number");
  const error = document.getElementById("table-error");

  const tableNumber = parseInt(tableInput.value);

  if (!tableNumber || tableNumber < 1) {
    error.textContent = "Please enter a valid table number.";
    error.style.display = "block";
    return;
  }

  if (cart.length === 0) {
    error.textContent = "Your cart is empty.";
    error.style.display = "block";
    return;
  }

  error.style.display = "none";

  const payload = {
    table: tableNumber,
    items: cart
  };

  fetch("admin_order.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(payload)
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        alert("Order submitted successfully!");
        cart = [];
        updateCartDisplay();
        tableInput.value = "";
        cartModal.style.display = "none";
      } else {
        alert("Error: " + (data.error || "Failed to submit order."));
      }
    })
    .catch((err) => {
      console.error("Error submitting order:", err);
      alert("Error connecting to server.");
    });
});

// ======= Sidebar Category Filter =======
document.querySelectorAll(".menu-sidebar li").forEach((sidebarItem) => {
  sidebarItem.addEventListener("click", () => {
    const selectedCategory = sidebarItem.dataset.category;

    // Set active class
    document.querySelectorAll(".menu-sidebar li").forEach((li) =>
      li.classList.remove("active")
    );
    sidebarItem.classList.add("active");

    // Filter only matching category
    document.querySelectorAll(".menu-card").forEach((card) => {
      const cardCategory = card.dataset.category;
      card.style.display = (cardCategory === selectedCategory) ? "flex" : "none";
    });
  });
});

window.addEventListener("DOMContentLoaded", () => {
  // Activate only Chicken in sidebar
  document.querySelectorAll(".menu-sidebar li").forEach((li) => {
    li.classList.remove("active");
    if (li.dataset.category === "chicken") {
      li.classList.add("active");
    }
  });

  // Hide non-chicken menu cards
  document.querySelectorAll(".menu-card").forEach((card) => {
    const category = card.dataset.category;
    card.style.display = (category === "chicken") ? "flex" : "none";
  });
});

