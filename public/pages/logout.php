<?php
session_start(); 
$temp_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
session_unset();
session_destroy();
session_start();
$_SESSION['cart'] = $temp_cart;
$_SESSION['isLoggedIn'] = false; 
header('Location: login.php'); 
exit;
?>
