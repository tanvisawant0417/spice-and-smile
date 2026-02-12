/* --- 1. PROFILE DROPDOWN FUNCTIONALITY --- */
document.addEventListener('DOMContentLoaded', function() {
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
});

/* --- 2. UPDATE CART COUNT FROM LOCALSTORAGE --- */
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('spiceCart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    
    if (cartCountElement) {
        cartCountElement.innerText = totalItems;
    }
}

/* --- 3. TOGGLE CONTENT LOGIC --- */
function toggleContent(id, btn) {
    var content = document.getElementById(id);
    
    // Toggle the 'is-open' class (handles max-height and opacity in CSS)
    content.classList.toggle("is-open");

    // Change the button text based on the state
    if (content.classList.contains("is-open")) {
        btn.innerHTML = "Read Less";
    } else {
        btn.innerHTML = "Read More";
    }
}

// --- 3. INITIALIZE ON PAGE LOAD --- 
document.addEventListener('DOMContentLoaded', updateCartCount);


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
