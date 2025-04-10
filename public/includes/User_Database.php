<?php
require_once 'Database.php';
class User_Database extends Database{

    public function getUser($email){
        $sql = self::$connection->prepare("SELECT * FROM users WHERE email=?");
        $sql->bind_param("s", $email);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }
    

    public function registerUser($full_name, $email, $password, $phone, $address){
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';

        $sql = self::$connection->prepare("INSERT INTO users(full_name, email, password, phone, address, role, created_at) 
                                           VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $sql->bind_param("ssssss", $full_name, $email, $passwordHash, $phone, $address, $role);

        return $sql->execute();
    }
}
?>
