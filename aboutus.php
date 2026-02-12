<?php
session_start();
require_once "db.php"; // includes $conn
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Spice & Smile</title>
    <link rel="stylesheet" href="aboutus.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
   
</head>
<body>


    <header class="main-nav">
        <div class="nav-pill">
            <div class="logo-section">
                <img src="images/logo.png" alt="Logo" class="nav-logo">
            </div>
            <nav class="nav-links">
                <a href="index.php">🏠 Home</a>
                <a href="menu.php">📜 Menu</a>
                <a href="aboutus.php" class="active">ℹ️ About Us</a>
                <a href="cart.php">🛒 Cart (<span id="cart-count">0</span>)</a>

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

            </nav>
    </header>

    <section class="hero-html">
        <img src="images/aboutus.png" alt="Spice Banner" class="hero-img">
        <div class="hero-overlay">
            <h1>Our Story, Our Flavors</h1>
        </div>
    </section>

    <main class="container">
        <section class="info-grid">
            <div class="card">
                <img src="images/aboutusmango.png" alt="Passion" class="card-img">
                <div class="card-text">
                    <h2>Passion for Flavor</h2>
                    <p>At Spice & Smile, flavor is our identity.We use authentic, hand-picked spices
						and.....</p>
                    <div id="more1" class="extra-content">
                        <p>  traditional recipes to create food that feels homemade and tastes unforgettable. Every ingredient is chosen with care, every dish prepared with
						passion — so each bite brings comfort, warmth, and a smile.</p>
                    </div>
                    <button class="btn-blue" onclick="toggleContent('more1', this)">Read More</button>
                </div>
            </div>

            <div class="card">
			
                <div class="card-text">
                    <h2>Quality Ingredients</h2>
                    <p>We use the finest handpicked ingredients for a fresh experience.</p>
                    <div id="more2" class="extra-content">
                        <p>At our kitchen, quality comes first. We carefully select fresh, handpicked ingredients every day to ensure great taste, hygiene, and consistency. From vegetables to spices, everything is chosen with care so every bite feels fresh, flavorful, and satisfying.

We never compromise on freshness because good food starts with good ingredients.</p>
                    </div>
                    <button class="btn-blue" onclick="toggleContent('more2', this)">Read More</button>
                </div>
                <img src="images/aboutusimage.png" alt="Ingredients" class="card-img">
            </div>
        </section>

        <section class="team-section">
            <h2 class="section-title">Meet the Team</h2>
            <div class="team-flex">
                <div class="member">
                    <img src="images/aboutimage.png" alt="Adarsh">
                    <h3>Tanvi & Adarsh</h3>
                    <p>(Founders)</p>
                </div>
                <div class="member">
                    <img src="images/chief.png" alt="Team">
                    <h3>Team Member</h3>
                    <p>(Chief)</p>
                </div>
                <div class="member">
                    <img src="images/sponser.png" alt="Team">
                    <h3>VSIT</h3>
                    <p>(Investor)</p>
                </div>
            </div>
        </section>
    </main>

   
<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-column">
            <h3>Contact Us</h3>
            <p>123 Spice Lane, Flavor Town</p>
            <p>Phone: +91 8104058728</p>
           <p> <a href="mailto:tanvisawant0417@gmail.com" target="_blank" class="white-email">
        SpiceandSmile@gmail.com
    </a>
	</p>
        </div>

        <div class="footer-column">
            <h3>Available In:</h3>
            <ul>
                <li><a href="#">Dadar</a></li>
                <li><a href="#">Andheri</a></li>
                <li><a href="#">Ghatkoper</a></li>
                <li><a href="#">Bandra</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h3>Legal</h3>
            <ul>
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Cookie Policy</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        <div class="footer-col">
    <h3>SOCIAL LINKS</h3> <div class="social-icons">
        <a href="https://wa.me/918104058728" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="social-img">
        </a>
        <a href="https://www.instagram.com/_.tanvi_.0427" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/e/e7/Instagram_logo_2016.svg" alt="Instagram" class="social-img">
        </a>
    </div>
            <p class="copyright">© 2026 Spice & Smile Limited</p>
        </div>
    </div>
</footer>

    <script src="home.js" defer></script>
<script>
    // Toggle content for "Read More/Less" buttons
    function toggleContent(id, btn) {
        var content = document.getElementById(id);
        content.classList.toggle("is-open");
        if (content.classList.contains("is-open")) {
            btn.innerHTML = "Read Less";
        } else {
            btn.innerHTML = "Read More";
        }
    }
</script>

</body>
<script src="home.js" defer></script>   <!-- For cart count and profile dropdown behavior -->
<script src="aboutus.js" defer></script> <!-- Handles Read More & splash --
</html>
