
<!DOCTYPE html>
<html lang="en">
<head>
	<title>TechGear Shop</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="TechGear Shop Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="public/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
  
    <link rel="stylesheet" type="text/css" href="public/assets/styles/main_styles.css">
    <link rel="stylesheet" type="text/css" href="public/assets/styles/responsive.css">
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
								  <input
									type="text"
									id="searchInput"
									placeholder="Tìm kiếm sản phẩm..."
								  />
								  <button id="searchButton"><i class="fa fa-search" aria-hidden="true"></i></button>
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
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <!-- Slide 1 -->
		<div class="carousel-item active" style="background-image: url(public/assets/images/oneplus-keyboard.jpg);">
			<div class="container h-100">
				<div class="row align-items-center h-100">
					<div class="col">
						<div class="main_slider_content text-white">
							<h6>Tech Accessories 2025</h6>
							<h1>Up to 25% Off on Keyboards & Headsets</h1>
							<div class="red_button shop_now_button"><a href="#" class="text-white">shop now</a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <!-- Slide 2 (Ví dụ thêm) -->
        <div class="carousel-item" style="background-image: url(public/assets/images/chair.png);">
            <div class="container h-100">
                <div class="row align-items-center h-100">
                    <div class="col">
                        <div class="main_slider_content text-white">
                            <h6>New Arrivals 2025</h6>
                            <h1>Explore Top Headsets</h1>
                            <div class="red_button shop_now_button"><a href="#" class="text-white">shop now</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slide 3 (Ví dụ thêm) -->
        <div class="carousel-item" style="background-image: url(public/assets/images/AK_600.jpg);">
            <div class="container h-100">
                <div class="row align-items-center h-100">
                    <div class="col">
                        <div class="main_slider_content text-white">
                            <h6>Gaming Laptops</h6>
                            <h1>Save Big This Season</h1>
                            <div class="red_button shop_now_button"><a href="#" class="text-white">shop now</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

	<div class="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="banner_item align-items-center" style="background-image:url(public/assets/images/bluetoothheadset.png)">
						<div class="banner_category">
							<a href="categories.html">Headsets</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="banner_item align-items-center" style="background-image:url(public/assets/images/keybord_1.png)">
						<div class="banner_category">
							<a href="categories.html">Keyboards</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="banner_item align-items-center" style="background-image:url(public/assets/images/laptop.png)">
						<div class="banner_category">
							<a href="categories.html">Laptops</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- New Arrivals -->

	<div class="new_arrivals py-5 bg-light">
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
                    <a href="index.php" class="btn btn-outline-primary category-button active">All</a>
                    
                        <a href="index.php?category_id=1" class="btn btn-outline-primary ">Điện thoại</a>
                                                <a href="index.php?category_id=2" class="btn btn-outline-primary ">Laptop</a>
                                                <a href="index.php?category_id=3" class="btn btn-outline-primary ">Phụ kiện</a>
                                        </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="row g-4">
                         <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/iphone15.jpg" class="card-img-top" alt="iPhone 15">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">iPhone 15</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">25,000,000 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/s23.jpg" class="card-img-top" alt="Galaxy S23">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Galaxy S23</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">20,000,000 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/xps13.jpg" class="card-img-top" alt="Dell XPS 13">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Dell XPS 13</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">30,000,000 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/airpods.jpg" class="card-img-top" alt="Tai nghe Airpods">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Tai nghe Airpods</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">5,000,000 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/AK_600.jpg" class="card-img-top" alt="AK-600 Gaming Keyboard">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">AK-600 Gaming Keyboard</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">90 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/bluetoothheadset_1.png" class="card-img-top" alt="Bluetooth Headset Blue">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Bluetooth Headset Blue</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">50 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/bluetoothheadset_2.png" class="card-img-top" alt="Bluetooth Headset Red">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Bluetooth Headset Red</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">60 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/camera.png" class="card-img-top" alt="Professional Camera">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Professional Camera</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">800 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/camera_1.png" class="card-img-top" alt="Camera with Zoom Lens">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Camera with Zoom Lens</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">900 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/chair.png" class="card-img-top" alt="Ergonomic Office Chair">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Ergonomic Office Chair</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">130 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/chair_1.png" class="card-img-top" alt="Blue Office Chair">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Blue Office Chair</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">150 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/drone.png" class="card-img-top" alt="Aerial Drone">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Aerial Drone</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">500 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/drone_1.png" class="card-img-top" alt="White Drone">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">White Drone</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">400 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/jacket.png" class="card-img-top" alt="White Jacket">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">White Jacket</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">80 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/jacket_1.png" class="card-img-top" alt="Brown Leather Jacket">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Brown Leather Jacket</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">200 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/keyboard.png" class="card-img-top" alt="RGB Gaming Keyboard">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">RGB Gaming Keyboard</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">100 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/keyboard_1.png" class="card-img-top" alt="RGB Keyboard with Mouse">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">RGB Keyboard with Mouse</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">120 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/laptop.png" class="card-img-top" alt="Gaming Laptop">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Gaming Laptop</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">1,500 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/laptop_1.png" class="card-img-top" alt="Sleek Blue Laptop">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Sleek Blue Laptop</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">1,000 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/mobile.png" class="card-img-top" alt="Smartphone Blue">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Smartphone Blue</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">700 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/mobile_1.png" class="card-img-top" alt="Smartphone Purple">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Smartphone Purple</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">800 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/shoes.png" class="card-img-top" alt="Running Shoes Blue">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Running Shoes Blue</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">90 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/shoes_1.png" class="card-img-top" alt="Yellow Sneakers">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Yellow Sneakers</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">100 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

                             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm product-card position-relative">
        <div class="position-relative">
            <img src="public/assets/images/stand.png" class="card-img-top" alt="Phone Stand">
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
        </div>
        <div class="card-body d-flex flex-column text-center">
            <h6 class="card-title mb-2">
                <a href="single.html" class="text-decoration-none text-dark">Phone Stand</a>
            </h6>
            <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">$89.99</span></p>
            <p class="card-text text-danger fw-bold mb-3">20 VNĐ</p>
            <a href="#" class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
            <div class="favorite favorite_right"></div> <!-- Thêm nút yêu thích -->
        </div>
    </div>
</div>

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
                <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="row justify-content-center">
                    <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item active">
                    <div class="row justify-content-center">
                    <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item active">
                    <div class="row justify-content-center">
                    <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="carousel-item active">
                    <div class="row justify-content-center">
                    <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="public/assets/images/keybord.png" class="img-fluid" alt="">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>new</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3"><a href="single.html">Razer BlackWidow V4 Pro Mechanical Keyboard</a></h6>
                                        <div class="product_price">$169.99</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="#">Add to Cart</a></div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="next">
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
          <div class="blog_background" style="background-image:url(public/assets/images/bluetoothheadset_1.png);"></div>
          <div class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
            <h4 class="blog_title">Top 5 Wireless Headsets for 2025</h4>
            <span class="blog_meta">by admin | Mar 19, 2025</span>
            <a class="blog_more" href="#">Read more</a>
          </div>
        </div>
      </div>

      <!-- Blog Item 2 -->
      <div class="col-md-6 col-lg-4">
        <div class="blog_item position-relative rounded overflow-hidden shadow">
          <div class="blog_background" style="background-image:url(public/assets/images/keybord_2.png);"></div>
          <div class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
            <h4 class="blog_title">How to Choose the Best Mechanical Keyboard</h4>
            <span class="blog_meta">by admin | Mar 19, 2025</span>
            <a class="blog_more" href="#">Read more</a>
          </div>
        </div>
      </div>

      <!-- Blog Item 3 -->
      <div class="col-md-6 col-lg-4">
        <div class="blog_item position-relative rounded overflow-hidden shadow">
          <div class="blog_background" style="background-image:url(public/assets/images/laptop_3.png);"></div>
          <div class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
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
        <div id="newsletterToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    🎉 Đăng ký thành công! Cảm ơn bạn đã tham gia newsletter.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
          <li class="nav-item"><a href="contact.html" class="nav-link px-2 text-muted">Contact us</a></li>
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
      ©2025 All Rights Reserved. Made with <i class="fas fa-heart text-danger"></i> by <a href="#" class="text-decoration-none">TechGear Team</a>
    </div>
  </div>
</footer>


</div>
<!-- Scripts -->
<script src="public/assets/js/jquery-3.2.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="public/assets/plugins/Isotope/isotope.pkgd.min.js"></script>
<script src="public/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="public/assets/plugins/easing/easing.js"></script>
<script src="public/assets/js/custom.js"></script>

</body>

</html>