<?php
require_once "Database.php";
class Promotions_database extends Database
{
    public function getAllpromotions()
    {
        $sql = self::$connection->prepare("SELECT * from promotions");
        $sql->execute();
        $item = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $item;
    }
}
