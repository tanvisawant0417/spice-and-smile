<?php
session_start();
// Destroy session to log user out
session_unset();
session_destroy();

// Redirect to home page or current page
header("Location: " . ($_GET['redirect'] ?? "index.php"));
exit();
?>
