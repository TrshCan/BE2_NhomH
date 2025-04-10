<?php
session_start();
include 'config.php';

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header("Location: livechat.php");
        exit();
    } else {
        $login_error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}

// Xử lý đăng ký
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
        $register_success = "Đăng ký thành công! Vui lòng đăng nhập.";
    } catch (PDOException $e) {
        $register_error = "Tên đăng nhập đã tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Chat System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 5px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .toggle-form {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        
        <?php if (isset($login_error)): ?>
            <div class="error"><?php echo $login_error; ?></div>
        <?php endif; ?>
        <?php if (isset($register_success)): ?>
            <div class="success"><?php echo $register_success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Tên đăng nhập:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="login">Đăng nhập</button>
        </form>

        <div class="toggle-form">
            <a href="#" onclick="toggleForm()">Chưa có tài khoản? Đăng ký</a>
        </div>

    
        <div id="register-form" style="display: none;">
            <h2>Đăng ký</h2>
            
            <?php if (isset($register_error)): ?>
                <div class="error"><?php echo $register_error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Tên đăng nhập:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Mật khẩu:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="register">Đăng ký</button>
            </form>

            <div class="toggle-form">
                <a href="#" onclick="toggleForm()">Đã có tài khoản? Đăng nhập</a>
            </div>
        </div>
    </div>

    <script>
        function toggleForm() {
            const loginForm = document.querySelector('form');
            const registerForm = document.getElementById('register-form');
            
            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            }
        }
    </script>
</body>
</html>