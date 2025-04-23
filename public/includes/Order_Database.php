<?php
require_once "Database.php";

class Order_Database extends Database
{
    private $order_id;
    private $user_id;
    private $total_price;
    private $payment_method;
    private $address;
    private $status;

    // Getters
    public function getOrderId() {
        return $this->order_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getTotalPrice() {
        return $this->total_price;
    }

    public function getPaymentMethod() {
        return $this->payment_method;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getStatus() {
        return $this->status;
    }

    // CRUD Functions

    // Create: Insert a new order
    public function createOrder($user_id, $total_price, $payment_method, $address, $status = 'đang chờ') {
        $sql = self::$connection->prepare("INSERT INTO orders (user_id, total_amount, payment_method, shipping_address, status) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("idsss", $user_id, $total_price, $payment_method, $address, $status);
        return $sql->execute(); // Returns true on success, false on failure
    }

    // Read: Get all orders
    public function getAllOrders() {
        $sql = self::$connection->prepare("SELECT * FROM orders");
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items; // Array of orders
    }

    // Read: Get order by ID
    public function getOrderById($order_id) {
        $sql = self::$connection->prepare("SELECT * FROM orders WHERE order_id = ?");
        $sql->bind_param("i", $order_id);
        $sql->execute();
        $item = $sql->get_result()->fetch_assoc();
        return $item; // Associative array or null if not found
    }

    // Update: Update an existing order
    public function updateOrder($order_id, $user_id, $total_price, $payment_method, $address, $status) {
        $sql = self::$connection->prepare("UPDATE orders SET user_id = ?, total_amount = ?, payment_method = ?, shipping_address = ?, status = ? WHERE order_id = ?");
        $sql->bind_param("idsssi", $user_id, $total_price, $payment_method, $address, $status, $order_id);
        return $sql->execute(); // Returns true on success, false on failure
    }

    // Delete: Delete an order by ID
    public function deleteOrder($order_id) {
        $sql = self::$connection->prepare("DELETE FROM orders WHERE order_id = ?");
        $sql->bind_param("i", $order_id);
        return $sql->execute(); // Returns true on success, false on failure
    }
}
?>