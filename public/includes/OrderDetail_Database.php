<?php
require_once "Database.php";

class Order_Detail_Database extends Database
{
    private $order_detail_id;
    private $order_id;
    private $product_id;
    private $quantity;
    private $price;

    // Getters
    public function getOrderDetailId() {
        return $this->order_detail_id;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getPrice() {
        return $this->price;
    }

    // CRUD Functions

    // Create: Insert a new order detail
    public function createOrderDetail($order_id, $product_id, $quantity, $price) {
        $sql = self::$connection->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $sql->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        return $sql->execute(); // Returns true on success, false on failure
    }

    // Read: Get all order details
    public function getAllOrderDetails() {
        $sql = self::$connection->prepare("SELECT * FROM order_details");
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; // Array of order details
    }

    // Read: Get order detail by ID
    public function getOrderDetailById($order_detail_id) {
        $sql = self::$connection->prepare("SELECT * FROM order_details WHERE order_detail_id = ?");
        $sql->bind_param("i", $order_detail_id);
        $sql->execute();
        $item = $sql->get_result()->fetch_assoc();
        return $item; // Associative array or null if not found
    }

    // Read: Get order details by order ID
    public function getOrderDetailsByOrderId($order_id) {
        $sql = self::$connection->prepare("SELECT * FROM order_details WHERE order_id = ?");
        $sql->bind_param("i", $order_id);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; // Array of order details for the given order
    }

    // Update: Update an existing order detail
    public function updateOrderDetail($order_detail_id, $order_id, $product_id, $quantity, $price) {
        $sql = self::$connection->prepare("UPDATE order_details SET order_id = ?, product_id = ?, quantity = ?, price = ? WHERE order_detail_id = ?");
        $sql->bind_param("iiidi", $order_id, $product_id, $quantity, $price, $order_detail_id);
        return $sql->execute(); // Returns true on success, false on failure
    }

    // Delete: Delete an order detail by ID
    public function deleteOrderDetail($order_detail_id) {
        $sql = self::$connection->prepare("DELETE FROM order_details WHERE order_detail_id = ?");
        $sql->bind_param("i", $order_detail_id);
        return $sql->execute(); // Returns true on success, false on failure
    }
}
?>