<?php
// File: danhgia_db.php
include_once 'config.php';

class danhgia_db {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    public function getProductById($product_id) {
        try {
            $sql = "SELECT p.*, c.category_name, b.brand_name
                    FROM Products p
                    LEFT JOIN Categories c ON p.category_id = c.category_id
                    LEFT JOIN Brands b ON p.brand_id = b.brand_id
                    WHERE p.product_id = :product_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $product ?: null;
            
        } catch(PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return null;
        }
    }
}
