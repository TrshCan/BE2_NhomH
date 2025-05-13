@extends('layouts.clients_home')

@section('content')
<div class="container checkout-container my-5">
    <a href="{{ route('cart.cart') }}" class="btn btn-link mb-3">← Quay lại giỏ hàng</a>
    <h2 class="text-center mb-4 text-primary">Thanh Toán</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @php
    $total = $subtotal;
    @endphp


    @if($cartItems->isEmpty())
    <div class="alert alert-warning text-center" role="alert">
        Giỏ hàng của bạn đang trống. <a href="{{ route('products.home') }}" class="alert-link">Tiếp tục mua sắm!</a>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-custom p-4 mb-4">
                <h4 class="mb-4">Thông Tin Giao Hàng</h4>
                <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="lastName" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="lastName" name="name"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address', $user->address) }}" required>
                        </div>
                        <div class="col-12">
                            <label for="phone" class="form-label">Số Điện Thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $user->phone) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="province" class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" id="province" name="province" required>
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="district" class="form-label">Quận/Huyện</label>
                            <select class="form-select" id="district" name="district" required disabled>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ward" class="form-label">Phường/Xã</label>
                            <select class="form-select" id="ward" name="ward" required disabled>
                                <option value="">Chọn phường/xã</option>
                            </select>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3">Phương Thức Thanh Toán</h4>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">Thanh Toán Khi Nhận Hàng (COD)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id="bank" value="bank">
                                <label class="form-check-label" for="bank">Thẻ Ngân Hàng MBBank</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id="e-wallet" value="e-wallet">
                                <label class="form-check-label" for="e-wallet">Ví Điện Tử Momo</label>
                            </div>
                        </div>
                        <div id="qr-code-container" style="display: none;">
                            <h4>Scan to Pay</h4>
                            <div id="qrcode"></div>
                        </div>


                        <div class="col-12">
                            <div class="alert alert-warning alert-custom mt-3" role="alert">
                                Lưu ý: Vui lòng kiểm tra kỹ địa chỉ giao hàng trước khi đặt hàng.
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom p-4">
                <h4 class="mb-4">Tóm Tắt Đơn Hàng</h4>
                @foreach ($cartItems as $cartItem)
                <div class="summary-item d-flex justify-content-between align-items-center row">
                    <div class="summary-item-content col-8">
                        <div class="row">
                            <img src="{{ asset('images/' . $cartItem->product->image) }}"
                                alt="{{ $cartItem->product->product_name }}" class="product-img img-fluid col-6">
                            <div class="col-6">
                                <span>{{ $cartItem->product->product_name }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="summary-item-price col-4">{{ $cartItem->product->price * $cartItem->quantity }}đ</span>
                </div>
                @endforeach

                <div class="coupon-section">
                    <input type="text" class="form-control" id="coupon-code" name="coupon_code"
                        placeholder="Nhập mã giảm giá">
                    <button type="button" class="btn btn-coupon btn-primary btn-sm text-white" onclick="applyCoupon()">Áp Dụng</button>
                </div>

                <hr>
                <div class="summary-item d-flex justify-content-between">
                    <span>Tạm Tính</span>
                    <span id="subtotal">{{ $subtotal }}đ</span>
                </div>
                <div class="summary-item d-flex justify-content-between">
                    <span>Giảm Giá Mã Coupon</span>
                    <span id="coupon-discount">0đ</span>
                    <input type="hidden" id="discount-amount" name="discount" value="0">
                </div>
                <div class="summary-item d-flex justify-content-between fw-bold">
                    <span>Tổng Cộng</span>
                    <span id="total">{{ $total }}đ</span>
                    <input type="hidden" id="total-amount" name="total" value="{{ $total }}">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Đặt Hàng</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const paymentRadioButtons = document.querySelectorAll('input[name="payment"]');
        const qrCodeContainer = document.getElementById("qr-code-container");
        const qrcodeElement = document.getElementById("qrcode");

        // Function to generate the QR code
        function generateQRCode(paymentType) {
            let paymentLink = '';

            if (paymentType === 'bank') {
                // Replace with your actual bank payment link or details
                paymentLink = '';
            } else if (paymentType === 'e-wallet') {
                // Replace with your e-wallet payment link
                paymentLink = 'https://me.momo.vn/4GIliDuJuDCEUgClujTQ';
            }

            // Clear previous QR code
            qrcodeElement.innerHTML = '';

            // Generate new QR code
            new QRCode(qrcodeElement, {
                text: paymentLink,
                width: 128,
                height: 128
            });

            // Show the QR code container
            qrCodeContainer.style.display = 'block';
        }

        // Listen for changes in the payment method selection
        paymentRadioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Hide the QR code if no relevant payment option is selected
                    qrCodeContainer.style.display = 'none';

                    // Generate QR code for Bank or E-wallet if selected
                    if (this.value === 'bank' || this.value === 'e-wallet') {
                        generateQRCode(this.value);
                    }
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const provinceSelect = document.getElementById("province");
        const districtSelect = document.getElementById("district");
        const wardSelect = document.getElementById("ward");

        let locationData = [];

        async function loadLocationData() {
            try {
                const response = await fetch("{{ asset('assets/json/nested-divisions.json') }}");
                locationData = await response.json();
                populateProvinces();
            } catch (error) {
                console.error("Lỗi khi tải dữ liệu tỉnh/thành:", error);
            }
        }

        function populateProvinces() {
            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
            locationData.forEach(province => {
                const option = document.createElement("option");
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        }

        function populateDistricts(provinceCode) {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;

            const selectedProvince = locationData.find(p => p.code == provinceCode);
            if (selectedProvince && selectedProvince.districts) {
                districtSelect.disabled = false;
                selectedProvince.districts.forEach(district => {
                    const option = document.createElement("option");
                    option.value = district.code;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }
        }

        function populateWards(districtCode, provinceCode) {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;

            const selectedProvince = locationData.find(p => p.code == provinceCode);
            const selectedDistrict = selectedProvince?.districts.find(d => d.code == districtCode);

            if (selectedDistrict && selectedDistrict.wards) {
                wardSelect.disabled = false;
                selectedDistrict.wards.forEach(ward => {
                    const option = document.createElement("option");
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
            }
        }

        provinceSelect.addEventListener("change", function() {
            populateDistricts(this.value);
        });

        districtSelect.addEventListener("change", function() {
            populateWards(this.value, provinceSelect.value);
        });

        loadLocationData();
    });
</script>

@endsection