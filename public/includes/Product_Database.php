<?php
require_once "Database.php";
class Product_Database extends Database{
    public function getAllProduct(){
        $sql = self::$connection->prepare("SELECT * from products");
        $sql->execute();
        $item = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $item;
    }
    public function getProductById($id){
        $sql = self::$connection->prepare("SELECT * FROM products WHERE  product_id  = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result;
    }
    public function getProductsByCategoryId($category_id)
    {
        $sql = self::$connection->prepare("SELECT * from products where category_id=?");
        $sql->bind_param("i", $category_id);
        $sql->execute();
        $item = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $item;
    }

}
