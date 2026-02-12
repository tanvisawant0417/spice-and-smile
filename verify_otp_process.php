<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_POST['otp'])) {
    echo json_encode(['success' => false, 'message' => 'OTP is required']);
    exit;
}

if (!isset($_SESSION['phone'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired. Please request OTP again.']);
    exit;
}

$otp = trim($_POST['otp']);
$phone = $_SESSION['phone'];

// Validate OTP format
if (!preg_match('/^\d{6}$/', $otp)) {
    echo json_encode(['success' => false, 'message' => 'Invalid OTP format']);
    exit;
}

$sql = "SELECT * FROM otp_verification WHERE phone='$phone' AND otp='$otp'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    // Check if is_verified column exists, if not just skip updating it
    $update_sql = "UPDATE otp_verification SET otp='verified' WHERE phone='$phone'";
    mysqli_query($conn, $update_sql);

    $_SESSION['authenticated'] = true;
    $_SESSION['user_phone'] = $phone;

    // Get redirect location from session or default to index.php
    $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'index.php';
    unset($_SESSION['redirect_after_login']);

    echo json_encode([
        'success' => true,
        'redirect' => $redirect
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Incorrect OTP. Please try again.'
    ]);
}
?>