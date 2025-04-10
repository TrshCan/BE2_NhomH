<?php
require_once "Database.php";
class Category_Database extends Database{
    public function getAllCategory(){
        $sql = self::$connection->prepare("SELECT * from categories");
        $sql->execute();
        $item = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $item;
    }
    public function getCategoryById($id){
        $sql = self::$connection->prepare("SELECT * FROM categories WHERE  category_id   = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result;
    }
    

}