<?php
session_start();
require_once "db.php"; // includes $conn
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Spice & Smile</title>
    <link rel="stylesheet" href="menu.css"> 
    <link rel="stylesheet" href="checkout.css">
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
            <a href="cart.php" style="text-decoration:none; margin-left:20px; font-weight:600; color:white;">🛒 Back to Cart</a>
        </nav>
        <div class="auth-buttons" style="display:flex; align-items:center; gap:10px; position:relative; margin-left:auto;">
            <?php if (isset($_SESSION['phone'])): ?>
                <!-- User is logged in: profile dropdown -->
                <div class="profile-container" id="profileBtn"
                     style="display:flex; align-items:center; gap:8px; cursor:pointer; padding:5px 10px; border-radius:8px; transition: all 0.3s ease; position:relative; flex-shrink:0;"
                     onmouseover="this.querySelector('.profile-dropdown').style.display='flex';"
                     onmouseout="this.querySelector('.profile-dropdown').style.display='none';">
                     
                    <!-- Circle icon -->
                    <div class="profile-circle" 
                         style="width:40px; height:40px; border-radius:50%; background-color:#f48225; display:flex; align-items:center; justify-content:center; border:none; transition: all 0.3s ease; flex-shrink:0;">
                        <i class="fa-solid fa-user" style="color:white; font-size:20px;"></i>
                    </div>
                    
                    <!-- Profile text -->
                    <span class="profile-text" style="color:#FFFFFF; font-weight:500; transition:color 0.3s ease; font-size:14px; white-space:nowrap;">Profile</span>
                    
                    <!-- Profile dropdown menu -->
                    <ul class="profile-dropdown" id="dropdownMenu" 
                        style="display:none; position:absolute; top:50px; right:0; background:white; border:2px solid #f48225; border-radius:12px; list-style:none; min-width:220px; box-shadow:0 12px 32px rgba(0,0,0,0.4); flex-direction:column; z-index:1000; padding:8px 0; margin:0;">
                        <li style="padding:0; border-bottom:1px solid #efefef;">
                            <a href="my_orders.php" style="display:flex; align-items:center; gap:12px; padding:14px 18px; color:#222; text-decoration:none; transition:all 0.2s ease; font-weight:500; font-size:14px;" onmouseover="this.style.backgroundColor='#fff3e0'; this.style.color='#f48225'; this.style.paddingLeft='24px';" onmouseout="this.style.backgroundColor='white'; this.style.color='#222'; this.style.paddingLeft='18px';">
                                <i class="fa-solid fa-box-open" style="color:#f48225; font-size:18px; width:22px; text-align:center; flex-shrink:0;"></i>
                                <span style="flex-grow:1;">My Orders</span>
                            </a>
                        </li>
                        <li style="padding:0; border-bottom:1px solid #efefef;">
                            <a href="rewards.php" style="display:flex; align-items:center; gap:12px; padding:14px 18px; color:#222; text-decoration:none; transition:all 0.2s ease; font-weight:500; font-size:14px;" onmouseover="this.style.backgroundColor='#fff3e0'; this.style.color='#f48225'; this.style.paddingLeft='24px';" onmouseout="this.style.backgroundColor='white'; this.style.color='#222'; this.style.paddingLeft='18px';">
                                <i class="fa-solid fa-gift" style="color:#f48225; font-size:18px; width:22px; text-align:center; flex-shrink:0;"></i>
                                <span style="flex-grow:1;">Rewards</span>
                            </a>
                        </li>
                        <li style="padding:0; height:8px; background-color:#f8f8f8; border:none;"></li>
                        <li style="padding:0;">
                            <a href="logout.php" style="display:flex; align-items:center; gap:12px; padding:14px 18px; color:#d32f2f; text-decoration:none; transition:all 0.2s ease; font-weight:500; font-size:14px;" onmouseover="this.style.backgroundColor='#ffebee'; this.style.color='#c62828'; this.style.paddingLeft='24px';" onmouseout="this.style.backgroundColor='white'; this.style.color='#d32f2f'; this.style.paddingLeft='18px';">
                                <i class="fa-solid fa-right-from-bracket" style="color:#d32f2f; font-size:18px; width:22px; text-align:center; flex-shrink:0;"></i>
                                <span style="flex-grow:1;">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <!-- User not logged in: login button -->
                <a href="signup.php?redirect=checkout" 
                   class="login-btn" 
                   style="text-decoration:none; color:white; border:1px solid #f48225; padding:8px 25px; border-radius:5px; transition: all 0.3s ease; font-size:14px; white-space:nowrap; flex-shrink:0;"
                   onmouseover="this.style.backgroundColor='#f48225'; this.style.color='white';"
                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='white';">Log in</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="checkout-wrapper">
        <div class="checkout-card">
            <h2>Shipping Details</h2>
            <p class="subtitle">Please provide your information for delivery updates.</p>

            <form id="orderForm">
                <div class="form-section">
                    <label>Full Name</label>
                    <input type="text" id="fullname" placeholder="John Doe" required>
                </div>

                <div class="form-section">
                    <label>Street Address</label>
                    <textarea id="street" rows="2" placeholder="House No, Street, Landmark" required></textarea>
                </div>

                <div class="row">
                    <div class="form-section">
                        <label>City, State</label>
                        <input type="text" id="city" placeholder="Mumbai, MH" required>
                    </div>
                    <div class="form-section">
                        <label>ZIP Code</label>
                        <input type="text" id="zip" placeholder="400001" required>
                    </div>
                </div>

                <div class="form-section">
                    <label>Phone Number</label>
                    <input type="tel" id="phone" placeholder="+91 81040XXXXX" required>
                </div>

                <div class="form-section">
                    <label>Payment Option</label>
                    <div class="payment-grid">
                        <div class="pay-option active" id="payOnline" onclick="selectPayment('Online')">
                            <span>💳 Online Pay</span>
                        </div>
                        <div class="pay-option" id="payCash" onclick="selectPayment('Cash')">
                            <span>💵 Cash on Delivery</span>
                        </div>
                    </div>
                </div>

                <div class="final-summary">
                    <span>Total Amount:</span>
                    <span id="final-amount">₹100</span>
                </div>

                <button type="submit" class="place-order-btn">Confirm Order</button>
            </form>
        </div>
    </main>

    <div id="orderSuccessModal" class="modal-overlay">
        <div class="modal-content">
           
            <h2>Order Confirmed! 🥳</h2>
            <p>Thank you for choosing Adarsh & Tanvi Spice and Smile.
Fresh flavors and happy smiles are being prepared just for you! 💛</p>
            <div class="modal-footer">
        <button class="track-btn" onclick="goToTracking()">Track Your Order</button>
    </div>
    </div>

    <!-- Scanner Modal -->
    <div id="scannerModal" class="scanner-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:10000; align-items:center; justify-content:center;">
        <div class="scanner-container" style="width:90%; max-width:450px; background:white; border-radius:20px; padding:30px; text-align:center; box-shadow:0 10px 50px rgba(0,0,0,0.5);">
            <h2 style="margin-bottom:10px; color:#333; font-size:24px;">💳 Scan to Pay</h2>
            <p style="color:#666; margin-bottom:20px; font-size:14px;">Scan this QR code with any UPI app</p>
            
            <div style="background:#f8f8f8; padding:20px; border-radius:15px; margin-bottom:20px;">
                <img src="images/scanner.jpg" alt="Payment QR Code" style="width:100%; max-width:300px; height:auto; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2);">
            </div>

            <p style="color:#f48225; font-weight:600; margin-bottom:20px; font-size:16px;">Amount: <span id="scanner-amount">₹100</span></p>

            <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                <button onclick="stopScanner()" style="padding:12px 30px; background:#666; color:white; border:none; border-radius:10px; cursor:pointer; font-weight:500; font-size:14px; transition:all 0.3s ease;" onmouseover="this.style.background='#555'" onmouseout="this.style.background='#666'">Cancel</button>
                <button onclick="confirmPayment()" style="padding:12px 30px; background:#4CAF50; color:white; border:none; border-radius:10px; cursor:pointer; font-weight:500; font-size:14px; transition:all 0.3s ease;" onmouseover="this.style.background='#45a049'" onmouseout="this.style.background='#4CAF50'">Payment Done ✓</button>
            </div>
        </div>
    </div>

   <script src="home.js" defer></script>
<script src="checkout.js" defer></script>

</body>
</html>