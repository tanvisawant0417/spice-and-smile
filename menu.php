<?php
session_start();
require_once "db.php"; // includes $conn
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spice & Smile</title>
    <link rel="stylesheet" href="menu.css">
	 <!-- Font Awesome for profile icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>


   <header class="navbar-pill">
    <div class="logo-group">
        <img src="images/logo.png" class="chili-img" alt="Logo">
    </div>

    <div class="location-search">
        <span class="loc-icon">📍</span>
        <select id="citySelect" class="loc-select" onchange="checkOther(this.value)">
            <option value="">Select City</option>
            <option value="Dadar">Dadar</option>
            <option value="Ghatkoper">Ghatkoper</option>
            <option value="Bandra">Bandra</option>
   
        </select>
        
    </div>

    <nav class="nav-links">
    <a href="index.php">🏠 Home</a>
    <a href="menu.php">📜 Menu</a>
    <a href="aboutus.php">ℹ️ About</a>
    <a href="cart.php" class="cart">🛒 Cart (<span id="cart-count">0</span>)</a>

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


</header> <!-- now header closes correctly -->

    
   
	
	<main class="menu-content">
    <h2 class="category-title">DABELI SPECIALS</h2>
    
    <div class="menu-row">
        <div class="menu-card">
            <img src="images/classicdabelli.png" class="food-thumb">
            <span class="veg-icon"></span><h3>Classic Dabeli</h3>
			<p class="desc">Grilled pav stuffed with classic Dabeli masala, tangy chutneys & crunchy peanuts.</p>
            <p class="price">₹80</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/cheesedabelli.png" class="food-thumb">
           <span class="veg-icon"></span> <h3>Cheese Dabeli</h3>
			<p class="desc">Grilled pav loaded with melted cheese, spiced potato filling & flavorful chutneys.</p>
            <p class="price">₹100</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/butterdabelli.png" class="food-thumb">
          <span class="veg-icon"></span>  <h3>Butter Dabeli</h3>
			<p class="desc">Buttery grilled pav filled with traditional Dabeli stuffing and house spices.</p>
            <p class="price">₹90</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>
    </div> <div class="menu-row">
        <div class="menu-card">
            <img src="images/butterchessedabelli.png" class="food-thumb">
          <span class="veg-icon"></span>  <h3>Butter Chesse Dabeli</h3>
			<p class="desc">Grilled pav layered with butter, cheese and signature Dabeli masala.</p>

            <p class="price">₹70</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/Specialdabelli.png" class="food-thumb">
           <span class="veg-icon"></span> <h3>Special Dabeli</h3>
			<p class="desc">Our special grilled Dabeli with extra filling, bold flavors & crunchy toppings.</p>

            <p class="price">₹110</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/jaindabelli.png" class="food-thumb">
        <span class="veg-icon"></span>    <h3>Jain Dabeli</h3>
			<p class="desc">Jain-style grilled Dabeli prepared without onion, full of authentic taste.</p>
            <p class="price">₹95</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>
    </div> <h2 class="category-title">FRESH JUICES</h2>

    <div class="menu-row">
        <div class="menu-card">
            <img src="images/pineapplejuice.png" class="food-thumb">
         <span class="veg-icon"></span>   <h3>Pineapple Juice</h3>
			<p class="desc">Freshly extracted pineapple juice served chilled and refreshing.</p>
            <p class="price">₹60</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/mangojuice.png" class="food-thumb">
            <span class="veg-icon"></span><h3>Mango Juice</h3>
			<p class="desc">Sweet and refreshing mango juice made from ripe, juicy mangoes.</p>
            <p class="price">₹80</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/mixfruitjuice.png" class="food-thumb">
          <span class="veg-icon"></span>  <h3>Mix Fruit Juice</h3>
			<p class="desc">A refreshing blend of seasonal fruits, freshly prepared and chilled.</p>
            <p class="price">₹90</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>
    </div> <div class="menu-row">
        <div class="menu-card">
            <img src="images/grapesjuice.png" class="food-thumb">
           <span class="veg-icon"></span> <h3>Grapes Juice</h3>
			<p class="desc">Naturally sweet grapes juice served fresh and chilled.</p>
            <p class="price">₹70</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/applejuice.png" class="food-thumb">
           <span class="veg-icon"></span> <h3>Apple Juice</h3>
			<p class="desc">Fresh apple juice with a perfect balance of sweetness and freshness.</p>
            <p class="price">₹80</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>

        <div class="menu-card">
            <img src="images/gauvajuice.png" class="food-thumb">
           <span class="veg-icon"></span> <h3>Gauva Juice</h3>
			<p class="desc">Refreshing guava juice with natural flavor and pulp.</p>
            <p class="price">₹100</p>
            <div class="qty-controller" style="display: none;">
                <button class="qty-btn minus">-</button>
                <span class="qty-number">1</span>
                <button class="qty-btn plus">+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>
    </div> </main>

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




	 <script src="menu.js" defer></script>
<script src="home.js" defer></script>


</body>

</html>
