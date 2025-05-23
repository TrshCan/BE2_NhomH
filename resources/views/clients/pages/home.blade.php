<x-checkout-success-popup />
<x-auth-failed-popup />
@extends('layouts.clients_home')
@section('title', 'Trang chu')
@section('styles')
<link rel="stylesheet" href="{{ asset('assets/styles/livechat.css') }}">
@endsection
@section('content')
@include('hotline')
    <!-- Header -->

    <!-- Carousel -->
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            @foreach ($carouselProducts as $index => $product)
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="{{ $index }}"
                    class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>

        <!-- Carousel Slides -->
        <div class="carousel-inner">
            @foreach ($carouselProducts as $index => $product)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="position: relative;">
                    <div class="carousel-image-wrapper"
                        style="position: relative; width: 100%; height: 800px; overflow: hidden;">
                        <img src="{{ asset('assets/images/' . $product->image_url) }}"
                            class="img-fluid d-block w-100 h-100 object-fit-cover"
                            style="position: absolute; top: 0; left: 0;" alt="{{ $product->product_name }}">
                        <div class="overlay"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1;">
                        </div>
                    </div>
                    <div class="container"
                        style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 2;">
                        <div class="row align-items-center h-100">
                            <div class="col text-white text-center">
                                <div class="main_slider_content" style="text-shadow: 0 0 8px rgba(0,0,0,0.6);">
                                    <h6>Featured Product</h6>
                                    <h1>{{ $product->product_name }}</h1>
                                    <p class="mt-2 fw-bold text-warning">{{ number_format($product->price) }} VNĐ</p>
                                    <div class="red_button shop_now_button mt-3">
                                        <a href="{{ route('shop.show') }}" class="btn btn-danger text-uppercase">Shop
                                            now</a>
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
    <div class="banner py-5">
        <h5 class="text-center text-dark fw-bold"
            style="font-size: 2.5rem; text-shadow: 2px 2px 5px rgba(119, 111, 111, 0.2);">
            Thương Hiệu
        </h5>
        <br>
        <div class="container">
            <div class="row justify-content-center text-center g-4">
                @foreach ($brands as $brand)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('products.index', $brand->slug) }}" class="text-decoration-none">
                            <div class="brand-card">
                                <div class="image-circle mx-auto mb-2"
                                    style="width: 120px; height: 120px; background-image: url('{{ asset('assets/images/' . $brand->logo_url) }}'); background-size: cover; background-position: center; border-radius: 50%;">
                                </div>
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
            <div class="row mb-4">
                <div class="col text-center">
                    <h2 class="fw-bold">New Arrivals</h2>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col text-center">
                    <div class="btn-group" role="group" aria-label="Category Filter">
                        <a href="{{ route('products.home') }}"
                            class="btn btn-outline-primary category-button {{ !request()->query('category_id') ? 'active' : '' }}">All</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products.home', ['category_id' => $category->category_id]) }}"
                                class="btn btn-outline-primary {{ request()->query('category_id') == $category->category_id ? 'active' : '' }}">
                                {{ $category->category_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row g-4" id="product-grid">
                @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm product-card position-relative">
                            <div class="position-relative">
                                <img src="{{ asset('assets/images/' . $product->image_url) }}" class="card-img-top"
                                    alt="{{ $product->product_name }}">
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">-10$</span>
                            </div>
                            <div class="card-body d-flex flex-column text-center">
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('products.show', $product->product_id) }}"
                                        class="text-decoration-none text-dark">{{ $product->product_name }}</a>
                                </h6>
                                <div class="product-description mb-3">
                                    @if (strlen($product->description) > 100)
                                        <p class="card-text text-muted description-short">{!! Str::limit($product->description, 100, '...') !!}</p>
                                        <p class="card-text text-muted description-full d-none">{!! $product->description !!}</p>
                                        <a href="#" class="read-more btn btn-link p-0">Xem thêm</a>
                                    @else
                                        <p class="card-text text-muted">{!! $product->description !!}</p>
                                    @endif
                                </div>
                                <p class="card-text text-muted mb-1">Giá gốc: <span
                                        class="text-decoration-line-through">{{ number_format($product->original_price) }}
                                        VNĐ</span></p>
                                <p class="card-text text-danger fw-bold mb-3">{{ number_format($product->price) }} VNĐ</p>
                                <a href="{{ route('cart.add', ['id' => $product->product_id]) }}"
                                    class="btn btn-primary mt-auto add-to-cart-btn">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <br>
            <div class="d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Deal of the Week -->
    <div class="deal_ofthe_week py-5" style="background: #f5f7fa; border-radius: 15px;">
        <div class="container">
            @if ($dealOfTheWeekProduct)
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="deal_ofthe_week_img position-relative overflow-hidden rounded shadow-sm">
                            <img src="{{ asset('assets/images/' . $dealOfTheWeekProduct->image_url) }}"
                                class="img-fluid w-100 deal-img" alt="{{ $dealOfTheWeekProduct->product_name }}"
                                style="transition: transform 0.3s ease;">
                            <span class="badge bg-danger position-absolute top-0 end-0 m-3 fs-6 px-3 py-2">
                                Save
                                {{ number_format($dealOfTheWeekProduct->original_price - $dealOfTheWeekProduct->price) }}
                                VNĐ
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-6 deal_ofthe_week_col">
                        <div class="deal_ofthe_week_content d-flex flex-column align-items-center text-center">
                            <div class="section_title mb-4">
                                <h2 class="fw-bold text-dark text-uppercase"
                                    style="font-size: 2.5rem; letter-spacing: 2px;">
                                    Deal Of The Week
                                </h2>
                                <h4 class="text-dark fw-semibold mt-2">{{ $dealOfTheWeekProduct->product_name }}</h4>
                            </div>
                            <div class="product-info mb-4">
                                <p class="text-muted mb-2">
                                    {{ $dealOfTheWeekProduct->description ?? 'Tai nghe chất lượng cao với thời lượng pin 30 giờ.' }}
                                </p>
                                <p class="text-muted mb-1">Original Price: <span
                                        class="text-decoration-line-through">{{ number_format($dealOfTheWeekProduct->original_price) }}
                                        VNĐ</span></p>
                                <p class="text-danger fw-bold fs-3">{{ number_format($dealOfTheWeekProduct->price) }} VNĐ
                                </p>
                            </div>
                            <ul class="timer d-flex justify-content-center gap-3 mb-4">
                                <li class="timer-item bg-white shadow-sm rounded p-3 text-center">
                                    <div id="day" class="timer_num fw-bold fs-3 text-dark">00</div>
                                    <div class="timer_unit text-muted text-uppercase">Days</div>
                                </li>
                                <li class="timer-item bg-white shadow-sm rounded p-3 text-center">
                                    <div id="hour" class="timer_num fw-bold fs-3 text-dark">00</div>
                                    <div class="timer_unit text-muted text-uppercase">Hours</div>
                                </li>
                                <li class="timer-item bg-white shadow-sm rounded p-3 text-center">
                                    <div id="minute" class="timer_num fw-bold fs-3 text-dark">00</div>
                                    <div class="timer_unit text-muted text-uppercase">Mins</div>
                                </li>
                                <li class="timer-item bg-white shadow-sm rounded p-3 text-center">
                                    <div id="second" class="timer_num fw-bold fs-3 text-dark">00</div>
                                    <div class="timer_unit text-muted text-uppercase">Sec</div>
                                </li>
                            </ul>
                            <div class="d-flex gap-3">
                                <a href="{{ route('products.show', $dealOfTheWeekProduct->product_id) }}"
                                    class="btn btn-outline-secondary custom-view-details btn-lg text-uppercase">View
                                    Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="py-5 text-center">
                    <h4>Hiện chưa có Deal Of The Week nào.</h4>
                </div>
            @endif
        </div>
    </div>




    <!-- Best Sellers (Moved here for better flow) -->
    <div class="best_sellers py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col text-center">
                    <h2 class="section_title">Best Sellers</h2>
                </div>
            </div>
            <div id="bestSellersCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @php $slideCount = ceil(count($bestSellers) / 3); @endphp
                    @for ($i = 0; $i < $slideCount; $i++)
                        <button type="button" data-bs-target="#bestSellersCarousel"
                            data-bs-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"
                            aria-current="{{ $i == 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $i + 1 }}"></button>
                    @endfor
                </div>
                <div class="carousel-inner">
                    @php $chunkedProducts = array_chunk($bestSellers->toArray(), 3); @endphp
                    @foreach ($chunkedProducts as $index => $productGroup)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="row justify-content-center">
                                @foreach ($productGroup as $product)
                                    <div class="col-md-4 col-sm-6 mb-4">
                                        <div class="product-item keyboards">
                                            <div class="product">
                                                <div class="product_image">
                                                    <img src="{{ asset('assets/images/' . $product['image_url']) }}"
                                                        class="card-img-top" alt="{{ $product['product_name'] }}">
                                                </div>
                                                <div class="product_bubble product_bubble_left"><span>sale</span></div>
                                                <div class="product_bubble product_bubble_right sale_fire">
                                                    <span>sale</span>
                                                </div>
                                                <div class="product_info text-center">
                                                    <h6 class="product_name mt-3">
                                                        <a
                                                            href="{{ route('products.home', $product['product_id']) }}">{{ $product['product_name'] }}</a>
                                                    </h6>
                                                    <p class="card-text text-muted mb-1">
                                                        Original Price: <span
                                                            class="text-decoration-line-through">$89.99</span>
                                                    </p>
                                                    <div class="product_price">{{ number_format($product['price'], 0) }}
                                                        VND</div>
                                                </div>
                                                <div class="add_to_cart_button text-center"><a
                                                        href="{{ route('cart.add', ['id' => $product['product_id']]) }}">Add
                                                        to Cart</a></div>
                                                <div class="favorite favorite_right"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
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
    <!-- Blogs -->
    <section class="blogs py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col text-center">
                    <h2 class="section_title">Latest Blogs</h2>
                </div>
            </div>
            <div class="row blogs_container gx-4 gy-4">
                @foreach ($latestBlogs as $blog)
                    <div class="col-md-6 col-lg-4">
                        <div class="blog_item position-relative rounded overflow-hidden shadow">
                            <div class="blog_background"
                                style="background-image:url('{{ asset('assets/images/' . $blog->image_url) }}');"></div>
                            <div
                                class="blog_content d-flex flex-column align-items-center justify-content-center text-center rounded">
                                <h4 class="blog_title">{{ $blog->title }}</h4>
                                <span class="blog_meta">by {{ $blog->author }} |
                                    {{ \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') }}</span>
                                <a class="blog_more" href="{{ route('post.show', $blog->id) }}">Read more</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <div class="newsletter py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-lg-start text-center mb-4 mb-lg-0">
                    <h4 class="fw-bold">Newsletter</h4>
                    <p class="mb-0">Subscribe to our newsletter and get 15% off your first tech purchase</p>
                </div>
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
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
            <div id="newsletterToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">🎉 Đăng ký thành công! Cảm ơn bạn đã tham gia newsletter.</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>


    <style>
        .carousel-image-wrapper {
            min-height: 800px;
        }

        .overlay {
            z-index: 1;
        }

        .main_slider_content h6 {
            font-size: 1.2rem;
        }

        .main_slider_content h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .shop_now_button .btn {
            padding: 12px 30px;
            font-weight: bold;
        }

        .banner .image-circle {
            transition: transform 0.3s ease;
        }

        .banner .image-circle:hover {
            transform: scale(1.1);
        }

        .brand-name {
            font-size: 1.1rem;
        }

        .product-description {
            min-height: 60px;
        }

        .description-short,
        .description-full {
            margin-bottom: 0;
        }

        .read-more {
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        .deal_ofthe_week_img {
            transition: all 0.3s ease;
        }

        .deal_ofthe_week_img:hover .deal-img {
            transform: scale(1.05);
        }

        .timer-item {
            min-width: 80px;
            transition: transform 0.2s ease;
        }

        .timer-item:hover {
            transform: scale(1.05);
        }

        .custom-shop-now {
            background-color: #212529;
            color: #ffffff;
            border: none;
            padding: 12px 30px;
            font-weight: bold;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .custom-shop-now:hover {
            background-color: #343a40;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .custom-view-details {
            background-color: #ffffff;
            color: #6c757d;
            border: 2px solid #dee2e6;
            padding: 12px 30px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .custom-view-details:hover {
            background-color: #f8f9fa;
            color: #495057;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .main_slider_content h1 {
                font-size: 2rem;
            }

            .shop_now_button .btn,
            .custom-shop-now,
            .custom-view-details {
                padding: 10px 20px;
                font-size: 1rem;
            }

            .timer-item {
                min-width: 60px;
                padding: 10px;
            }

            .section_title h2 {
                font-size: 1.8rem;
            }

            .product-info .fs-3 {
                font-size: 1.5rem !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Read More/Read Less
            const readMoreLinks = document.querySelectorAll('.read-more');
            readMoreLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const card = this.closest('.card-body');
                    const shortDesc = card.querySelector('.description-short');
                    const fullDesc = card.querySelector('.description-full');
                    if (shortDesc.classList.contains('d-none')) {
                        shortDesc.classList.remove('d-none');
                        fullDesc.classList.add('d-none');
                        this.textContent = 'Xem thêm';
                    } else {
                        shortDesc.classList.add('d-none');
                        fullDesc.classList.remove('d-none');
                        this.textContent = 'Thu gọn';
                    }
                });
            });

            // Countdown Timer
            const now = new Date();
            const endTime = new Date(now);
            endTime.setDate(now.getDate() + 6);
            endTime.setHours(now.getHours() + 23);
            endTime.setMinutes(now.getMinutes() + 59);
            endTime.setSeconds(now.getSeconds() + 17);

            function updateTimer() {
                const currentTime = new Date();
                const timeLeft = endTime - currentTime;
                if (timeLeft <= 0) {
                    document.querySelectorAll('.timer_num').forEach(el => el.textContent = '00');
                    return;
                }
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                document.getElementById('day').textContent = String(days).padStart(2, '0');
                document.getElementById('hour').textContent = String(hours).padStart(2, '0');
                document.getElementById('minute').textContent = String(minutes).padStart(2, '0');
                document.getElementById('second').textContent = String(seconds).padStart(2, '0');
            }
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Add to Cart click
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function(e) {
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
                                document.getElementById('loginModalMessage').textContent =
                                    data.message;
                                new bootstrap.Modal(document.getElementById('loginModal'))
                                    .show();
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
                cartLink.addEventListener('click', function(e) {
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
                                document.getElementById('loginModalMessage').textContent = data
                                    .message;
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
