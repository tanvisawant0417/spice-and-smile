<?php
session_start();
require_once "db.php"; // includes $conn
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spice & Smile | Authentic Indian Street Food</title>
    <link rel="stylesheet" href="HOMEPAGE.css">
	 <!-- Font Awesome for profile icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="home.js" defer></script>
</head>
<body class="home">


<div id="splash">
  <div id="loader"></div>
  <img id="logo" src="images/reload.png" alt="Logo">
</div>

    <div id="coupon-overlay" class="coupon-overlay">
        <div class="coupon-card">
            <button class="close-popup" onclick="closePopup()">&times;</button>
            <div class="coupon-content">
                <h3>WELCOME TO SPICE & SMILE!</h3>
                <h1>Enjoy 20% off <br> your first order.</h1>
                <div class="coupon-input-group">
                    <input type="email" placeholder="Enter Email Address" id="subscriber-email">
                    <button type="button" class="unlock-btn" onclick="applyDiscount()">UNLOCK CODE</button>
                </div>
            </div>
        </div>
    </div>


    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <img src="images/logo.png" alt="Spice & Smile Logo">
            </div>
			
			
            <nav class="nav-menu">
                <ul>
                    <li><a href="index.php">🏠 Home</a></li>
                    <li><a href="menu.php">📜 Menu</a></li>
                    <li><a href="aboutus.php">ℹ️ About Us</a></li>
                    <li>
                        <a href="cart.php">🛒 Cart (<span id="cart-count">0</span>)</a>
                    </li> 
                </ul>
           </nav>
            
<div class="auth-buttons" style="display:flex; align-items:center; gap:10px; position:relative;">

<?php if (isset($_SESSION['phone'])): ?>
    <!-- User is logged in: profile with hover and responsive -->
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
    <a href="signup.php?redirect=<?php echo urlencode(basename($_SERVER['PHP_SELF'])); ?>" 
       class="login-btn" 
       style="text-decoration:none; color:white; border:1px solid #f48225; padding:8px 25px; border-radius:5px; transition: all 0.3s ease; font-size:14px; white-space:nowrap; flex-shrink:0;"
       onmouseover="this.style.backgroundColor='#f48225'; this.style.color='white';"
       onmouseout="this.style.backgroundColor='transparent'; this.style.color='white';">Log in</a>
<?php endif; ?>

</div>

    </header>


    <div class="banner-container">
        <img src="images/banner.png" alt="Fresh Dabeli" class="banner-img">
        <div class="banner-overlay">
            <div class="banner-text">
                 <h1>
                Spice &amp; Smile —<br>
                Indian Street Food You’ll Love
            </h1>
           
                <div class="banner-btns">
                    <a href="menu.php" class="btn-red">Explore Menu</a>
                
				<a href="signup.php?redirect=<?php echo urlencode(basename($_SERVER['PHP_SELF'])); ?>" class="btn-orange">Order Now</a>
                </div>
            </div>
        </div>
    </div>

    <section class="popular-section">
	
        <h2 class="section-title">POPULAR ITEMS</h2>
        <div class="items-grid">
            
            <div class="food-card">
                <img src="images/classicdabelli.png" alt="Classic Dabeli">
                <span class="veg-icon"></span> <h3>Classic Dabeli</h3>
				<p class="desc">Grilled pav stuffed with classic Dabeli masala, tangy chutneys & crunchy peanuts.</p>

<p class="price">₹50</p>
                <div class="ctrl-container" id="ctrl-Dabeli">
                    </div>
            </div>

            <div class="food-card">
                <img src="images/cheesedabelli.png" alt="Cheese Dabeli">
               <span class="veg-icon"></span>  <h3>Cheese Dabeli</h3>
				<p class="desc">Grilled pav loaded with melted cheese, spiced potato filling & flavorful chutneys.</p>

                <p class="price">₹70</p>

                <div class="ctrl-container" id="ctrl-Cheese"></div>
            </div>

            <div class="food-card">
                <img src="images/mangojuice.png" alt="Mango Juice">
                <span class="veg-icon"></span> <h3>Mango Juice</h3>
				<p class="desc">Sweet and refreshing mango juice made from ripe, juicy mangoes.</p>
               <p class="price">₹60</p>

                <div class="ctrl-container" id="ctrl-Mango"></div>
            </div>

            <div class="food-card">
                <img src="images/pineapplejuice.png" alt="Pineapple Juice">
                <span class="veg-icon"></span> <h3>Pineapple Juice</h3>
				<p class="desc">Freshly extracted pineapple juice served chilled and refreshing.</p>
                <p class="price">₹60</p>

                <div class="ctrl-container" id="ctrl-Pineapple"></div>
            </div>

        </div>
    </section>

    <section class="about-section">
        <div class="about-container">
            <div class="about-image">
                <img src="images/aboutimage.png" alt="Our Restaurant Owners">
            </div>
            <div class="about-content">
                <h2>ABOUT</h2>
                <p>Adarsh & Tanvi's Spice & Smile is a family-owned venture bringing the authentic, zesty flavors of Indian street food to your neighborhood.</p>
                <a href="aboutus.php" class="read-more-btn">Read More</a>
            </div>
        </div>
    </section>
	
<!-- ================= AUTO REVIEWS ================= -->
<section class="reviews-section">
    <h2>Loved by Our Customers ❤️</h2>

    <div class="reviews-track">
        <div class="review-card">
            <div class="stars">★★★★★</div>
            <p>Best dabeli I’ve had! Perfect spice and freshness.</p>
            <h4>Riya Patil</h4>
        </div>

        <div class="review-card">
            <div class="stars">★★★★☆</div>
            <p>Juices are refreshing and hygiene is top-notch.</p>
            <h4>Aman Kulkarni</h4>
        </div>

        <div class="review-card">
            <div class="stars">★★★★★</div>
            <p>Affordable prices with amazing taste. Loved it!</p>
            <h4>Sneha Deshmukh</h4>
        </div>

        <!-- DUPLICATE FOR SMOOTH LOOP -->
        <div class="review-card">
            <div class="stars">★★★★★</div>
            <p>Best dabeli I’ve had! Perfect spice and freshness.</p>
            <h4>Riya Patil</h4>
        </div>

        <div class="review-card">
            <div class="stars">★★★★☆</div>
            <p>Juices are refreshing and hygiene is top-notch.</p>
            <h4>Aman Kulkarni</h4>
        </div>
    </div>
</section>


<!-- ================= FEEDBACK ================= -->
<section class="feedback-section">
    <h2>Rate Your Experience ⭐</h2>

    <form class="feedback-form" onsubmit="submitFeedback(event)">
        <input type="text" placeholder="Your Name" required>

        <div class="star-rating">
            <span onclick="rate(1)">★</span>
            <span onclick="rate(2)">★</span>
            <span onclick="rate(3)">★</span>
            <span onclick="rate(4)">★</span>
            <span onclick="rate(5)">★</span>
        </div>

        <textarea placeholder="Write your feedback..." required></textarea>
        <button type="submit">Submit Review</button>
    </form>

    <div class="thank-you" id="thankYou">
        🎉 Thanks for rating us!
    </div>
</section>



    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>Contact Us</h3>
                <p>123 Spice Lane, Flavor Town</p>
                <p>Phone: +91 8104058728</p>
                <p><a href="mailto:SpiceandSmile@gmail.com" class="white-email">SpiceandSmile@gmail.com</a></p>
            </div>
            <div class="footer-column">
                <h3>Available In</h3>
                <ul>
                    <li><a href="#">Dadar</a></li>
                    <li><a href="#">Andheri</a></li>
                    <li><a href="#">Bandra</a></li>
                    <li><a href="#">Ghatkopar</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Social Links</h3>
                <div class="social-icons">
                    <a href="https://wa.me/918104058728" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="social-img" alt="WhatsApp">
                    </a>
                    <a href="https://www.instagram.com/_.tanvi_.0427" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e7/Instagram_logo_2016.svg" class="social-img" alt="Instagram">
                    </a>
                </div>
                <p class="copyright">© 2026 Spice & Smile Limited</p>
            </div>
        </div>
    </footer>



</body>
</html>