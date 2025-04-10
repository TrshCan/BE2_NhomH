<nav class="navbar navbar-expand-lg navbar-light bg-warning fixed-top">
    <div class="container">
        <a class="navbar-brand text-white font-weight-bold" href="#">Nhóm H</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item active">
                    <a class="nav-link font-weight-bold" href="index.php">
                        <i class="material-icons align-middle">home</i> Trang Chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="cart.php">
                        <i class="material-icons align-middle">shopping_cart</i> Giỏ Hàng
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                        session_start(); 
                        if (isset($_SESSION['email'])): 
                    ?>
                    <?php include 'config.php'; ?>
                        <a class="nav-link font-weight-bold" href="<?php echo $base_url;?>pages/logout.php">
                            <i class="material-icons align-middle">logout</i> Đăng Xuất
                        </a>
                    <?php else: ?>
                        <a class="nav-link font-weight-bold" href="../pages/login.php">
                            <i class="material-icons align-middle">account_circle</i> Đăng Nhập
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
