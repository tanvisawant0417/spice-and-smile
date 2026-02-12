<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');
include 'db.php';

try {
    if (!isset($_POST['phone'])) {
        echo json_encode(['success' => false, 'message' => 'Phone missing']);
        exit;
    }

    $phone = trim($_POST['phone']);
    $otp = rand(100000, 999999);

    // Validate phone number
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Invalid phone number']);
        exit;
    }

    // Check database connection
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit;
    }

    // Save phone in session
    $_SESSION['phone'] = $phone;

    // Ensure table exists
    $create_table = "CREATE TABLE IF NOT EXISTS otp_verification (
        id INT AUTO_INCREMENT PRIMARY KEY,
        phone VARCHAR(15) NOT NULL,
        otp VARCHAR(10) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $create_table);

    // Remove old OTP
    mysqli_query($conn, "DELETE FROM otp_verification WHERE phone='$phone'");

    // Insert OTP - try with created_at first, then without
    $created_at = date('Y-m-d H:i:s');
    $sql = "INSERT INTO otp_verification (phone, otp, created_at) VALUES ('$phone', '$otp', '$created_at')";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        // Try without created_at (in case column doesn't exist)
        $sql = "INSERT INTO otp_verification (phone, otp) VALUES ('$phone', '$otp')";
        $result = mysqli_query($conn, $sql);
    }

    if ($result) {
        // Success - return OTP for development
        echo json_encode([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save OTP: ' . mysqli_error($conn)
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
