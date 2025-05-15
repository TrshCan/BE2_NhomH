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
                                     <form action="{{ route('products.home') }}" method="GET" class="search-bar">
                                         <input type="text" name="search" id="searchInput" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}" />
                                                 <button type="submit" id="searchButton">
                                                               <i class="fa fa-search" aria-hidden="true"></i>
                                                 </button>
                                    </form>
                                    <div class="checkout">
                                        <a href="public/pages/cart.php">
                                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                            <span id="checkout_items" class="checkout_items">0</span>
                                        </a>
                                    </div>
                                    <div class="user-dropdown">
                                        <a href="#" class="user-icon"><i class="fa fa-user" aria-hidden="true"></i></a>
                                        <div class="dropdown-menu">
                                            <a href="public//pages/login.php" class="dropdown-item">Đăng nhập</a>
                                            <a href="../Be2_NhomH2/public/pages/logout.php" class="dropdown-item">Đăng
                                                xuất</a>
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
        