<?php
session_start();
require_once '../includes/Order_Database.php';
require_once '../includes/Order_Detail_Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_db = new Order_Database();
    $order_detail_db = new Order_Detail_Database();

    // Calculate total
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }

    // Form data
    $user_id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $payment_method = $_POST['payment'];
    $address = implode(', ', [$_POST['address'], $_POST['ward'], $_POST['district'], $_POST['province']]);

    // Create order
    $order_db->createOrder($user_id, $total, $payment_method, $address);
    $order_id = self::$connection->insert_id; // Get the last inserted order ID

    // Create order details
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $order_detail_db->createOrderDetail($order_id, $product_id, $item['quantity'], $item['price']);
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect
    header("Location: ../../index.php");
    exit;
}
?>