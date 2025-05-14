 <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-md-7">
                <form action="{{ route('place-order') }}" method="POST" id="checkout-form">
                    @csrf
                    <!-- Your form fields remain the same -->
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-md-5">
                <h3>Đơn hàng của bạn</h3>
                @php
                    $userId = auth()->id();
                    $cart = \App\Models\Cart::with('products')->where('user_id', $userId)->first();
                    $subtotal = 0;
                @endphp

                @if($cart && $cart->products->count() > 0)
                    @foreach ($cart->products as $product)
                        @php
                            $quantity = $product->pivot->quantity;
                            $subtotal += $product->price * $quantity;
                        @endphp
                        <div class="summary-item d-flex justify-content-between align-items-center">
                            <div class="summary-item-content">
                                <img src="{{ asset('assets/images/' . $product->image_url) }}" alt="{{ $product->product_name }}" class="product-img">
                                <span>{{ $product->product_name }} (x{{ $quantity }})</span>
                            </div>
                            <span class="summary-item-price">{{ number_format($product->price * $quantity) . 'đ' }}</span>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tạm tính:</strong>
                        <span>{{ number_format($subtotal) . 'đ' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Giảm giá:</strong>
                        <span id="coupon-discount">0đ</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <span id="total">{{ number_format($subtotal) . 'đ' }}</span>
                    </div>
                @else
                    <div class="alert alert-warning">Giỏ hàng của bạn đang trống</div>
                @endif

                <!-- Coupon code section remains the same -->
            </div>
        </div>
    </div>