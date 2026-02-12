// 1. Global variable to keep track of discount
let discountPercent = 0;

/**
 * Main function to render the cart items and calculate totals
 */
function displayCart() {
    const cart = JSON.parse(localStorage.getItem('spiceCart')) || [];
    const listContainer = document.getElementById('cart-items-list');
    const subtotalDisplay = document.getElementById('subtotal-val');
    const totalDisplay = document.getElementById('total-val');
    const countDisplay = document.getElementById('cart-count'); // Navbar count
    
    if (!listContainer) return;

    // Update Navbar count
    if (countDisplay) {
        countDisplay.innerText = cart.reduce((sum, item) => sum + item.quantity, 0);
    }

    // Handle Empty Cart View
    if (cart.length === 0) {
        listContainer.innerHTML = `
            <div class='cart-card' style='justify-content: center; text-align: center; padding: 40px;'>
                <div>
                    <h3>Your basket is empty! 🥣</h3>
                    <p style='color: #888; margin-top: 10px;'>Add some spice to your day!</p>
                </div>
            </div>`;
        if (subtotalDisplay) subtotalDisplay.innerText = "₹0";
        if (totalDisplay) totalDisplay.innerText = "₹0";
        return;
    }

    // Render Items
    let subtotal = 0;
    listContainer.innerHTML = cart.map((item, index) => {
        subtotal += (item.price * item.quantity);
        return `
            <div class="cart-card">
                <img src="${item.image}" alt="${item.name}">
                <div class="cart-info">
                    <h3>${item.name}</h3>
                    <p class="price">₹${item.price} x ${item.quantity} = ₹${item.price * item.quantity}</p>
                </div>
                <button class="remove-btn" onclick="removeItem(${index})">🗑️ Remove</button>
            </div>
        `;
    }).join('');

    // --- MATH LOGIC ---
    let deliveryFee = 20;
    let discountAmount = (subtotal * discountPercent) / 100;
    let finalTotal = (subtotal - discountAmount) + deliveryFee;

    if (subtotalDisplay) subtotalDisplay.innerText = "₹" + subtotal;
    if (totalDisplay) totalDisplay.innerText = "₹" + Math.round(finalTotal);
}

/**
 * Coupon Logic
 */
function applyCoupon() {
    const codeInput = document.getElementById('couponInput');
    const code = codeInput.value.trim().toUpperCase();
    const msg = document.getElementById('couponMessage');
    
    const codes = {
        'SPICE20': 20,
        'WELCOME10': 10,
        'FLAVOR50': 50
    };

    if (codes[code]) {
        discountPercent = codes[code];
        msg.innerText = `✅ Success! ${discountPercent}% off applied.`;
        msg.style.color = "#28a745"; 
    } else {
        discountPercent = 0;
        msg.innerText = "❌ Invalid coupon code.";
        msg.style.color = "#dc3545"; 
    }
    
    displayCart(); 
}

/**
 * Remove specific item from cart
 */
function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('spiceCart')) || [];
    cart.splice(index, 1); 
    localStorage.setItem('spiceCart', JSON.stringify(cart));
    displayCart(); 
}

/**
 * --- CRITICAL UPDATE: Navigation & Saving Total ---
 */
function proceedToPayment() {
    const cart = JSON.parse(localStorage.getItem('spiceCart')) || [];

    // 1. Check if cart is empty
    if (cart.length === 0) {
        const modal = document.getElementById('cartModal');
        if(modal) {
            modal.style.display = 'flex'; 
        } else {
            alert("Your basket is empty! 🥣"); 
        }
        return; 
    }

    // 2. GET THE FINAL TOTAL from the screen
    const totalDisplay = document.getElementById('total-val');
    if (totalDisplay) {
        // Remove the '₹' symbol and save only the number
        const finalAmountValue = totalDisplay.innerText.replace('₹', '');
        localStorage.setItem('cartTotal', finalAmountValue);
    }

    // 3. Go to Checkout page
    window.location.href = "checkout.php";
}

/**
 * Close Modal function
 */
function closeModal() {
    const modal = document.getElementById('cartModal');
    if(modal) {
        modal.style.display = 'none';
    }
    window.location.href = "menu.php";
}

// Event Listeners
document.addEventListener('DOMContentLoaded', displayCart);
window.addEventListener('storage', displayCart);


window.addEventListener("load", function() {
  const splash = document.getElementById('splash');

  // After 2 seconds, fade out splash
  setTimeout(() => {
    splash.style.opacity = 0; // fade out

    // After fade transition, remove splash from DOM
    setTimeout(() => {
      splash.style.display = 'none';
    }, 500); // match CSS fade duration
  }, 1000); // 1 seconds delay
});
