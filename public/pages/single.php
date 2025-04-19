<!DOCTYPE html>
<html lang="en">

<head>
    <title>Single Product - TechGear Shop</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="TechGear Shop Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.2 for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"
        type="text/css">
    <!-- Google Fonts for Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../assets/styles/single_styles.css?v=2">
    <link rel="stylesheet" type="text/css" href="../assets/styles/main_styles.css">
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

        <!-- Main Content -->
        <div class="container single_product_container py-5">
            <!-- Breadcrumbs -->
            <div class="row">
                <div class="col">
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="../../index.php">Home</a></li>
                            <li><a href="categories.html"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                    Headsets</a></li>
                            <li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> JBL
                                    Quantum 400 Gaming Headset</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="row">
                <?php
                require_once "../includes/Product_Database.php";
                if (isset($_GET["product_id"])) {
                    $productdb = new Product_Database();
                    $product_id  = $_GET["product_id"];
                    $product = $productdb->getProductById($product_id);
                    foreach ($product as $items) {
                ?>
                        <!-- Product Images -->
                        <div class="col-lg-7">
                            <div class="single_product_pics">
                                <div class="row">
                                    <!-- Thumbnails (Vertical) -->
                                    <div class="col-lg-2 thumbnails_col">
                                        <div class="single_product_thumbnails">
                                            <ul class="list-unstyled">
                                                <li><img src="../assets/images/bluetoothheadset_1.png" alt=""
                                                        data-image="../assets/images/bluetoothheadset_1.png"></li>
                                                <li><img src="../assets/images/bluetoothheadset_2.png" alt="" class="active"
                                                        data-image="../assets/images/bluetoothheadset_2.png"></li>
                                                <li><img src="../assets/images/bluetoothheadset_3.png" alt=""
                                                        data-image="../assets/images/bluetoothheadset_3.png"></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Main Image -->
                                    <div class="col-lg-10 image_col">
                                        <div class="single_product_image"
                                            style="background-image: url('../assets/images/<?= $items['image_url'] ?>');"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Info -->
                        <div class="col-lg-5">
                            <div class="product_details">
                                <div class="product_details_title">
                                    <h2><?= $items["product_name"] ?></h2>

                                </div>
                                <div class="description-container my-3">
                                    <p class="description-text text-muted">
                                        <?= $items["description"] ?>
                                    </p>
                                    <button class="btn btn-link p-0 description-toggle" style="font-size: 0.9rem;">Xem
                                        thêm</button>
                                </div>
                                <div class="free_delivery d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-truck"></i><span>Free Delivery</span>
                                </div>
                                <div class="original_price">$99.99</div>
                                <div class="product_price"> <?= number_format($items['price'], 0, '.', '.') ?> VNĐ</div>
                                <ul class="star_rating list-unstyled d-flex gap-1">
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                                </ul>

                                <div class="quantity d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                                    <span>Quantity:</span>
                                    <div class="quantity_selector">
                                        <span class="minus"><i class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span id="quantity_value">1</span>
                                        <span class="plus"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                    </div>
                                    <a href="#" class="add_to_cart_button">Add to Cart</a>
                                </div>
                            </div>
                        </div>
            </div>
    <?php
                    }
                }
    ?>

        </div>

        <!-- Tabs Section -->
        <div class="tabs_section_container py-5">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <!-- Tabs Navigation -->
                        <div class="tabs_container">
                            <ul class="tabs">
                                <li class="tab active" data-active-tab="tab_1"><span>Description</span></li>
                                <li class="tab" data-active-tab="tab_2"><span>Additional Information</span></li>
                                <li class="tab" data-active-tab="tab_3"><span>Reviews (2)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <!-- Tab Description -->
                        <div id="tab_1" class="tab_container active">
                            <div class="row">
                                <div class="col-lg-5 desc_col">
                                    <div class="tab_title">
                                        <h4>Description</h4>
                                    </div>
                                    <div class="tab_text_block">
                                        <h2>JBL Quantum 400 Gaming Headset</h2>
                                        <p>The JBL Quantum 400 Gaming Headset delivers an immersive audio experience
                                            with JBL QuantumSURROUND sound technology. Designed for gamers, it features
                                            a lightweight build, memory foam ear cushions, and a detachable boom
                                            microphone for clear communication.</p>
                                    </div>
                                    <div class="tab_image">
                                        <img src="images/bluetoothheadset_1.png" alt="">
                                    </div>
                                    <div class="tab_text_block">
                                        <h2>Enhanced Gaming Experience</h2>
                                        <p>With customizable RGB lighting and compatibility with PC, PS4, Xbox, and
                                            other platforms, this headset ensures you stay ahead in every game. The 50mm
                                            drivers provide powerful bass and crisp highs for an unparalleled audio
                                            experience.</p>
                                    </div>
                                </div>
                                <div class="col-lg-5 offset-lg-2 desc_col">
                                    <div class="tab_image">
                                        <img src="images/bluetoothheadset_2.png" alt="">
                                    </div>
                                    <div class="tab_text_block">
                                        <h2>Comfort and Durability</h2>
                                        <p>Built for long gaming sessions, the JBL Quantum 400 features a durable
                                            headband and breathable materials to keep you comfortable. The detachable
                                            microphone ensures versatility for both gaming and casual use.</p>
                                    </div>
                                    <div class="tab_image desc_last">
                                        <img src="images/bluetoothheadset_3.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Additional Info -->
                        <div id="tab_2" class="tab_container">
                            <div class="row">
                                <div class="col additional_info_col">
                                    <div class="tab_title additional_info_title">
                                        <h4>Additional Information</h4>
                                    </div>
                                    <p>MODEL: <span>Black, Red, White</span></p>
                                    <p>CONNECTIVITY: <span>USB, 3.5mm Jack</span></p>
                                    <p>COMPATIBILITY: <span>PC, PS4, Xbox, Mobile</span></p>
                                    <p>WEIGHT: <span>346g</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Reviews -->
                        <div id="tab_3" class="tab_container">
                            <div class="row">
                                <!-- User Reviews -->
                                <div class="col-lg-6 reviews_col">
                                    <div class="tab_title reviews_title">
                                        <h4>Reviews (2)</h4>
                                    </div>
                                    <!-- User Review -->
                                    <div class="user_review_container d-flex flex-column flex-sm-row gap-3 mb-4">
                                        <div class="user">
                                            <div class="user_pic"></div>
                                            <div class="user_rating">
                                                <ul class="star_rating list-unstyled d-flex gap-1">
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="review">
                                            <div class="review_date">15 Mar 2025</div>
                                            <div class="user_name">Alex Gamer</div>
                                            <p>The sound quality is amazing for the price! The surround sound really
                                                enhances my gaming experience, though the microphone could be a bit
                                                clearer.</p>
                                        </div>
                                    </div>
                                    <!-- User Review -->
                                    <div class="user_review_container d-flex flex-column flex-sm-row gap-3">
                                        <div class="user">
                                            <div class="user_pic"></div>
                                            <div class="user_rating">
                                                <ul class="star_rating list-unstyled d-flex gap-1">
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="review">
                                            <div class="review_date">10 Mar 2025</div>
                                            <div class="user_name">Sarah Tech</div>
                                            <p>Very comfortable for long gaming sessions. The RGB lighting is a nice
                                                touch, and the sound quality is top-notch for this price range.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Review -->
                                <div class="col-lg-6 add_review_col">
                                    <div class="add_review">
                                        <form id="review_form" action="post">
                                            <div>
                                                <h1>Add Review</h1>
                                                <input id="review_name" class="form_input input_name" type="text"
                                                    name="name" placeholder="Name*" required="required"
                                                    data-error="Name is required.">
                                                <input id="review_email" class="form_input input_email" type="email"
                                                    name="email" placeholder="Email*" required="required"
                                                    data-error="Valid email is required.">
                                            </div>
                                            <div>
                                                <h1>Your Rating:</h1>
                                                <ul class="user_star_rating list-unstyled d-flex gap-1">
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star-o" aria-hidden="true"></i></li>
                                                </ul>
                                                <textarea id="review_message" class="input_review" name="message"
                                                    placeholder="Your Review" rows="4" required
                                                    data-error="Please, leave us a review."></textarea>
                                            </div>
                                            <div class="text-left text-sm-right">
                                                <button id="review_submit" type="submit"
                                                    class="red_button review_submit_btn" value="Submit">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefit -->
        <div class="benefit py-5">
            <div class="container">
                <div class="row benefit_row g-4">
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center gap-3">
                            <div class="benefit_icon"><i class="fa fa-truck" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>Free Shipping</h6>
                                <p>Free Shipping on Orders Over $50</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center gap-3">
                            <div class="benefit_icon"><i class="fa fa-money-bill" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>Cash on Delivery</h6>
                                <p>Pay After Receiving Your Order</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center gap-3">
                            <div class="benefit_icon"><i class="fa fa-undo" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>30 Days Return</h6>
                                <p>Easy Returns Within 30 Days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 benefit_col">
                        <div class="benefit_item d-flex flex-row align-items-center gap-3">
                            <div class="benefit_icon"><i class="fa fa-clock" aria-hidden="true"></i></div>
                            <div class="benefit_content">
                                <h6>Support 24/7</h6>
                                <p>Customer Support Anytime</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="newsletter">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-lg-start text-center mb-4 mb-lg-0">
                        <div class="newsletter_text">
                            <h4>Newsletter</h4>
                            <p>Subscribe to our newsletter and get 15% off your first tech purchase</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form action="post">
                            <div
                                class="newsletter_form d-flex flex-md-row flex-column align-items-center justify-content-lg-end justify-content-center gap-2">
                                <input id="newsletter_email" type="email" class="form-control" placeholder="Your email"
                                    required="required" data-error="Valid email is required.">
                                <button id="newsletter_submit" type="submit" class="newsletter_submit_btn"
                                    value="Submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-center text-lg-start">
                        <ul
                            class="footer_nav list-unstyled d-flex gap-3 justify-content-center justify-content-lg-start">
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">FAQs</a></li>
                            <li><a href="contact.html">Contact us</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-center text-lg-end">
                        <ul
                            class="footer_social list-unstyled d-flex gap-3 justify-content-center justify-content-lg-end">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fab fa-skype"></i></a></li>
                            <li><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cr mt-4">©2025 All Rights Reserved. Made with <i class="fa fa-heart text-danger"
                                aria-hidden="true"></i> by <a href="#">TechGear Team</a></div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3.3 JS (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- Custom JavaScript -->
    <script>
        // Hamburger Menu Toggle
        document.querySelector('.hamburger_container').addEventListener('click', function() {
            document.querySelector('.hamburger_menu').classList.add('active');
            document.querySelector('.fs_menu_overlay').classList.add('active');
        });

        document.querySelector('.hamburger_close').addEventListener('click', function() {
            document.querySelector('.hamburger_menu').classList.remove('active');
            document.querySelector('.fs_menu_overlay').classList.remove('active');
        });

        document.querySelector('.fs_menu_overlay').addEventListener('click', function() {
            document.querySelector('.hamburger_menu').classList.remove('active');
            document.querySelector('.fs_menu_overlay').classList.remove('active');
        });

        // Thumbnail Image Switch
        const thumbnails = document.querySelectorAll('.single_product_thumbnails img');
        const mainImage = document.querySelector('.single_product_image');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const newImage = this.getAttribute('data-image');
                mainImage.style.backgroundImage = `url('${newImage}')`;
            });
        });

        // Color Selection
        const colorOptions = document.querySelectorAll('.product_color li');
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                colorOptions.forEach(o => o.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Quantity Selector
        const quantityValue = document.getElementById('quantity_value');
        const minusBtn = document.querySelector('.quantity_selector .minus');
        const plusBtn = document.querySelector('.quantity_selector .plus');

        minusBtn.addEventListener('click', function() {
            let value = parseInt(quantityValue.textContent);
            if (value > 1) {
                quantityValue.textContent = value - 1;
            }
        });

        plusBtn.addEventListener('click', function() {
            let value = parseInt(quantityValue.textContent);
            quantityValue.textContent = value + 1;
        });

        // Tabs Functionality
        const tabs = document.querySelectorAll('.tabs .tab');
        const tabContents = document.querySelectorAll('.tab_container');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-active-tab');

                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.description-toggle');

            toggles.forEach(button => {
                const desc = button.previousElementSibling;

                // Hide button if content is short
                if (desc.scrollHeight <= desc.clientHeight) {
                    button.style.display = 'none';
                }

                button.addEventListener('click', function() {
                    const isExpanded = desc.classList.contains('expanded');
                    desc.classList.toggle('expanded');
                    this.textContent = isExpanded ? 'Xem thêm' : 'Thu gọn';
                });
            });
        });
    </script>



</body>

</html>