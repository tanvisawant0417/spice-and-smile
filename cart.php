<?php
session_start();
require_once "db.php"; // includes $conn
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Basket - Spice & Smile</title>
    <link rel="stylesheet" href="menu.css"> 
    <link rel="stylesheet" href="cart.css">
    <!-- Font Awesome for profile icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>



    <header class="navbar-pill" style="display:flex; justify-content:space-between; align-items:center; padding:15px 5%; background:black; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
        <div class="logo-group">
            <img src="images/logo.png" class="chili-img" alt="Logo" style="height:40px; width:auto;">
        </div>
        <nav class="nav-links" style="display:flex; gap:0; align-items:center;">
            <a href="index.php" style="text-decoration:none; margin-left:20px; font-weight:600; color:white;">🏠 Home</a>
            <a href="menu.php" style="text-decoration:none; margin-left:20px; font-weight:600; color:white;">📜 Menu</a>
            <a href="aboutus.php" style="text-decoration:none; margin-left:20px; font-weight:600; color:white;">ℹ️ About Us</a>
            <a href="cart.php" class="cart" style="text-decoration:none; margin-left:20px; font-weight:600; color:white;">
                Cart (<span id="cart-count">0</span>) 🛒
            </a>
        </nav>
        
    </header>

    <main class="cart-wrapper">
        <h2 class="category-title">Your Tasty Picks</h2>
        
        <div class="cart-flex-container">
            <div id="cart-items-list" class="cart-items-section">
                </div>

            <aside class="order-summary-card">
                <h3>Order Summary</h3>
                
                <div class="summary-line">
                    <span>Subtotal</span>
                    <span id="subtotal-val">₹0</span>
                </div>
                
                <div class="summary-line">
                    <span>Delivery Fee</span>
                    <span>₹20</span>
                </div>

                <div class="summary-coupon-box">
                    <input type="text" id="couponInput" placeholder="Enter Coupon Code">
                    <button id="applyCouponBtn" onclick="applyCoupon()">Apply</button>
                </div>
                <p id="couponMessage"></p>
                
                <hr class="summary-divider">
                
                <div class="summary-line total-line">
                    <span>Total Amount</span>
                    <span id="total-val">₹0</span>
                </div>
                
                <button class="checkout-btn" onclick="proceedToPayment()">Proceed to Pay</button>
            </aside>
        </div>
    </main>

    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>Contact Us</h3>
                <p>123 Spice Lane, Flavor Town</p>
                <p>Phone: +91 8104058728</p>
                <p><a href="mailto:tanvisawant0417@gmail.com" class="white-email">SpiceandSmile@gmail.com</a></p>
            </div>

            <div class="footer-column">
                <h3>Available In</h3>
                <ul>
                    <li><a href="#">Dadar</a></li>
                    <li><a href="#">Andheri</a></li>
                    <li><a href="#">Bandra</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>Social Links</h3>
                <div class="social-icons">
                    <a href="https://wa.me/918104058728" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="social-img" alt="WA">
                    </a>
                    <a href="https://www.instagram.com/_.tanvi_.0427" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e7/Instagram_logo_2016.svg" class="social-img" alt="IG">
                    </a>
                </div>
                <p class="copyright">© 2026 Spice & Smile Limited</p>
            </div>
        </div>
    </footer>

    <div id="cartModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-icon">🥣</div>
            <h2>Oops! Your bowl is empty.</h2>
<p>Your basket is feeling a bit light. Add some spice and a smile with Adarsh & Tanvi Spice and Smile before you go!</p>
            <button class="close-modal-btn" onclick="closeModal()">Back to Menu</button>
        </div>
    </div>

<script src="home.js" defer></script>
<script src="cart.js?v=99" defer></script>

</body>
</html>