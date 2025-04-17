<?php 
session_start();
$ammount = 0;
if(isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id=>$items) {
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
    <link rel="stylesheet" type="text/css" href="public/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.css">

    <link rel="stylesheet" type="text/css" href="public/assets/styles/main_styles.css">

</head>

<body>

    <div class="super_container">

        <!-- Header -->

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
                                        <button id="searchButton"><i class="fa fa-search"
                                                aria-hidden="true"></i></button>
                                    </div>
                                    <div class="checkout">
                                        <a href="#">
                                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                            <span id="checkout_items" class="checkout_items">2</span>
                                        </a>
                                    </div>
                                    <div class="user-dropdown">
                                        <a href="#" class="user-icon"><i class="fa fa-user" aria-hidden="true"></i></a>
                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item">Đăng nhập</a>
                                            <a href="#" class="dropdown-item">Đăng xuất</a>
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

        <div class="fs_menu_overlay"></div>
        <div class="hamburger_menu">
            <div class="hamburger_close"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="hamburger_menu_content text-right">
                <ul class="menu_top_nav">
                    <li class="menu_item"><a href="#">home</a></li>
                    <li class="menu_item"><a href="#">shop</a></li>
                    <li class="menu_item"><a href="#">promotion</a></li>
                    <li class="menu_item"><a href="#">pages</a></li>
                    <li class="menu_item"><a href="#">blog</a></li>
                    <li class="menu_item"><a href="#">contact</a></li>
                </ul>
            </div>
        </div>

        <!-- Carousel -->
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <?php
                require_once "public/includes/promotions_database.php";
                $promotionsdb = new Promotions_database();
                $promotions = $promotionsdb->getAllpromotions();
                $isFirst = true; // Flag to mark the first item
                foreach ($promotions as $value2) {
                ?>
                    <div class="carousel-item <?= $isFirst ? 'active' : '' ?>" style="position: relative;">
                        <!-- Ảnh nền -->
                        <div class="carousel-image-wrapper"
                            style="position: relative; width: 100%; height: 800px; overflow: hidden;">
                            <img src="public/assets/images/<?= $value2['image_url'] ?>"
                                class="img-fluid d-block w-100 h-100 object-fit-cover"
                                style="position: absolute; top: 0; left: 0;" alt="<?= $value2['title'] ?>">

                            <!-- Lớp phủ tối -->
                            <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
             background-color: rgba(0, 0, 0, 0.5); z-index: 1;"></div>
                        </div>

                        <!-- Nội dung chồng lên ảnh -->
                        <div class="container"
                            style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 2;">
                            <div class="row align-items-center h-100">
                                <div class="col text-white">
                                    <div class="main_slider_content text-center"
                                        style="text-shadow: 0 0 8px rgba(0,0,0,0.6);">
                                        <h6><?= $value2['title'] ?></h6>
                                        <h1><?= $value2['description'] ?></h1>
                                        <div class="red_button shop_now_button mt-3">
                                            <a href="index.php" class="btn btn-danger text-uppercase">Shop now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                <?php
                    $isFirst = false; // Set to false after the first item
                }
                ?>

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Banner -->


        <div class="banner py-5" style="background-color: #1c1c1c;">
            <h5 class="text-center text-dark fw-bold"
                style="font-size: 2.5rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.2);">Thương Hiệu</h5>

            <br>

            <div class="container">
                <div class="row justify-content-center text-center g-4">
                    <?php
                    require_once "public/includes/brand_database.php";
                    $branddb = new Brand_Database();
                    $brands = $branddb->getAllBrand();

                    foreach ($brands as $value1) {
                        $productCount = $branddb->getProductCountByBrand($value1['brand_id']);
                    ?>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="brand.php?brand_id=<?= $value1['brand_id'] ?>" class="text-decoration-none">
                                <div class="brand-card">
                                    <div class="image-circle mx-auto mb-2" style="
                            width: 120px;
                            height: 120px;
                            background-image: url('public/assets/images/<?= $value1['images_brand'] ?>');
                            background-size: cover;
                            background-position: center;
                            border-radius: 50%;
                        "></div>
                                    <div class="brand-name text-white fw-bold">
                                        <?= htmlspecialchars($value1['brand_name']) ?></div>
                                    <div class="text-muted small"><?= $productCount ?> sản phẩm</div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>



        <!-- New Arrivals -->
        <div id="scrollToNewArrivals" class="new_arrivals py-5 bg-light">
            <div class="container">
                <!-- Tiêu đề -->
                <div class="row mb-4">
                    <div class="col text-center">
                        <h2 class="fw-bold">New Arrivals</h2>
                    </div>
                </div>

                <!-- Danh sách danh mục -->
                <div class="row mb-4">
                    <div class="col text-center">
                        <div class="btn-group" role="group" aria-label="Category Filter">
                            <a href="index.php"
                                class="btn btn-outline-primary category-button <?= !isset($_GET['category_id']) ? 'active' : '' ?>">All</a>
                            <?php
                            require_once "public/includes/Category_Database.php";
                            $category_db = new Category_Database();
                            $category = $category_db->getAllCategory();
                            foreach ($category as $value) {
                                $activeClass = (isset($_GET['category_id']) && $_GET['category_id'] == $value['category_id']) ? 'active' : '';
                            ?>
                                <a href="index.php?category_id=<?= $value['category_id'] ?>"
                                    class="btn btn-outline-primary <?= $activeClass ?>"><?= $value['category_name'] ?></a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Danh sách sản phẩm -->
                <div class="row g-4" id="product-grid">
                    <?php
                    require_once "public/includes/Product_Database.php";
                    $productdb = new Product_Database();
                    $category_id = $_GET['category_id'] ?? null;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $per_page = 8; // Số sản phẩm mỗi trang
                    $offset = ($page - 1) * $per_page;
                    $total_products = (new Product_Database())->getTotalProducts($category_id);
                    $total_pages = ceil($total_products / $per_page);
                    if ($category_id) {
                        $products = $productdb->getProductsByCategoryPaged($category_id, $per_page, $offset);
                    } else {
                        $products = $productdb->getAllProductPaged($per_page, $offset);
                    }

                    foreach ($products as $item) {
                    ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm product-card position-relative">
                                <div class="position-relative">
                                    <img src="public/assets/images/<?= $item['image_url'] ?>" class="card-img-top"
                                        alt="<?= $item['product_name'] ?>">
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
                                </div>
                                <div class="card-body d-flex flex-column text-center">
                                    <h6 class="card-title mb-2">
                                        <a href="public//page/single.php"
                                            class="text-decoration-none text-dark"><?= $item['product_name'] ?></a>
                                    </h6>
                                    <p class="card-text text-muted mb-1">Giá gốc: <span
                                            class="text-decoration-line-through">$89.99</span></p>
                                    <p class="card-text text-danger fw-bold mb-3"><?= number_format($item['price'], 0, 0) ?>
                                        VNĐ</p>
                                    <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <br>
                <div class="pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&category_id=<?= $category_id ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&category_id=<?= $category_id ?>"><?= $i ?></a>
                            </li>
                        <?php } ?>
                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&category_id=<?= $category_id ?>"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>



        <!-- Deal of the Week -->

        <div class="deal_ofthe_week">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="deal_ofthe_week_img">
                            <img src="public/assets/images/keybord_1.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 text-right deal_ofthe_week_col">
                        <div class="deal_ofthe_week_content d-flex flex-column align-items-center float-right">
                            <div class="section_title">
                                <h2>Deal Of The Week</h2>
                            </div>
                            <ul class="timer">
                                <li class="d-inline-flex flex-column justify-content-center align-items-center">
                                    <div id="day" class="timer_num">03</div>
                                    <div class="timer_unit">Day</div>
                                </li>
                                <li class="d-inline-flex flex-column justify-content-center align-items-center">
                                    <div id="hour" class="timer_num">15</div>
                                    <div class="timer_unit">Hours</div>
                                </li>
                                <li class="d-inline-flex flex-column justify-content-center align-items-center">
                                    <div id="minute" class="timer_num">45</div>
                                    <div class="timer_unit">Mins</div>
                                </li>
                                <li class="d-inline-flex flex-column justify-content-center align-items-center">
                                    <div id="second" class="timer_num">23</div>
                                    <div class="timer_unit">Sec</div>
                                </li>
                            </ul>
                            <div class="red_button deal_ofthe_week_button"><a href="#">shop now</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="best_sellers py-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col text-center">
                        <h2 class="section_title">Best Sellers</h2>
                    </div>
                </div>

                <div id="bestSellersCarousel" class="carousel slide" data-bs-ride="carousel">
                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="3"
                            aria-label="Slide 4"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-inner">
                            <?php
                            $bestSeller = $productdb->getBestSellingProducts(12); // lấy tối đa 12 sp
                            $chunkedProducts = array_chunk($bestSeller, 3); // chia mảng thành từng nhóm 3 sp
                            $isFirst = true;

                            foreach ($chunkedProducts as $productGroup) {
                            ?>
                                <div class="carousel-item <?php echo $isFirst ? 'active' : ''; ?>">
                                    <div class="row justify-content-center">
                                        <?php foreach ($productGroup as $value3) { ?>
                                            <div class="col-md-4 col-sm-6 mb-4">
                                                <div class="product-item keyboards">
                                                    <div class="product">
                                                        <div class="product_image">
                                                            <img src="public/assets/images/<?= $value3['image_url']; ?>"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                        <div class="product_bubble product_bubble_left"><span>sale</span></div>
                                                        <div class="product_bubble product_bubble_right sale_fire">
                                                            <span>sale</span>
                                                        </div>
                                                        <div class="product_info text-center">
                                                            <h6 class="product_name mt-3"><a
                                                                    href="single.html"><?= $value3['product_name']; ?></a>
                                                            </h6>
                                                            <p class="card-text text-muted mb-1">Giá gốc: <span
                                                                    class="text-decoration-line-through">$89.99</span></p>
                                                            <div class="product_price">
                                                                <?= number_format($value3['price'], 0, 0) ?> VND</div>
                                                        </div>
                                                        <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a>
                                                        </div>
                                                        <div class="favorite favorite_right"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php
                                $isFirst = false;
                            }
                            ?>
                        </div>

                    </div>

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#bestSellersCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bestSellersCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Benefit -->

        <div class="benefit">
            <div class="container">
                <div class="row benefit_row">
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center">
                            <div class="benefit_icon"><i class="fa fa-truck" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>free shipping</h6>
                                <p>Free Shipping on Orders Over $50</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center">
                            <div class="benefit_icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>cash on delivery</h6>
                                <p>Pay After Receiving Your Order</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center">
                            <div class="benefit_icon"><i class="fa fa-undo" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>30 days return</h6>
                                <p>Easy Returns Within 30 Days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center">
                            <div class="benefit_icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>support 24/7</h6>
                                <p>Customer Support Anytime</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blogs -->

        <section class="blogs py-5 bg-light">
            <div class="container">
                <div class="row mb-5">
                    <div class="col text-center">
                        <h2 class="section_title">Latest Blogs</h2>
                    </div>
                </div>

                <div class="row blogs_container gx-4 gy-4">
                    <!-- Blog Item -->
                    <div class="col-md-6 col-lg-4">
                        <div class="blog_item position-relative rounded overflow-hidden shadow">
                            <div class="blog_background"
                                style="background-image:url(public/assets/images/bluetoothheadset_1.png);"></div>
                            <div
                                class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
                                <h4 class="blog_title">Top 5 Wireless Headsets for 2025</h4>
                                <span class="blog_meta">by admin | Mar 19, 2025</span>
                                <a class="blog_more" href="#">Read more</a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Item 2 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="blog_item position-relative rounded overflow-hidden shadow">
                            <div class="blog_background"
                                style="background-image:url(public/assets/images/keybord_2.png);"></div>
                            <div
                                class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
                                <h4 class="blog_title">How to Choose the Best Mechanical Keyboard</h4>
                                <span class="blog_meta">by admin | Mar 19, 2025</span>
                                <a class="blog_more" href="#">Read more</a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Item 3 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="blog_item position-relative rounded overflow-hidden shadow">
                            <div class="blog_background"
                                style="background-image:url(public/assets/images/laptop_3.png);"></div>
                            <div
                                class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
                                <h4 class="blog_title">Best Laptops for Gaming in 2025</h4>
                                <span class="blog_meta">by admin | Mar 19, 2025</span>
                                <a class="blog_more" href="#">Read more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>





        <!-- Newsletter -->

        <!-- Newsletter Section -->
        <div class="newsletter py-5 bg-light">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Text -->
                    <div class="col-lg-6 text-lg-start text-center mb-4 mb-lg-0">
                        <h4 class="fw-bold">Newsletter</h4>
                        <p class="mb-0">Subscribe to our newsletter and get 15% off your first tech purchase</p>
                    </div>

                    <!-- Form -->
                    <div class="col-lg-6">
                        <form id="newsletterForm">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your email" required>
                                <button class="btn btn-primary" type="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Toast Notification -->
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
                <div id="newsletterToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            🎉 Đăng ký thành công! Cảm ơn bạn đã tham gia newsletter.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Footer -->

        <footer class="footer py-5 bg-light border-top">
            <div class="container">
                <div class="row gy-4 align-items-center">

                    <!-- Left side -->
                    <div class="col-lg-6 text-center text-lg-start">
                        <ul class="nav justify-content-center justify-content-lg-start">
                            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Blog</a></li>
                            <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
                            <li class="nav-item"><a href="contact.html" class="nav-link px-2 text-muted">Contact us</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Right side -->
                    <div class="col-lg-6 text-center text-lg-end">
                        <div class="d-flex justify-content-center justify-content-lg-end gap-3">
                            <a href="#" class="text-muted"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-skype"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-pinterest-p"></i></a>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <div class="text-center text-muted small">
                    ©2025 All Rights Reserved. Made with <i class="fas fa-heart text-danger"></i> by <a href="#"
                        class="text-decoration-none">TechGear Team</a>
                </div>
            </div>
        </footer>


    </div>
    <!-- Scripts -->
    <script src="public/assets/js/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="public/assets/plugins/Isotope/isotope.pkgd.min.js"></script>
    <script src="public/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
    <script src="public/assets/plugins/easing/easing.js"></script>
    <script src="public/assets/js/custom.js"></script>



</body>

</html>