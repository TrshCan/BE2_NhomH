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
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-6 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Quên Mật Khẩu</h4>
                    </div>
                    <div class="card-body">
                        <form action="/reset-password" method="POST">
                            <!-- Email nhập vào để nhận link reset mật khẩu -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="Nhập email đã đăng ký">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Gửi Liên Kết Reset Mật Khẩu</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="login.php" class="text-decoration-none">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liên kết đến JS của Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0fEzG1uhq1lKgg0v4ONyS5w/s19sq3lXI+q8lLmrM8ZBy9ZT" crossorigin="anonymous"></script>
</body>
</html>
