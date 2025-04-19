<?php
include 'config.php'; // Đảm bảo file cấu hình DB được include

$sql = $conn->prepare("
    SELECT *
    FROM `users`
    WHERE `role` = 'admin'
    ORDER BY `created_at` ASC
    LIMIT 1;
");
$sql->execute();
$admin = $sql->fetch(PDO::FETCH_ASSOC); // Trả về mảng kết hợp
