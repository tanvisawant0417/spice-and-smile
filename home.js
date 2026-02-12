// --- 1. INITIALIZATION & COUPON LOGIC ---

document.addEventListener('DOMContentLoaded', function() {
    // Profile dropdown functionality
    const profileBtn = document.getElementById('profileBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    if (profileBtn && dropdownMenu) {
        function positionDropdown() {
            const rect = profileBtn.getBoundingClientRect();
            dropdownMenu.style.top = (rect.bottom + 10) + 'px';
            dropdownMenu.style.left = (rect.right - 220) + 'px';
        }

        // Toggle dropdown on click
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isShowing = dropdownMenu.classList.toggle('show');
            if (isShowing) {
                positionDropdown();
            }
        });

        // Reposition dropdown on window resize/scroll
        window.addEventListener('scroll', function() {
            if (dropdownMenu.classList.contains('show')) {
                positionDropdown();
            }
        });

        window.addEventListener('resize', function() {
            if (dropdownMenu.classList.contains('show')) {
                positionDropdown();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        // Close dropdown when clicking on a link
        dropdownMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                dropdownMenu.classList.remove('show');
            });
        });
    }

    // 1. Update UI based on what's already in the cart
    updateCartCount();
    renderAllButtons();

    // 2. Handle Coupon Popup - Show only once per user
    const isReturningUser = localStorage.getItem('couponShown');
    
    if (!isReturningUser) {
        setTimeout(function() {
            const overlay = document.getElementById('coupon-overlay');
            if (overlay) {
                console.log('Showing coupon popup'); // Debug log
                overlay.style.display = 'flex';
                // Confetti trigger when popup appears
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: { y: 0.6 },
                        zIndex: 100001
                    });
                }
            } else {
                console.error('Coupon overlay not found!'); // Debug log
            }
        }, 2000); // Show after splash is completely gone (1000ms + 500ms fade + 500ms buffer)
    }
});

function applyDiscount() {
    const emailInput = document.getElementById('subscriber-email');
    const couponContent = document.querySelector('.coupon-content');

    if (emailInput && emailInput.value.trim() !== "" && emailInput.value.includes('@')) {
        localStorage.setItem('couponShown', 'true');
        localStorage.setItem('firstOrderDiscount', 'active');

        // Update popup content to show the code
        couponContent.innerHTML = `
            <h3 style="color:#f48225;">CONGRATULATIONS!</h3>
            <h1 style="margin: 15px 0;">CODE: <span style="background:#000; color:#fff; padding:5px 15px; border-radius:8px;">SPICE20</span></h1>
            <p style="color: #555; margin-bottom: 20px;">20% OFF has been applied to your future order!</p>
            <button class="unlock-btn" onclick="closePopup()" style="width:100%;">START ORDERING</button>
        `;

        // Extra celebratory confetti
        confetti({
            particleCount: 200,
            spread: 100,
            origin: { y: 0.6 },
            zIndex: 100001 // CHANGED: Must be higher than 99999
        });
    } else {
        alert("Please enter a valid email address to unlock your discount!");
    }
}

function closePopup() {
    localStorage.setItem('couponShown', 'true');
    const overlay = document.getElementById('coupon-overlay');
    if (overlay) overlay.style.display = 'none';
}

// --- 2. QUANTITY TOGGLE & CART LOGIC ---

function addToCart(name, price, image) {
    let cart = JSON.parse(localStorage.getItem('spiceCart')) || [];
    const existingItem = cart.find(item => item.name === name);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ name, price, image, quantity: 1 });
    }

    localStorage.setItem('spiceCart', JSON.stringify(cart));
    updateCartCount();
    renderAllButtons();
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('spiceCart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    
    if (cartCountElement) {
        cartCountElement.innerText = totalItems;
    }
}

function changeQty(name, delta) {
    let cart = JSON.parse(localStorage.getItem('spiceCart')) || [];
    const itemIndex = cart.findIndex(item => item.name === name);

    if (itemIndex !== -1) {
        cart[itemIndex].quantity += delta;

        if (cart[itemIndex].quantity <= 0) {
            cart.splice(itemIndex, 1);
        }
    }

    localStorage.setItem('spiceCart', JSON.stringify(cart));
    updateCartCount();
    renderAllButtons();
}

function renderAllButtons() {
    const cart = JSON.parse(localStorage.getItem('spiceCart')) || [];
    
    const items = [
        { id: 'ctrl-Dabeli', name: 'Classic Dabeli', price: 50, img: 'classicdabelli.png' },
        { id: 'ctrl-Cheese', name: 'Cheese Dabeli', price: 70, img: 'cheesedabelli.png' },
        { id: 'ctrl-Mango', name: 'Mango Juice', price: 60, img: 'mangojuice.png' },
        { id: 'ctrl-Pineapple', name: 'Pineapple Juice', price: 60, img: 'pineapplejuice.png' }
    ];

    items.forEach(item => {
        const container = document.getElementById(item.id);
        if (!container) {
            console.log("Could not find container:", item.id);
            return;
        }

        const cartItem = cart.find(c => c.name === item.name);

        if (cartItem) {
            container.innerHTML = `
                <div class="quantity-pill">
                    <button class="qty-btn" onclick="changeQty('${item.name}', -1)">−</button>
                    <span class="qty-number">${cartItem.quantity}</span>
                    <button class="qty-btn" onclick="changeQty('${item.name}', 1)">+</button>
                </div>
            `;
        } else {
            container.innerHTML = `
                <button class="add-to-cart" onclick="addToCart('${item.name}', ${item.price}, '${item.img}')">Add to Cart</button>
            `;
        }
    });
}

let selectedRating = 0;

function rate(stars) {
    selectedRating = stars;
    document.querySelectorAll(".star-rating span")
        .forEach((star, index) => {
            star.classList.toggle("active", index < stars);
        });
}

function submitFeedback(e) {
    e.preventDefault();

    if (selectedRating === 0) {
        alert("Please select a star rating ⭐");
        return;
    }

    document.getElementById("thankYou").style.display = "block";
    e.target.reset();
    selectedRating = 0;

    document.querySelectorAll(".star-rating span")
        .forEach(star => star.classList.remove("active"));
}

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



