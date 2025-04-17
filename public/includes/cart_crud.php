<?php
session_start();
require_once 'Database.php';
require_once 'Product_Database.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions via GET parameters
if (isset($_GET['id']) || isset($_GET['action'])) {
    $productModel = new Product_Database();

    if ($_GET['action'] == 'add') {
        $product_id = $_GET['id'];
        $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1; // Default to 1 if not provided

        if (isset($_SESSION['cart'][$product_id])) {
            // If product exists in cart, increment quantity
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Fetch product details and add new item to cart
            $product = $productModel->getProductById($product_id);
            if ($product) {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'image' => $product['image'] // Assuming your table has an image column
                ];
            }
        }
        // Redirect to cart page after adding
        header("Location: ../../index.php");
        exit;

    } elseif ($_GET['action'] == 'delete') {
        $product_id = (int)$_GET['id'];
        if (isset($_SESSION['cart'][$product_id])) {
            // Decrease quantity by 1
            $_SESSION['cart'][$product_id]['quantity'] -= 1;
            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]); // Remove if quantity reaches 0 or below
            }
        }
        header("Location: ../pages/cart.php");
        exit;

    } elseif ($_GET['action'] == 'deleteall') {
        // Clear the entire cart
        unset($_SESSION['cart']);
        header("Location: ../pages/cart.php");
        exit; 
    } elseif ($_GET['action'] == 'update_quantity' && isset($_GET['id']) && isset($_GET['quantity'])) {
        $id = $_GET['id'];
        $quantity = (int)$_GET['quantity'];

        if ($quantity < 1) {
            header("Location: ../pages/cart.php?error=quantity_invalid");
            exit();
        } elseif (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        } else {
            header("Location: ../pages/cart.php?error=product_not_found");
            exit();
        }

        header("Location: ../pages/cart.php");
        exit();
    }
} else {
    // If no valid action or ID is provided, redirect to index
    header("Location: ../../index.php");
    exit;
}
?>