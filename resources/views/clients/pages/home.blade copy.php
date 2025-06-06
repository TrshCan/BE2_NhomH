<x-checkout-success-popup/>
<x-auth-failed-popup/>
@extends('layouts.clients_home');
@section('title','Trang chu')

@section('content')


<!-- Header -->

<!-- Bootstrap Modal if user  not logged in and try to add to cart -->
<!-- Login Required Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông báo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <p id="loginModalMessage">Vui lòng đăng nhập để tiếp tục.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>




<!-- Carousel -->
<div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
    <!-- Carousel Indicators -->
    <div class="carousel-indicators">
        @foreach ($carouselProducts as $index => $product)
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="{{ $index }}"
            class="{{ $index === 0 ? 'active' : '' }}"
            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
            aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>

    <!-- Carousel Slides -->
    <div class="carousel-inner">
        @foreach ($carouselProducts as $index => $product)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="position: relative;">
            <!-- Ảnh nền -->
            <div class="carousel-image-wrapper"
                style="position: relative; width: 100%; height: 800px; overflow: hidden;">
                <img src="{{ asset('assets/images/' . $product->image_url) }}"
                    class="img-fluid d-block w-100 h-100 object-fit-cover"
                    style="position: absolute; top: 0; left: 0;" alt="{{ $product->product_name }}">

                <!-- Lớp phủ tối -->
                <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                        background-color: rgba(0, 0, 0, 0.5); z-index: 1;"></div>
            </div>

            <!-- Nội dung -->
            <div class="container"
                style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 2;">
                <div class="row align-items-center h-100">
                    <div class="col text-white text-center">
                        <div class="main_slider_content" style="text-shadow: 0 0 8px rgba(0,0,0,0.6);">
                            <h6>{{ $product->category ?? 'Featured Product' }}</h6>
                            <h1>{{ $product->product_name }}</h1>
                            <p class="mt-2 fw-bold text-warning">{{ number_format($product->price) }} VNĐ</p>
                            <div class="red_button shop_now_button mt-3">
                                <a href="{{ url('public/pages/single.php?product_id=' . $product->id) }}"
                                    class="btn btn-danger text-uppercase">Shop now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Controls -->
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
        style="font-size: 2.5rem; text-shadow: 2px 2px 5px rgba(119, 111, 111, 0.2);">
        Thương Hiệu
    </h5>

    <br>

    <div class="container">
        <div class="row justify-content-center text-center g-4">
            @foreach($brands as $brand)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="{{ route('products.home', $brand->slug) }}" class="text-decoration-none">
                    <div class="brand-card">
                        <div class="image-circle mx-auto mb-2" style="
                                width: 120px;
                                height: 120px;
                                background-image: url('{{ asset('assets/images/' . $brand->logo_url) }}');
                                background-size: cover;
                                background-position: center;
                                border-radius: 50%;
                            "></div>
                        <div class="brand-name text-white fw-bold">{{ $brand->name }}</div>
                        <div class="text-muted small">{{ $brand->products_count }} sản phẩm</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>




        <!-- New Arrivals -->
        <div id="scrollToNewArrivals" class="new_arrivals py-5 bg-light">
            <div class="container">
            </div>
        </div>


        <!-- Danh sách danh mục -->
        <div class="row mb-4">
            <div class="col text-center">
                <div class="btn-group" role="group" aria-label="Category Filter">
                    <!-- All -->
                    <a href="{{ route('products.home') }}" class="btn btn-outline-primary category-button {{ !request()->query('category_id') ? 'active' : '' }}">All</a>

                    <!-- Lặp danh mục -->
                    @foreach ($categories as $category)
                    <a href="{{ route('products.home', ['category_id' => $category->category_id]) }}"
                        class="btn btn-outline-primary {{ request()->query('category_id') == $category->category_id ? 'active' : '' }}">
                        {{ $category->category_name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>





        <!-- Danh sách sản phẩm -->
        <div class="row g-4" id="product-grid">
            @foreach ($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm product-card position-relative">
                    <div class="position-relative">
                        <img src="{{ asset('assets/images/'.$product->image_url) }}" class="card-img-top" alt="{{ $product->product_name }}">
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
                    </div>
                    <div class="card-body d-flex flex-column text-center">
                        <h6 class="card-title mb-2">
                            <a href="{{ url('public/pages/single.php?product_id='.$product->id) }}" class="text-decoration-none text-dark">{{ $product->product_name }}</a>
                        </h6>
                        <p class="card-text text-muted mb-1">Giá gốc: <span class="text-decoration-line-through">{{ number_format($product->original_price) }} VNĐ</span></p>
                        <p class="card-text text-danger fw-bold mb-3">{{ number_format($product->price) }} VNĐ</p>
                        <a href="{{ route('cart.add', ['id' => $product->product_id]) }}" class="btn btn-primary mt-auto add-to-cart-btn">
                            Add to Cart
                        </a>


                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <br>
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-4') }} <!-- Để dùng kiểu Bootstrap 4 -->

        </div>
    </div>
</div>



<!-- Deal of the Week -->
<div class="deal_ofthe_week">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="deal_ofthe_week_img">
                    
                @if ($dealOfTheWeekProduct && $dealOfTheWeekProduct->image_url)
                        <img src="{{ asset('assets/images/' . $dealOfTheWeekProduct->image_url) }}" alt="Deal of the Week">
                    @else
                        <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="No Deal Available">
                    @endif

                </div>
            </div>
            <div class="col-lg-6 text-right deal_ofthe_week_col">
                <div class="deal_ofthe_week_content d-flex flex-column align-items-center float-right">
                    <div class="section_title">
                        <h2>Deal Of The Week</h2>
                    </div>
                    <ul class="timer">
                        <li class="d-inline-flex flex-column justify-content-center align-items-center">
                            <div id="day" class="timer_num">00</div>
                            <div class="timer_unit">Day</div>
                        </li>
                        <li class="d-inline-flex flex-column justify-content-center align-items-center">
                            <div id="hour" class="timer_num">00</div>
                            <div class="timer_unit">Hours</div>
                        </li>
                        <li class="d-inline-flex flex-column justify-content-center align-items-center">
                            <div id="minute" class="timer_num">00</div>
                            <div class="timer_unit">Mins</div>
                        </li>
                        <li class="d-inline-flex flex-column justify-content-center align-items-center">
                            <div id="second" class="timer_num">00</div>
                            <div class="timer_unit">Sec</div>
                        </li>
                    </ul>
                    <div class="red_button deal_ofthe_week_button"><a href="#">shop now</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm JavaScript để đếm ngược -->
<script>
    var dealEndTime = {
        {
            $dealEndTime - > timestamp
        }
    }; // Giới thiệu thời gian kết thúc deal từ PHP

    var countdown = setInterval(function() {
        var now = new Date().getTime();
        var distance = dealEndTime * 1000 - now; // Tính thời gian còn lại (ms)

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Hiển thị kết quả trong các phần tử trên trang
        document.getElementById("day").innerHTML = days;
        document.getElementById("hour").innerHTML = hours;
        document.getElementById("minute").innerHTML = minutes;
        document.getElementById("second").innerHTML = seconds;

        // Nếu thời gian còn lại là 0, dừng đếm ngược
        if (distance < 0) {
            clearInterval(countdown);
            document.getElementById("day").innerHTML = "00";
            document.getElementById("hour").innerHTML = "00";
            document.getElementById("minute").innerHTML = "00";
            document.getElementById("second").innerHTML = "00";
        }
    }, 1000); // Cập nhật mỗi giây
</script>




<!-- Best Sellers -->
<!-- Best Sellers -->
<div class="best_sellers py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col text-center">
                <h2 class="section_title">Best Sellers</h2>
            </div>
        </div>

        <div id="bestSellersCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#bestSellersCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row justify-content-center">
                        @foreach($bestSellers as $product1)
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="product-item keyboards">
                                <div class="product">
                                    <div class="product_image">
                                        <img src="{{ asset('assets/images/' . $product1->image_url) }}" class="img-fluid" alt="{{ $product1->product_name }}">
                                    </div>
                                    <div class="product_bubble product_bubble_left"><span>sale</span></div>
                                    <div class="product_bubble product_bubble_right sale_fire"><span>sale</span></div>
                                    <div class="product_info text-center">
                                        <h6 class="product_name mt-3">
                                            <a href="{{ route('products.home', $product1->product_id) }}">{{ $product1->product_name }}</a>
                                        </h6>
                                        <p class="card-text text-muted mb-1">
                                            Original Price: <span class="text-decoration-line-through">$89.99</span>
                                        </p>
                                        <div class="product_price">{{ number_format($product1->price, 0) }} VND</div>
                                    </div>
                                    <div class="add_to_cart_button text-center"><a href="{{ route('cart.add', ['id' => $product->product_id]) }}" class="btn btn-primary mt-auto add-to-cart-btn">
                                            Add to Cart
                                        </a>
                                    </div>
                                    <div class="favorite favorite_right"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

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
            @foreach($latestBlogs as $blog)
            <div class="col-md-6 col-lg-4">
                <div class="blog_item position-relative rounded overflow-hidden shadow">
                    <div class="blog_background"
                        style="background-image:url('{{ asset('storage/' . $blog->image_url) }}');">
                    </div>
                    <div class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
                        <h4 class="blog_title">{{ $blog->title }}</h4>
                        <span class="blog_meta">by {{ $blog->author }} | {{ \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') }}</span>
                        <a class="blog_more" href="{{ route('products.home', $blog->id) }}">Read more</a>
                    </div>
                </div>
            </div>
            @endforeach
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Add to Cart click
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
            })
            .then(async response => {
                const data = await response.json();
                if (response.status === 401 && data.modal) {
                    document.getElementById('loginModalMessage').textContent = data.message;
                    new bootstrap.Modal(document.getElementById('loginModal')).show();
                } else {
                    alert(data.message || 'Đã thêm vào giỏ hàng.');
                    // Optional: Update cart count here
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
            });
        });
    });

    // Intercept View Cart
    const cartLink = document.querySelector('#cart-link');
    if (cartLink) {
        cartLink.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                }
            })
            .then(async response => {
                if (response.status === 401) {
                    const data = await response.json();
                    document.getElementById('loginModalMessage').textContent = data.message;
                    new bootstrap.Modal(document.getElementById('loginModal')).show();
                } else {
                    window.location.href = url;
                }
            });
        });
    }
});
</script>



@endsection
