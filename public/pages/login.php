
?>
<?php
require_once '../includes/User_Database.php';
$userDb = new User_Database();
$success = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || preg_match('/\s/', $email)) {
        $error = 'Email không được để trống và không được chứa khoảng trắng';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    } elseif (empty($password) || preg_match('/\s/', $password)) {
        $error = 'Mật khẩu không được để trống và không được chứa khoảng trắng';
    } else {
        $user = $userDb->getUser($email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $email;
                header('Location: ../../index.php');
                exit;
            } else {
                $error = 'Sai Mật Khẩu';
            }
        } else {
            $error = 'Email không tồn tại';
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
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Đăng Nhập</h5>
                <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Nhập email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="login" class="btn btn-primary w-100">Đăng Nhập</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="forgotpassword.php" class="text-decoration-none">Quên mật khẩu?</a><br>
                    <a href="register.php" class="text-decoration-none">Đăng ký tài khoản mới</a>
                </div>


                <div class="mt-4">
                    <a href="/login/google" class="btn btn-danger w-100 mb-2">
                        <i class="material-icons align-middle">account_circle</i> Đăng nhập với Google
                    </a>
                    <a href="/login/facebook" class="btn btn-primary w-100">
                        <i class="material-icons align-middle">facebook</i> Đăng nhập với Facebook
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
