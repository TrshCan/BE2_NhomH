<?php
session_start();
require_once '../includes/Order_Database.php';
require_once '../includes/OrderDetail_Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_db = new Order_Database();
    $order_detail_db = new Order_Detail_Database();

    // Calculate total with discount
    $subtotal = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $total = $subtotal - $discount;

    // Form data
    $user_id = isset($_POST['id']) ? $_POST['id'] : null;
    $payment_method = $_POST['payment'];
    $address = implode(', ', [
        $_POST['address'],
        $_POST['ward'],
        $_POST['district'],
        $_POST['province']
    ]);

    // Create order
    $order_db->createOrder($user_id, $total, $payment_method, $address, $discount);
    $order_id = Order_Database::$connection->insert_id;

    // Create order details
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $order_detail_db->createOrderDetail($order_id, $product_id, $item['quantity'], $item['price']);
    }

    // Set session variables for success modal
    $_SESSION['checkout_success'] = true;
    $_SESSION['order_id'] = $order_id;

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to checkout.php
    header("Location: ../pages/checkout.php");
    exit;
}
?>