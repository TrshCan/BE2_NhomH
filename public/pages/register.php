<?php
include '../includes/header.php'; 
require_once '../includes/User_Database.php';

$userDB = new User_Database();

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $full_name = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if($password != $repassword){
        $error = "Mật khẩu không khớp!";
    } else {
        $user = $userDB->getUser($email);

        if($user){
            $error = "Email đã tồn tại!";
        } else {
            if($userDB->registerUser($full_name, $email, $password, $phone, $address)){
                $success = "Đăng ký thành công!";
            } else {
                $error = "Không đăng ký được!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Đăng Ký</h5>
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ Và Tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập Họ Và Tên" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập Email..." required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật Khẩu</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="repassword" class="form-label">Nhập Lại Mật Khẩu</label>
                            <input type="password" class="form-control" name="repassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số Điện Thoại</label>
                            <input type="text" class="form-control" name="phone" placeholder="Nhập SĐT..." required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control" name="address" placeholder="Nhập Địa Chỉ..." required>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary w-100">Đăng Ký</button>
                        <a href="login.php">Quay trở lại đăng nhập</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
