// 1. Function to handle payment selection UI
function selectPayment(type) {
    const online = document.getElementById('payOnline');
    const cash = document.getElementById('payCash');

    // Remove active from all
    online.classList.remove('active');
    cash.classList.remove('active');

    // Add active to selected
    if (type === 'Online') {
        online.classList.add('active');
        // Open scanner when Online Pay is selected
        setTimeout(() => openScanner(), 300);
    } else if (type === 'Cash') {
        cash.classList.add('active');
        stopScanner();
    }
}

// Scanner Functions
function openScanner() {
    const scannerModal = document.getElementById('scannerModal');
    const scannerAmount = document.getElementById('scanner-amount');
    const savedTotal = localStorage.getItem('cartTotal');
    
    if (!scannerModal) return;
    
    // Update amount in scanner
    if (scannerAmount && savedTotal) {
        scannerAmount.innerText = '₹' + savedTotal;
    }
    
    scannerModal.style.display = 'flex';
}

function stopScanner() {
    const scannerModal = document.getElementById('scannerModal');
    
    if (scannerModal) {
        scannerModal.style.display = 'none';
    }
}

function confirmPayment() {
    // Close scanner
    stopScanner();
    
    // Show success message
    alert('✅ Payment confirmed! Processing your order...');
    
    // Submit the order form
    document.getElementById('orderForm').dispatchEvent(new Event('submit'));
}

// 2. Handle Form Submission
document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent page refresh

    // Get form data
    const fullname = document.getElementById('fullname').value.trim();
    const street = document.getElementById('street').value.trim();
    const city = document.getElementById('city').value.trim();
    const zip = document.getElementById('zip').value.trim();
    const payOnline = document.getElementById('payOnline');
    const payment_method = payOnline.classList.contains('active') ? 'Online' : 'Cash on Delivery';
    
    // Get cart from localStorage
    const cartData = localStorage.getItem('spiceCart');
    const cartTotal = localStorage.getItem('cartTotal') || '0';
    let cartItems = [];
    
    try {
        cartItems = cartData ? JSON.parse(cartData) : [];
    } catch (e) {
        console.error('Error parsing cart:', e);
    }

    // Validate all fields
    if (!fullname || !street || !city || !zip) {
        alert('Please fill all delivery details');
        return;
    }

    if (cartItems.length === 0) {
        alert('Cart is empty');
        return;
    }

    // Prepare data using URLSearchParams
    const params = new URLSearchParams();
    params.append('fullname', fullname);
    params.append('street', street);
    params.append('city', city);
    params.append('zip', zip);
    params.append('payment_method', payment_method);
    params.append('total_amount', cartTotal);
    params.append('cart_items', JSON.stringify(cartItems));

    console.log('Sending order data:');
    console.log('- Full Name:', fullname);
    console.log('- Payment Method:', payment_method);
    console.log('- Total:', cartTotal);
    console.log('- Items:', cartItems);

    // Save order to database
    fetch('save_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params.toString()
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP Error: ${res.status}`);
        }
        return res.text(); // Get text first to debug
    })
    .then(text => {
        console.log('Raw response:', text);
        // Try to parse as JSON
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error('Invalid JSON response: ' + text);
        }
    })
    .then(data => {
        console.log('Save Order Response:', data);
        
        if (data.success) {
            // Scroll to top so the modal is perfectly visible on all screens
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Show the Success Modal
            const successModal = document.getElementById('orderSuccessModal');
            if (successModal) {
                successModal.style.display = 'flex';
            }

            // Clear the cart and the total so the user starts fresh next time
            localStorage.removeItem('spiceCart');
            localStorage.removeItem('cartTotal');
            
            // Store order ID for tracking
            if (data.order_id) {
                localStorage.setItem('lastOrderId', data.order_id);
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to save order'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
});

// 3. Button inside the Modal (Track Order)
function goToTracking() {
    // Generate a random Order ID (for future use or tracking pages)
    const orderID = "SS" + Math.floor(1000 + Math.random() * 9000);
    
    // For now, redirect to Home
    window.location.href = "index.php"; 
}

// 4. Initialize dynamic data on page load
window.onload = function() {
    // Look for the saved total we passed from cart.js
    const savedTotal = localStorage.getItem('cartTotal');
    const displayTotal = document.getElementById('final-amount');

    if (savedTotal && displayTotal) {
        displayTotal.innerText = "₹" + savedTotal;
    } else if (displayTotal) {
        // Fallback if the user went directly to checkout without the cart
        displayTotal.innerText = "₹0";
    }
};

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
