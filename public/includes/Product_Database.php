<?php
require_once "Database.php";
class Product_Database extends Database{
    public function getAllProduct(){
        $sql = self::$connection->prepare("SELECT * from products");
        $sql->execute();
        $item = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $item;
    }

    public function getTotalProducts($category_id = null)
    {
        if ($category_id) {
            $sql = self::$connection->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = ?");
            $sql->bind_param("i", $category_id);
        } else {
            $sql = self::$connection->prepare("SELECT COUNT(*) as total FROM products");
        }
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc()['total'];
    }
    public function getAllProductPaged($limit, $offset) {
        $sql = self::$connection->prepare("SELECT * FROM products LIMIT ?, ?");
        $sql->bind_param("ii", $offset, $limit);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getProductsByCategoryPaged($category_id, $limit, $offset) {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ?, ?");
        $sql->bind_param("iii", $category_id, $offset, $limit);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
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
    public function searchProduct($keyword)
    {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE name LIKE '%$keyword%'");
        //$sql->bind_param("i", $category_id); // Gán tham số
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC); // Lấy tất cả kết quả dưới dạng mảng kết hợp
        return $items; // Trả về mảng kết quả
    }
    public function searchProduct_Paginate($keyword, $page, $perPage)
    {
        $startRecord = ($page - 1) * $perPage;
        $sql = self::$connection->prepare("SELECT * FROM products WHERE name LIKE '%$keyword%' LIMIT $startRecord,$perPage");
        //$sql->bind_param("i", $category_id); // Gán tham số
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC); // Lấy tất cả kết quả dưới dạng mảng kết hợp
        return $items; // Trả về mảng kết quả
    }
    function paginateBar($url, $keyword, $total, $perPage, $page)
    {
        $totalLinks = ceil($total / $perPage);

        $link = "";
        $link .= "<a href='$url?&page=1'> << </a>";
        $p = ($page > 1) ? ($page - 1)  : 1;
        $link .= "<a href='$url&page=$p'> < </a>";
        for ($j = 1; $j <= $totalLinks; $j++) {
            $link .= "<a href='$url&page=$j'> $j </a>";
        }

        $p2 = ($page < $totalLinks) ? ($page + 1)  : $totalLinks;
        $link .= " <span style='border: solid 2px red;' ><a href='$url?&page=$p2'> > </a></span>";

        $link .= "<a href='$url&page=$totalLinks'> >> </a>";
        return $link;
    }
    //
    public function searchProduct_total($keyword)
    {
        $sql = self::$connection->prepare("SELECT count(*) as total FROM products WHERE name LIKE '%$keyword%'");
        //$sql->bind_param("i", $category_id); // Gán tham số
        $sql->execute();
        $items = $sql->get_result()->fetch_assoc()['total']; // Lấy tất cả kết quả dưới dạng mảng kết hợp
        return $items; // Trả về mảng kết quả
    }

}
