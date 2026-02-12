<?php
session_start();
require_once "db.php"; // includes $conn
$redirect = 'index.php'; // Redirect after successful OTP
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Join the Family - Spice & Smile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Link your existing CSS -->
<link rel="stylesheet" href="signup.css">
</head>

<body>
<img src="images/butterchessedabelli.png" class="bg-image" alt="Background">

<div class="signup-wrapper">

    <!-- PHONE INPUT BOX -->
    <div class="glass-box" id="phone-box">
        <h2>Enter Your Phone Number</h2>
        <input type="tel" id="phone" placeholder="Phone Number" pattern="[0-9]{10}" required>
<button id="send-otp" type="button" class="submit-btn">
    Send OTP
</button>

        <p class="footer-text">We will send a 6-digit OTP to your phone for verification.</p>
    </div>

    <!-- OTP INPUT BOX -->
    <div class="glass-box hidden" id="otp-box">
        <h2>Enter OTP</h2>
        <p class="footer-text" id="otp-display-top" style="background-color: #fff3e0; padding: 15px; border-radius: 8px; border: 2px solid #f48225; margin-bottom: 15px; font-weight: bold; color: #000; font-size: 16px;"></p>
        <input type="text" id="otp" placeholder="6-digit OTP" pattern="[0-9]{6}" required>
       <button id="verify-otp" type="button" class="submit-btn">
    Verify OTP
</button>

        <p class="footer-text" id="otp-message"></p>
    </div>

</div>
<script>
// Send OTP - Only when button is clicked
document.getElementById('send-otp').addEventListener('click', function() {
    const phone = document.getElementById('phone').value;
    
    // Validate phone number
    if (!phone || phone.length !== 10 || !/^[0-9]{10}$/.test(phone)) {
        alert('Please enter a valid 10-digit phone number');
        return;
    }
    
    fetch('process_otp.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `phone=${encodeURIComponent(phone)}`
    }).then(res => res.json()).then(data => {
        if (data.success) {
            // Show OTP box immediately
            document.getElementById('phone-box').classList.add('hidden');
            document.getElementById('otp-box').classList.remove('hidden');
            
            // Display OTP in the OTP box
            const otpDisplayTop = document.getElementById('otp-display-top');
            otpDisplayTop.innerHTML = `✅ Your OTP is: <span style="color:#f48225; font-size:20px;">${data.otp}</span>`;
        } else {
            alert(data.message);
        }
    }).catch(error => {
        alert('Error sending OTP. Please try again.');
        console.error(error);
    });
});

// Verify OTP - Only when button is clicked
document.getElementById('verify-otp').addEventListener('click', function() {
    const otp = document.getElementById('otp').value;
    
    // Validate OTP
    if (!otp || otp.length !== 6 || !/^[0-9]{6}$/.test(otp)) {
        alert('Please enter a valid 6-digit OTP');
        return;
    }
    
    console.log('Verifying OTP:', otp); // Debug log
    
    fetch('verify_otp_process.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `otp=${encodeURIComponent(otp)}`
    })
    .then(res => {
        console.log('Response status:', res.status); // Debug log
        return res.json();
    })
    .then(data => {
        console.log('Response data:', data); // Debug log
        if (data.success) {
            alert('Login successful! Redirecting...');
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Verification failed');
            document.getElementById('otp-message').textContent = data.message || 'Verification failed';
            document.getElementById('otp-message').style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Verification error:', error);
        alert('Error verifying OTP. Please check the console for details.');
    });
});
</script>


</body>
</html>
