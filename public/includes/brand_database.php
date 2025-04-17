<?php
require_once "Database.php";
class Brand_Database extends Database
{
    public function getAllBrand()
    {
        $sql = self::$connection->prepare("SELECT * from brand");
        $sql->execute();
        $item = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $item;
    }
    public function getBrandById($id)
    {
        $sql = self::$connection->prepare("SELECT * FROM brand WHERE  brand_id   = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result;
    }
    public function getProductCountByBrand($brand_id)
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM products WHERE brand_id = ?");
        $sql->bind_param("i", $brand_id);
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result['total'];
    }
}
