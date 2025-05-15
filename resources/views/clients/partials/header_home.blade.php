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
                                <li><a href="{{ route('products.home') }}">home</a></li>
                                <li><a href="#">shop</a></li>
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
                                    <a href="{{ route('cart.cart') }}" id="cart-link">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span id="checkout_items" class="checkout_items">{{ $cartItemCount }}</span>
                                    </a>
                                </div>
                                <div class="user-dropdown">
                                    <a href="#" class="user-icon"><i class="fa fa-user" aria-hidden="true"></i></a>
                                    <div class="dropdown-menu">
                                        @if (Auth::check())
                                        <!-- Người dùng đã đăng nhập -->
                                        <span class="dropdown-item disabled">Xin chào, {{ Auth::user()->name }}</span>
                                        <a href="{{ route('showUser', ['id' => Auth::user()->id]) }}" class="dropdown-item">Cài đặt</a>
                                        <a href="{{ route('signOut') }}" class="dropdown-item" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Đăng xuất
                                        </a>
                                        <form id="logout-form" action="{{ route('signOut') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @else
                                        <!-- Người dùng chưa đăng nhập -->
                                        <a href="{{ route('login') }}" class="dropdown-item">Đăng nhập</a>
                                        <a href="{{ route('register') }}" class="dropdown-item">Đăng ký</a>
                                    @endif
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