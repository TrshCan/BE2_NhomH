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

    // Load nested-divisions.json
    $json_file = __DIR__ . '/../assets/nested-divisions.json';
    if (!file_exists($json_file)) {
        error_log('nested-divisions.json not found at ' . $json_file);
        die('Error: Location data file not found');
    }
    $location_data = json_decode(file_get_contents($json_file), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Failed to parse nested-divisions.json: ' . json_last_error_msg());
        die('Error: Invalid location data format');
    }

    // Get codes from form (convert to integers)
    $province_code = isset($_POST['province']) ? (int)$_POST['province'] : 0;
    $district_code = isset($_POST['district']) ? (int)$_POST['district'] : 0;
    $ward_code = isset($_POST['ward']) ? (int)$_POST['ward'] : 0;
    $street = isset($_POST['address']) ? trim($_POST['address']) : '';

    // Map codes to names
    $province_name = '';
    $district_name = '';
    $ward_name = '';

    foreach ($location_data as $province) {
        if ($province['code'] === $province_code) {
            $province_name = $province['name'];
            if (isset($province['districts'])) {
                foreach ($province['districts'] as $district) {
                    if ($district['code'] === $district_code) {
                        $district_name = $district['name'];
                        if (isset($district['wards'])) {
                            foreach ($district['wards'] as $ward) {
                                if ($ward['code'] === $ward_code) {
                                    $ward_name = $ward['name'];
                                    break;
                                }
                            }
                        }
                        break;
                    }
                }
            }
            break;
        }
    }

    // Fallback to codes if names not found
    $province_name = $province_name ?: $province_code;
    $district_name = $district_name ?: $district_code;
    $ward_name = $ward_name ?: $ward_code;

    // Construct readable address
    $address_parts = array_filter([
        $street,
        $ward_name,
        $district_name,
        $province_name
    ], 'strlen');
    $address = implode(', ', $address_parts);

    // Form data
    $user_id = isset($_POST['id']) ? $_POST['id'] : null;
    $payment_method = $_POST['payment'];

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