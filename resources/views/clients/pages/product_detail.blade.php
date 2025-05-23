@extends('layouts.client')

@section('title', $product->product_name . ' - TechGear Shop')

@section('content')

<div class="container single_product_container py-5">
    <!-- Breadcrumbs -->
    <div class="row">
        <div class="col">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/categories/headsets') }}"><i class="fa fa-angle-right" aria-hidden="true"></i> Headsets</a></li>
                    <li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> {{ $product->product_name }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-7">
            <div class="single_product_pics">
                <div class="row">
                    <!-- Thumbnails (Vertical) -->
                    <div class="col-lg-2 thumbnails_col">
                        <div class="single_product_thumbnails">
                            <ul class="list-unstyled">
                                @foreach($product->images as $index => $image)
                                <li>
                                    <img src="{{ asset('assets/images/' . $image->image_url) }}" alt="" class="{{ $index === 0 ? 'active' : '' }}" data-image="{{ asset('assets/images/' . $image->image_url) }}">
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Main Image -->
                    <div class="col-lg-10 image_col">
                        <div class="single_product_image" style="background-image: url('{{ asset('assets/images/' . $product->image_url) }}');"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Info -->
        <div class="col-lg-5">
            <div class="product_details">
                <div class="product_details_title">
                    <h2>{{ $product->product_name }}</h2>
                </div>
                <div class="description-container my-3">
                    <p class="description-text text-muted">{{ $product->description }}</p>
                    <button class="btn btn-link p-0 description-toggle" style="font-size: 0.9rem;">Xem thêm</button>
                </div>
                <div class="free_delivery d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-truck"></i><span>Free Delivery</span>
                </div>
                <div class="original_price">$99.99</div>
                <div class="product_price">{{ number_format($product->price, 0, '.', '.') }} VNĐ</div>
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
                    <a href="{{ url('/cart/add/' . $product->id) }}" class="add_to_cart_button">Add to Cart</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="tabs_section_container py-5 ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <!-- Tabs Navigation -->
                    <div class="container my-3">
                        <div class="tabs_container d-flex justify-content-center">
                            <ul class="tabs nav">
                                <li class="tab active mx-2" data-active-tab="tab_1"><span>Description</span></li>
                                <li class="tab mx-2" data-active-tab="tab_2"><span>Additional Information</span></li>
                                <li class="tab mx-2" data-active-tab="tab_3"><span>Reviews ({{ $count }})</span></li>
                            </ul>
                        </div>
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
                                    <h2>{{ $product->product_name }}</h2>
                                    <p>{{ $product->description ?? 'No description available.' }}</p>
                                </div>
                                @if ($product->images->isNotEmpty())
                                <div class="tab_image">
                                    <img src="{{ asset('assets/images/' . $product->images[0]->image_url) }}" alt="{{ $product->product_name }}">
                                </div>
                                @endif
                                @if ($product->features && isset($product->features['Gaming']))
                                <div class="tab_text_block">
                                    <h2>Enhanced Gaming Experience</h2>
                                    <p>{{ $product->features['Gaming'] }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="col-lg-5 offset-lg-2 desc_col">
                                @if ($product->images->count() > 1)
                                <div class="tab_image">
                                    <img src="{{ asset('assets/images/' . $product->images[1]->image_url) }}" alt="{{ $product->product_name }}">
                                </div>
                                @endif
                                @if ($product->features && isset($product->features['Comfort']))
                                <div class="tab_text_block">
                                    <h2>Comfort and Durability</h2>
                                    <p>{{ $product->features['Comfort'] }}</p>
                                </div>
                                @endif
                                @if ($product->images->count() > 2)
                                <div class="tab_image desc_last">
                                    <img src="{{ asset('assets/images/' . $product->images[2]->image_url) }}" alt="{{ $product->product_name }}">
                                </div>
                                @endif
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
                                @if($product->details)
                                <p>MODEL: <span>{{ $product->details->model ?? 'N/A' }}</span></p>
                                <p>CONNECTIVITY: <span>{{ $product->details->connectivity ?? 'N/A' }}</span></p>
                                <p>COMPATIBILITY: <span>{{ $product->details->compatibility ?? 'N/A' }}</span></p>
                                <p>WEIGHT: <span>{{ $product->details->weight ?? 'N/A' }}</span></p>
                                @else
                                <p>No additional information available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Tab Reviews -->
                    <div id="tab_3" class="tab_container ">
                        <div class="row d-flex justify-content-center">
                            <!-- User Reviews -->
                            <div class="col-lg-6 reviews_col">
                                <div class="tab_title reviews_title">
                                    <h4>Reviews ({{ $count }})</h4>
                                </div>
                                @foreach($reviews as $review)
                                <div class="user_review_container d-flex flex-column flex-sm-row gap-3">
                                    <!-- Phần thông tin người dùng -->
                                    <div class="user" style="width: 45%;">

                                        <div class="user_name">{{ $review->user->name ?? 'Anonymous' }}</div>


                                        <div class="user_rating">
                                            <ul class="star_rating list-unstyled d-flex gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <li>
                                                    <i class="fa {{ $i <= $review->rating ? 'fa-star' : 'fa-star-o' }}" aria-hidden="true"></i>
                                                    </li>
                                                    @endfor
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Phần nội dung đánh giá -->
                                    <div class="review" style="width: 55%;">
                                        <div class="review_date">{{ $review->created_at->format('d M Y') }}</div>

                                        <p>
                                            {{ strlen($review->comment) > 55 ? substr($review->comment, 0, 35) . '...' : $review->comment }}
                                        </p>
                                        <img src="{{ asset($review->image) }}" alt="review Image" style="width: 50px; height: 50px; border-radius: 50%;">
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-lg-6 add_review_col my-4 mx-auto">
                                    <div class="add_review">
                                        <form id="review_form" action="{{ route('reviews.form', ['product_id' => $product->product_id]) }}" method="GET">
                                            @csrf

                                            <div class="text-left text-sm-right">
                                                <button id="review_submit" type="submit" class="red_button review_submit_btn">Đánh giá</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div style="margin-left: -25px;">
                                    {{ $reviews->links('pagination::bootstrap-5') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Benefit -->
    @include('clients.partials.benefit')

</div>

<!-- CSS for Description Toggle -->
<style>
    .description-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        /* Limit to 3 lines by default */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.3s ease;
    }

    .description-text.expanded {
        -webkit-line-clamp: unset;
        overflow: visible;
    }
</style>

<!-- Consolidated JavaScript -->
<script>
    console.log(<?= $review->user ?>)
    document.addEventListener('DOMContentLoaded', function() {
        // Hamburger Menu Toggle
        const hamburgerContainer = document.querySelector('.hamburger_container');
        const hamburgerMenu = document.querySelector('.hamburger_menu');
        const hamburgerClose = document.querySelector('.hamburger_close');
        const fsMenuOverlay = document.querySelector('.fs_menu_overlay');

        if (hamburgerContainer && hamburgerMenu && fsMenuOverlay) {
            hamburgerContainer.addEventListener('click', function() {
                hamburgerMenu.classList.add('active');
                fsMenuOverlay.classList.add('active');
            });
        }

        if (hamburgerClose && hamburgerMenu && fsMenuOverlay) {
            hamburgerClose.addEventListener('click', function() {
                hamburgerMenu.classList.remove('active');
                fsMenuOverlay.classList.remove('active');
            });
        }

        if (fsMenuOverlay && hamburgerMenu) {
            fsMenuOverlay.addEventListener('click', function() {
                hamburgerMenu.classList.remove('active');
                fsMenuOverlay.classList.remove('active');
            });
        }

        // Thumbnail Image Switch
        const thumbnails = document.querySelectorAll('.single_product_thumbnails img');
        const mainImage = document.querySelector('.single_product_image');

        if (thumbnails && mainImage) {
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const newImage = this.getAttribute('data-image');
                    mainImage.style.backgroundImage = `url('${newImage}')`;
                });
            });
        }

        // Quantity Selector
        const quantityValue = document.getElementById('quantity_value');
        const minusBtn = document.querySelector('.quantity_selector .minus');
        const plusBtn = document.querySelector('.quantity_selector .plus');

        if (quantityValue && minusBtn && plusBtn) {
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
        }

        // Tabs Functionality
        const tabs = document.querySelectorAll('.tabs .tab');
        const tabContents = document.querySelectorAll('.tab_container');

        if (tabs && tabContents) {
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
        }

        // Description Toggle
        const toggles = document.querySelectorAll('.description-toggle');

        if (toggles) {
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
        }
    });
</script>

@endsection