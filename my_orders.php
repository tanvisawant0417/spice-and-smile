<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['phone'])) {
    header("Location: signup.php?redirect=my_orders");
    exit;
}

include 'db.php';
$phone = $_SESSION['phone'];

// Create orders table if not exists
$create_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(15) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    street TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    zip VARCHAR(10) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (phone)
)";
mysqli_query($conn, $create_orders);

// Get all orders for this user
$orders_query = "SELECT * FROM orders WHERE phone='$phone' ORDER BY created_at DESC";
$orders_result = mysqli_query($conn, $orders_query);
$orders = [];
while ($row = mysqli_fetch_assoc($orders_result)) {
    $orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Spice & Smile</title>
    <link rel="stylesheet" href="HOMEPAGE.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .orders-wrapper {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .orders-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .orders-header h1 {
            margin: 0;
            font-size: 2.2rem;
            color: #333;
        }
        
        .order-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-left: 5px solid #f48225;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .order-id {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }
        
        .order-status {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-delivered {
            background: #cfe2ff;
            color: #084298;
        }
        
        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item {
            font-size: 0.95rem;
        }
        
        .detail-label {
            color: #666;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: #333;
        }
        
        .order-items {
            background: #f8f8f8;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .items-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .item-list li {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
            font-size: 0.95rem;
            display: flex;
            justify-content: space-between;
        }
        
        .item-list li:last-child {
            border-bottom: none;
        }
        
        .order-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: bold;
            color: #f48225;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-text {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 20px;
        }
        
        .back-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #f48225;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: #d66b1d;
            transform: translateY(-2px);
        }

        /* ==================== RESPONSIVE STYLES ==================== */

        /* Large Tablets */
        @media (max-width: 1024px) {
            .orders-wrapper {
                margin: 30px auto;
                padding: 0 20px;
            }
            
            .orders-header h1 {
                font-size: 2rem;
            }
        }

        /* Tablets */
        @media (max-width: 768px) {
            .orders-wrapper {
                margin: 20px auto;
                padding: 0 15px;
            }
            
            .orders-header h1 {
                font-size: 1.8rem;
            }
            
            .order-details {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .order-card {
                padding: 20px;
            }
            
            .order-header h3 {
                font-size: 1.1rem;
            }
        }

        /* Small Tablets and Large Phones */
        @media (max-width: 600px) {
            .orders-wrapper {
                margin: 15px auto;
                padding: 0 10px;
            }
            
            .orders-header h1 {
                font-size: 1.6rem;
                margin-bottom: 15px;
            }
            
            .order-card {
                padding: 18px;
                margin-bottom: 18px;
            }
            
            .order-header h3 {
                font-size: 1rem;
            }
            
            .order-header .order-date,
            .order-header .order-status {
                font-size: 0.85rem;
            }
            
            .detail-label,
            .detail-value {
                font-size: 0.9rem;
            }
            
            .back-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        /* Mobile Phones */
        @media (max-width: 480px) {
            .orders-wrapper {
                margin: 10px auto;
            }
            
            .orders-header h1 {
                font-size: 1.4rem;
            }
            
            .order-card {
                padding: 15px;
                margin-bottom: 15px;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .order-header h3 {
                font-size: 0.95rem;
            }
            
            .order-header .order-date {
                font-size: 0.8rem;
            }
            
            .order-status {
                font-size: 0.75rem;
                padding: 6px 12px;
            }
            
            .detail-label,
            .detail-value {
                font-size: 0.85rem;
            }
            
            .items-section h4 {
                font-size: 0.95rem;
            }
            
            .item-row {
                font-size: 0.85rem;
                flex-wrap: wrap;
            }
        }

        /* Extra Small Phones */
        @media (max-width: 359px) {
            .orders-header h1 {
                font-size: 1.2rem;
            }
            
            .order-card {
                padding: 12px;
            }
            
            .order-header h3 {
                font-size: 0.9rem;
            }
            
            .detail-label,
            .detail-value {
                font-size: 0.8rem;
            }
            
            .items-section h4 {
                font-size: 0.9rem;
            }
            
            .item-row {
                font-size: 0.8rem;
            }
            
            .back-btn {
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

    <div class="orders-wrapper">
        <div class="orders-header">
            <h1>📦 My Orders</h1>
        </div>

        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">Order #<?php echo $order['id']; ?></span>
                        <span class="order-status status-<?php echo strtolower(str_replace(' ', '-', $order['order_status'])); ?>">
                            <?php echo $order['order_status']; ?>
                        </span>
                    </div>

                    <div class="order-details">
                        <div class="detail-item">
                            <div class="detail-label">📅 Date</div>
                            <div class="detail-value"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">👤 Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['fullname']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">📍 City</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['city']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">💳 Payment</div>
                            <div class="detail-value"><?php echo $order['payment_method']; ?></div>
                        </div>
                    </div>

                    <?php
                    // Get order items
                    $items_query = "SELECT * FROM order_items WHERE order_id=" . $order['id'];
                    $items_result = mysqli_query($conn, $items_query);
                    $items = [];
                    while ($item = mysqli_fetch_assoc($items_result)) {
                        $items[] = $item;
                    }
                    ?>

                    <?php if (count($items) > 0): ?>
                        <div class="order-items">
                            <div class="items-title">🍽️ Items Ordered</div>
                            <ul class="item-list">
                                <?php foreach ($items as $item): ?>
                                    <li>
                                        <span><?php echo htmlspecialchars($item['item_name']); ?> x<?php echo $item['item_quantity']; ?></span>
                                        <span>₹<?php echo number_format($item['item_price'] * $item['item_quantity'], 2); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="order-total">
                        <span>Total Amount:</span>
                        <span style="color:#f48225;">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <div class="empty-text">No orders yet</div>
                <a href="menu.php" class="back-btn">Start Ordering Now</a>
            </div>
        <?php endif; ?>

        <div style="margin-top: 40px; text-align: center;">
            <a href="index.php" class="back-btn">← Back to Home</a>
        </div>
    </div>

</body>
</html>
