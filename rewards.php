<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['phone'])) {
    header("Location: signup.php?redirect=rewards");
    exit;
}

include 'db.php';
$phone = $_SESSION['phone'];

// Get user's order history to calculate rewards
$orders_query = "SELECT * FROM orders WHERE phone='$phone' ORDER BY created_at DESC";
$orders_result = @mysqli_query($conn, $orders_query);
$total_orders = $orders_result ? mysqli_num_rows($orders_result) : 0;

// Calculate dabeli count from order items
$dabeli_count = 0;
if ($orders_result) {
    mysqli_data_seek($orders_result, 0);
    while ($order = mysqli_fetch_assoc($orders_result)) {
        $items_query = "SELECT * FROM order_items WHERE order_id=" . $order['id'];
        $items_result = @mysqli_query($conn, $items_query);
        if ($items_result) {
            while ($item = mysqli_fetch_assoc($items_result)) {
                if (stripos($item['item_name'], 'dabeli') !== false) {
                    $dabeli_count += $item['item_quantity'];
                }
            }
        }
    }
}

// Calculate progress and rewards
$reward_threshold = 10;
$free_dabelis = 4;
$completed_rewards = floor($dabeli_count / $reward_threshold);
$current_progress = $dabeli_count % $reward_threshold;
$progress_percentage = ($current_progress / $reward_threshold) * 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards - Spice & Smile</title>
    <link rel="stylesheet" href="HOMEPAGE.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff9f0 0%, #ffe8d6 100%);
            min-height: 100vh;
        }

        .rewards-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .rewards-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .rewards-header h1 {
            font-size: 2.8rem;
            color: #333;
            margin-bottom: 10px;
        }

        .rewards-header p {
            font-size: 1.2rem;
            color: #666;
        }

        .rewards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }

        /* Circle Card with Animation */
        .reward-card {
            background: white;
            border-radius: 25px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .reward-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(244,130,37,0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .reward-card:hover::before {
            opacity: 1;
        }

        .reward-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(244,130,37,0.3);
        }

        /* Circle Progress Animation */
        .circle-container {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 30px;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .reward-card:hover .circle-container {
            transform: scale(1.15) rotate(5deg);
        }

        .progress-circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: conic-gradient(
                #f48225 0deg,
                #f48225 calc(var(--progress) * 3.6deg),
                #f0f0f0 calc(var(--progress) * 3.6deg),
                #f0f0f0 360deg
            );
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.5s ease;
            animation: rotateIn 0.8s ease-out;
        }

        @keyframes rotateIn {
            from {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            to {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        .reward-card:hover .progress-circle {
            box-shadow: 0 0 30px rgba(244,130,37,0.5);
        }

        .circle-inner {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
        }

        .reward-card:hover .circle-inner {
            background: linear-gradient(135deg, #fff 0%, #fff9f0 100%);
        }

        .circle-number {
            font-size: 3rem;
            font-weight: 800;
            color: #f48225;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .reward-card:hover .circle-number {
            transform: scale(1.1);
        }

        .circle-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
        }

        .reward-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .reward-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .reward-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #f48225 0%, #ff9a56 100%);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .reward-card:hover .reward-badge {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(244,130,37,0.4);
        }

        /* Stats Section */
        .stats-container {
            background: white;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            position: relative;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .stat-item:hover .stat-icon {
            transform: scale(1.2) rotate(10deg);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #f48225;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 1rem;
        }

        .cta-section {
            text-align: center;
            margin-top: 50px;
        }

        .cta-btn {
            display: inline-block;
            padding: 18px 50px;
            background: linear-gradient(135deg, #f48225 0%, #ff9a56 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 10px 30px rgba(244,130,37,0.3);
        }

        .cta-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(244,130,37,0.4);
        }

        /* ==================== RESPONSIVE STYLES ==================== */

        /* Large Tablets */
        @media (max-width: 1024px) {
            .rewards-wrapper {
                padding: 0 20px;
            }
            
            .rewards-header h1 {
                font-size: 2.2rem;
            }
            
            .rewards-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 25px;
            }
        }

        /* Tablets */
        @media (max-width: 768px) {
            .rewards-header h1 {
                font-size: 2rem;
            }

            .rewards-header p {
                font-size: 1rem;
            }

            .rewards-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .circle-container {
                width: 180px;
                height: 180px;
            }

            .progress-circle {
                width: 180px;
                height: 180px;
            }

            .circle-inner {
                width: 140px;
                height: 140px;
            }

            .circle-number {
                font-size: 2.5rem;
            }
            
            .circle-label {
                font-size: 0.85rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }

        /* Small Tablets and Large Phones */
        @media (max-width: 600px) {
            .rewards-wrapper {
                padding: 0 15px;
                margin: 25px auto;
            }
            
            .rewards-header h1 {
                font-size: 1.8rem;
                margin-bottom: 8px;
            }
            
            .rewards-header p {
                font-size: 0.95rem;
            }
            
            .reward-card {
                padding: 20px;
            }
            
            .reward-card h3 {
                font-size: 1.3rem;
                margin-bottom: 15px;
            }
            
            .circle-container {
                width: 160px;
                height: 160px;
            }

            .progress-circle {
                width: 160px;
                height: 160px;
            }

            .circle-inner {
                width: 125px;
                height: 125px;
            }

            .circle-number {
                font-size: 2.2rem;
            }
            
            .circle-label {
                font-size: 0.8rem;
            }
            
            .reward-card p {
                font-size: 0.95rem;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .stat-label {
                font-size: 0.9rem;
            }
            
            .cta-btn {
                padding: 12px 25px;
                font-size: 0.95rem;
            }
        }

        /* Mobile Phones */
        @media (max-width: 480px) {
            .rewards-wrapper {
                margin: 20px auto;
                padding: 0 10px;
            }
            
            .rewards-header h1 {
                font-size: 1.5rem;
            }
            
            .rewards-header p {
                font-size: 0.9rem;
            }
            
            .reward-card {
                padding: 18px;
            }
            
            .reward-card h3 {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }
            
            .circle-container {
                width: 140px;
                height: 140px;
            }

            .progress-circle {
                width: 140px;
                height: 140px;
            }

            .circle-inner {
                width: 110px;
                height: 110px;
            }

            .circle-number {
                font-size: 1.8rem;
            }
            
            .circle-label {
                font-size: 0.75rem;
            }
            
            .reward-card p {
                font-size: 0.85rem;
                line-height: 1.4;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-card {
                padding: 12px;
            }
            
            .stat-number {
                font-size: 1.6rem;
            }
            
            .stat-label {
                font-size: 0.85rem;
            }
            
            .cta-section h2 {
                font-size: 1.3rem;
            }
            
            .cta-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        /* Extra Small Phones */
        @media (max-width: 359px) {
            .rewards-header h1 {
                font-size: 1.3rem;
            }
            
            .rewards-header p {
                font-size: 0.85rem;
            }
            
            .reward-card {
                padding: 15px;
            }
            
            .reward-card h3 {
                font-size: 1rem;
            }
            
            .circle-container {
                width: 120px;
                height: 120px;
            }

            .progress-circle {
                width: 120px;
                height: 120px;
            }

            .circle-inner {
                width: 95px;
                height: 95px;
            }

            .circle-number {
                font-size: 1.5rem;
            }
            
            .circle-label {
                font-size: 0.7rem;
            }
            
            .reward-card p {
                font-size: 0.8rem;
            }
            
            .stat-card {
                padding: 10px;
            }
            
            .stat-number {
                font-size: 1.4rem;
            }
            
            .stat-label {
                font-size: 0.8rem;
            }
            
            .cta-section h2 {
                font-size: 1.1rem;
            }
            
            .cta-btn {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
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
                    <li><a href="cart.php">🛒 Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="rewards-wrapper">
        <div class="rewards-header">
            <h1>🎁 Your Rewards</h1>
            <p>Order 10 Dabelis, Get 4 FREE!</p>
        </div>

        <div class="stats-container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">📦</div>
                    <div class="stat-number"><?php echo $total_orders; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🥙</div>
                    <div class="stat-number"><?php echo $dabeli_count; ?></div>
                    <div class="stat-label">Dabelis Ordered</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🎉</div>
                    <div class="stat-number"><?php echo $completed_rewards; ?></div>
                    <div class="stat-label">Rewards Earned</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">✨</div>
                    <div class="stat-number"><?php echo $completed_rewards * $free_dabelis; ?></div>
                    <div class="stat-label">Free Dabelis</div>
                </div>
            </div>
        </div>

        <div class="rewards-grid">
            <!-- Current Progress Card -->
            <div class="reward-card">
                <div class="circle-container">
                    <div class="progress-circle" style="--progress: <?php echo $progress_percentage; ?>">
                        <div class="circle-inner">
                            <div class="circle-number"><?php echo $current_progress; ?>/<?php echo $reward_threshold; ?></div>
                            <div class="circle-label">Progress</div>
                        </div>
                    </div>
                </div>
                <h3 class="reward-title">Current Progress</h3>
                <p class="reward-description">
                    <?php 
                    $remaining = $reward_threshold - $current_progress;
                    if ($remaining > 0) {
                        echo "Just $remaining more Dabelis to unlock your next reward!";
                    } else {
                        echo "Congratulations! You've earned a reward!";
                    }
                    ?>
                </p>
                <div class="reward-badge">
                    <?php echo round($progress_percentage); ?>% Complete
                </div>
            </div>

            <!-- Reward Offer Card -->
            <div class="reward-card">
                <div class="circle-container">
                    <div class="progress-circle" style="--progress: 100">
                        <div class="circle-inner">
                            <div class="circle-number"><?php echo $free_dabelis; ?></div>
                            <div class="circle-label">FREE</div>
                        </div>
                    </div>
                </div>
                <h3 class="reward-title">Your Reward</h3>
                <p class="reward-description">
                    Order 10 Dabelis and get 4 Dabelis absolutely free! Keep ordering to unlock more rewards.
                </p>
                <div class="reward-badge">
                    🎁 Special Offer
                </div>
            </div>

            <!-- Completed Rewards Card -->
            <div class="reward-card">
                <div class="circle-container">
                    <div class="progress-circle" style="--progress: <?php echo $completed_rewards > 0 ? 100 : 0; ?>">
                        <div class="circle-inner">
                            <div class="circle-number"><?php echo $completed_rewards; ?></div>
                            <div class="circle-label">Unlocked</div>
                        </div>
                    </div>
                </div>
                <h3 class="reward-title">Rewards Earned</h3>
                <p class="reward-description">
                    You've unlocked <?php echo $completed_rewards; ?> rewards so far! Keep ordering to earn more.
                </p>
                <div class="reward-badge">
                    🏆 Achievements
                </div>
            </div>
        </div>

        <div class="cta-section">
            <a href="menu.php" class="cta-btn">Order Now & Earn Rewards</a>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="color: #f48225; text-decoration: none; font-weight: 600; font-size: 1.1rem;">
                ← Back to Home
            </a>
        </div>
    </div>

</body>
</html>
