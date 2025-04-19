<?php
$ammount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $items) {
        $ammount += $items['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>TechGear Shop</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="TechGear Shop Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"
        type="text/css">
    <!-- <link rel="stylesheet" type="text/css" href="public/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.css"> -->
    <link rel="stylesheet" type="text/css" href="../assets//plugins//OwlCarousel2-2.2.1/owl.carousel.css">

    <link rel="stylesheet" type="text/css" href="public/assets/styles/main_styles.css">
    <link rel="stylesheet" href="../assets/styles/main_styles.css">

</head>

<body>

    <header class="header trans_300">
        <!-- Main Navigation -->
        <div class="main_nav_container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <div class="logo_container">
                            <a href="#">tech<span>gear</span></a>
                        </div>
                        <nav class="navbar">
                            <ul class="navbar_menu">
                                <li><a href="#">home</a></li>
                                <li><a href="#">shop</a></li>
                                <li><a href="#">promotion</a></li>
                                <li><a href="#">pages</a></li>
                                <li><a href="#">blog</a></li>
                                <li><a href="contact.html">contact</a></li>
                            </ul>
                            <div class="navbar_user">
                                <div class="search-bar">
                                    <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." />
                                    <button id="searchButton"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                                <div class="checkout">
                                    <a href="../pages/cart.php">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span id="checkout_items" class="checkout_items"><?= $ammount ?></span>
                                    </a>
                                </div>
                                <div class="user-dropdown">
                                    <a href="#" class="user-icon"><i class="fa fa-user" aria-hidden="true"></i></a>
                                    <div class="dropdown-menu">
                                        <a href="public//pages/login.php" class="dropdown-item">Đăng nhập</a>
                                        <a href="../pages/logout.php" class="dropdown-item">Đăng xuất</a>
                                    </div>
                                </div>
                            </div>
                            <div class="hamburger_container">
                                <i class="fa fa-bars" aria-hidden="true"></i>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>

</html>