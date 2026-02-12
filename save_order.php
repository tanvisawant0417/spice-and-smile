<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json; charset=utf-8');
include 'db.php';

try {
    // Check if user is logged in
    if (!isset($_SESSION['phone'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in'], JSON_UNESCAPED_SLASHES);
        exit;
    }

    // Get POST data
    $phone = $_SESSION['phone'];
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $street = isset($_POST['street']) ? trim($_POST['street']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
    $cart_items = isset($_POST['cart_items']) ? json_decode($_POST['cart_items'], true) : [];

    // Validate required fields
    if (!$fullname || !$street || !$city || !$zip || !$payment_method) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    if ($total_amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid total amount']);
        exit;
    }

    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    // Drop old tables if they exist (to reset structure)
    @mysqli_query($conn, "DROP TABLE IF EXISTS order_items");
    @mysqli_query($conn, "DROP TABLE IF EXISTS orders");

    // Create orders table with correct structure
    $create_orders = "CREATE TABLE orders (
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
    if (!mysqli_query($conn, $create_orders)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create orders table']);
        exit;
    }

    // Create order_items table with correct structure
    $create_items = "CREATE TABLE order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        item_name VARCHAR(100) NOT NULL,
        item_price DECIMAL(10, 2) NOT NULL,
        item_quantity INT NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id)
    )";
    if (!mysqli_query($conn, $create_items)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create order_items table']);
        exit;
    }

    // Insert order
    $fullname_esc = mysqli_real_escape_string($conn, $fullname);
    $street_esc = mysqli_real_escape_string($conn, $street);
    $city_esc = mysqli_real_escape_string($conn, $city);
    
    $insert_order = "INSERT INTO orders (phone, fullname, street, city, zip, payment_method, total_amount) 
                     VALUES ('$phone', '$fullname_esc', '$street_esc', '$city_esc', '$zip', '$payment_method', $total_amount)";
    
    if (!mysqli_query($conn, $insert_order)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create order']);
        exit;
    }

    $order_id = mysqli_insert_id($conn);

    // Insert order items
    if (is_array($cart_items) && count($cart_items) > 0) {
        foreach ($cart_items as $item) {
            $item_name = isset($item['name']) ? mysqli_real_escape_string($conn, $item['name']) : '';
            $item_price = isset($item['price']) ? floatval($item['price']) : 0;
            $item_qty = isset($item['quantity']) ? intval($item['quantity']) : 1;

            if ($item_name && $item_price > 0) {
                $insert_item = "INSERT INTO order_items (order_id, item_name, item_price, item_quantity) 
                               VALUES ($order_id, '$item_name', $item_price, $item_qty)";
                @mysqli_query($conn, $insert_item);
            }
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Order saved successfully',
        'order_id' => $order_id
    ], JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
exit;
?>
?>

// Check connection
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

// Collect POST data safely
$fullname = $_POST['fullname'] ?? '';
$street = $_POST['street'] ?? '';
$city = $_POST['city'] ?? '';
$zip = $_POST['zip'] ?? '';
$user_phone = $_POST['phone'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$cartItemsJSON = $_POST['cart_items'] ?? '';
$total_amount = $_POST['total_amount'] ?? 0;

// Insert into orders table
$stmt = $conn->prepare("
    INSERT INTO orders 
    (user_phone, fullname, street, city, zip, payment_method, total_amount)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "ssssssd",
    $user_phone,
    $fullname,
    $street,
    $city,
    $zip,
    $payment_method,
    $total_amount
);

// Check for errors
if (!$stmt->execute()) {
    die("Order Insert Failed: " . $stmt->error);
}

// Get order ID
$order_id = $conn->insert_id;
$stmt->close();

// Insert each item into order_items
$cartItems = json_decode($cartItemsJSON, true);

$itemStmt = $conn->prepare("
    INSERT INTO order_items (order_id, item_name, item_price, quantity, subtotal)
    VALUES (?, ?, ?, ?, ?)
");

foreach ($cartItems as $item) {
    $name = $item['name'];
    $price = (float) $item['price'];
    $qty = (int) $item['quantity'];
    $subtotal = $price * $qty;

    $itemStmt->bind_param(
        "isddd",
        $order_id,
        $name,
        $price,
        $qty,
        $subtotal
    );
    if (!$itemStmt->execute()) {
        // Log error if needed
        file_put_contents("order_debug.txt", "Item insert failed: " . $itemStmt->error . "\n", FILE_APPEND);
    }
}

$itemStmt->close();
$conn->close();

// Redirect to success
header("Location: order_success.php?order_id=" . $order_id);
exit;
