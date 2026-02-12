<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

try {
    if (!isset($_POST['phone']) || !isset($_POST['otp'])) {
        echo json_encode(['success' => false, 'message' => 'Phone or OTP missing']);
        exit;
    }

    $phone = trim($_POST['phone']);
    $otp = trim($_POST['otp']);

    // Validate phone number
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Invalid phone number']);
        exit;
    }

    // Validate OTP format
    if (!preg_match('/^\d{6}$/', $otp)) {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP format']);
        exit;
    }

    // Check database connection
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }

    // Ensure table exists
    $create_table = "CREATE TABLE IF NOT EXISTS otp_verification (
        id INT AUTO_INCREMENT PRIMARY KEY,
        phone VARCHAR(15) NOT NULL,
        otp VARCHAR(10) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $create_table);

    // Ensure users table exists
    $create_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        phone VARCHAR(15) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $create_users);

    // Check OTP in database
    $sql = "SELECT * FROM otp_verification WHERE phone='$phone' AND otp='$otp'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Check if OTP is expired (10 minutes) - only if created_at exists
        if (isset($row['created_at'])) {
            $created_time = strtotime($row['created_at']);
            $current_time = time();
            $time_diff = ($current_time - $created_time) / 60;
            
            if ($time_diff > 10) {
                echo json_encode(['success' => false, 'message' => 'OTP expired']);
                exit;
            }
        }
        
        // OTP is valid
        $_SESSION['phone'] = $phone;
        
        // Delete used OTP
        mysqli_query($conn, "DELETE FROM otp_verification WHERE phone='$phone'");
        
        // Check if user already exists or create new account
        $check_user = mysqli_query($conn, "SELECT * FROM users WHERE phone='$phone'");
        
        if (mysqli_num_rows($check_user) === 0) {
            // Create new user
            $insert_user = "INSERT INTO users (phone) VALUES ('$phone')";
            if (!mysqli_query($conn, $insert_user)) {
                // User might already exist due to race condition, that's ok
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Phone verified successfully',
            'redirect' => 'index.php'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
