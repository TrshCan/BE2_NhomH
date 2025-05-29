@extends('layouts.clients_home')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
    $appliedCoupons = session('applied_coupons', []);
    // Safely calculate total discount
    $couponDiscount = array_sum(array_map(function ($coupon) {
    return is_array($coupon) && isset($coupon['discount']) ? $coupon['discount'] : (is_numeric($coupon) ? $coupon : 0);
    }, $appliedCoupons));
    $total = max(0, $subtotal - $couponDiscount);
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
                            <img src="{{ asset('assets/images/' . $cartItem->product->image_url) }}"
                                alt="{{ $cartItem->product->product_name }}" class="product-img img-fluid col-6">
                            <div class="col-6">
                                <span>{{ $cartItem->product->product_name }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="summary-item-price col-4">{{ number_format($cartItem->product->price * $cartItem->quantity, 0, ',', '.') }}đ</span>
                </div>
                @endforeach

                <div class="coupon-section mt-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="coupon-code" name="coupon_code" placeholder="Nhập mã giảm giá">
                        <button type="button" class="btn btn-coupon btn-primary btn-sm text-white" onclick="applyCoupon()">Áp Dụng</button>
                    </div>
                    <div id="applied-coupons" class="mt-2">
                        @foreach (session('applied_coupons', []) as $code => $coupon)
                        <div class="applied-coupon d-flex justify-content-between align-items-center mb-2"
                            data-code="{{ $code }}"
                            data-updated-at="{{ is_array($coupon) && isset($coupon['updated_at']) ? $coupon['updated_at'] : '' }}">
                            <span>Mã: <strong>{{ $code }}</strong></span>
                            <span class="coupon-amount">
                                {{ number_format(is_array($coupon) && isset($coupon['discount']) ? $coupon['discount'] : (is_numeric($coupon) ? $coupon : 0), 0, ',', '.') }}đ
                            </span>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeCoupon('{{ $code }}')">Xóa</button>
                        </div>
                        @endforeach
                    </div>
                    <div id="coupon-error" class="text-danger mt-2" style="display: none;"></div>
                </div>

                <hr>
                <div class="summary-item d-flex justify-content-between">
                    <span>Tạm Tính</span>
                    <span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-item d-flex justify-content-between">
                    <span>Giảm Giá Mã Coupon</span>
                    <span id="total-coupon-discount">{{ number_format($couponDiscount, 0, ',', '.') }}đ</span>
                    <input type="hidden" id="discount-amount" name="discount" value="{{ $couponDiscount }}">
                </div>
                <div class="summary-item d-flex justify-content-between fw-bold">
                    <span>Tổng Cộng</span>
                    <span id="total">{{ number_format($total, 0, ',', '.') }}đ</span>
                    <input type="hidden" id="total-amount" name="total" value="{{ $total }}">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Đặt Hàng</button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
<script src="https://cdn.rawgit.com/davidshimjs/qrcode.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const submitButton = document.querySelector("#checkout-form button[type='submit']");
        if (submitButton) {
            submitButton.addEventListener("click", function() {
                submitButton.disabled = true; // Disable button
                submitButton.innerText = "Processing...";
                setTimeout(() => {
                    submitButton.disabled = false; // Re-enable button after a delay (optional)
                }, 3000);
            });
        }
    });

    document.getElementById('checkout-form').addEventListener('submit', async function(event) {
        event.preventDefault();

        const couponError = document.getElementById('coupon-error');
        const subtotalSpan = document.getElementById('subtotal');
        const discountSpan = document.getElementById('total-coupon-discount');
        const totalSpan = document.getElementById('total');
        const discountInput = document.getElementById('discount-amount');
        const totalInput = document.getElementById('total-amount');

        try {
            const validateResponse = await fetch("{{ route('checkout.validate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            });

            const validateResult = await validateResponse.json();

            if (!validateResponse.ok || !validateResult.success) {
                couponError.textContent = validateResult.message || 'Giỏ hàng hoặc mã giảm giá không hợp lệ. Vui lòng kiểm tra lại.';
                couponError.style.display = 'block';
                return;
            }

            subtotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(validateResult.subtotal) + 'đ';
            discountSpan.textContent = new Intl.NumberFormat('vi-VN').format(validateResult.total_discount) + 'đ';
            totalSpan.textContent = new Intl.NumberFormat('vi-VN').format(Math.max(validateResult.total, 0)) + 'đ';
            discountInput.value = validateResult.total_discount;
            totalInput.value = Math.max(validateResult.total, 0).toFixed(2);

            const appliedCouponsDiv = document.getElementById('applied-coupons');
            const previousCouponCount = appliedCouponsDiv.querySelectorAll('.applied-coupon').length;
            appliedCouponsDiv.innerHTML = '';
            validateResult.applied_coupons.forEach(coupon => {
                const couponDiv = document.createElement('div');
                couponDiv.className = 'applied-coupon d-flex justify-content-between align-items-center mb-2';
                couponDiv.dataset.code = coupon.code;
                couponDiv.dataset.updatedAt = coupon.updated_at || '';
                couponDiv.innerHTML = `
                    <span>Mã: <strong>${coupon.code}</strong></span>
                    <span class="coupon-amount">${new Intl.NumberFormat('vi-VN').format(coupon.discount)}đ</span>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeCoupon('${coupon.code}')">Xóa</button>
                `;
                appliedCouponsDiv.appendChild(couponDiv);
            });

            if (validateResult.applied_coupons.length < previousCouponCount) {
                couponError.textContent = 'Một số mã giảm giá đã hết hiệu lực, đã được xóa hoặc đã bị chỉnh sửa.';
                couponError.style.display = 'block';
                return;
            }

            const formData = new FormData(this);
            const processResponse = await fetch("{{ route('checkout.process') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const processResult = await processResponse.json();

            if (processResponse.ok && processResult.status === 'success') {
                alert(processResult.message);
                window.location.href = "{{ route('products.home') }}";
            } else {
                couponError.textContent = processResult.message || 'Lỗi không xác định. Vui lòng thử lại.';
                couponError.style.display = 'block';
            }
        } catch (error) {
            console.error('Error processing checkout:', error);
            couponError.textContent = 'Lỗi khi xử lý đơn hàng. Vui lòng thử lại.';
            couponError.style.display = 'block';
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const paymentRadioButtons = document.querySelectorAll('input[name="payment"]');
        const qrCodeContainer = document.getElementById('qr-code-container');
        const qrcodeElement = document.getElementById('qrcode');

        function generateQRCode(paymentType) {
            let paymentLink = '';
            if (paymentType === 'bank') {
                paymentLink = '';
            } else if (paymentType === 'e-wallet') {
                paymentLink = 'https://me.momo.vn/4GIliDuJuDCEUgClujTQ';
            }
            qrcodeElement.innerHTML = '';
            new QRCode(qrcodeElement, {
                text: paymentLink,
                width: 128,
                height: 128
            });
            qrCodeContainer.style.display = 'block';
        }

        paymentRadioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                qrCodeContainer.style.display = 'none';
                if (this.value === 'bank' || this.value === 'e-wallet') {
                    generateQRCode(this.value);
                }
            });
        });

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

        window.applyCoupon = async function() {
            const couponCode = document.getElementById('coupon-code').value.trim();
            const appliedCouponsDiv = document.getElementById('applied-coupons');
            const couponError = document.getElementById('coupon-error');
            const subtotalSpan = document.getElementById('subtotal');
            const discountSpan = document.getElementById('total-coupon-discount');
            const totalSpan = document.getElementById('total');
            const discountInput = document.getElementById('discount-amount');
            const totalInput = document.getElementById('total-amount');

            if (!couponCode) {
                couponError.textContent = 'Vui lòng nhập mã giảm giá.';
                couponError.style.display = 'block';
                return;
            }

            try {
                const response = await fetch("{{ route('checkout.applyCoupon') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        coupon_code: couponCode
                    }),
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    const couponDiv = document.createElement('div');
                    couponDiv.className = 'applied-coupon d-flex justify-content-between align-items-center mb-2';
                    couponDiv.dataset.code = result.coupon.code;
                    couponDiv.dataset.updatedAt = result.coupon.updated_at || '';
                    couponDiv.innerHTML = `
                        <span>Mã: <strong>${result.coupon.code}</strong></span>
                        <span class="coupon-amount">${new Intl.NumberFormat('vi-VN').format(result.coupon.discount)}đ</span>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeCoupon('${result.coupon.code}')">Xóa</button>
                    `;
                    appliedCouponsDiv.appendChild(couponDiv);
                    couponError.style.display = 'none';
                    document.getElementById('coupon-code').value = '';

                    subtotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(result.subtotal) + 'đ';
                    discountSpan.textContent = new Intl.NumberFormat('vi-VN').format(result.total_discount) + 'đ';
                    totalSpan.textContent = new Intl.NumberFormat('vi-VN').format(Math.max(result.total, 0)) + 'đ';
                    discountInput.value = result.total_discount;
                    totalInput.value = Math.max(result.total, 0).toFixed(2);

                    alert(result.message);
                } else {
                    couponError.textContent = result.message || 'Lỗi khi áp dụng mã giảm giá.';
                    couponError.style.display = 'block';
                }
            } catch (error) {
                console.error('Error applying coupon:', error);
                couponError.textContent = 'Lỗi khi áp dụng mã giảm giá.';
                couponError.style.display = 'block';
            }
        };

        window.removeCoupon = async function(couponCode) {
            if (!confirm(`Bạn có chắc chắn muốn xóa mã giảm giá "${couponCode}" không?`)) {
                return;
            }

            const appliedCouponsDiv = document.getElementById('applied-coupons');
            const couponError = document.getElementById('coupon-error');
            const subtotalSpan = document.getElementById('subtotal');
            const discountSpan = document.getElementById('total-coupon-discount');
            const totalSpan = document.getElementById('total');
            const discountInput = document.getElementById('discount-amount');
            const totalInput = document.getElementById('total-amount');

            try {
                const response = await fetch("{{ route('checkout.removeCoupon') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        coupon_code: couponCode
                    }),
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    const couponDiv = appliedCouponsDiv.querySelector(`.applied-coupon[data-code="${couponCode}"]`);
                    if (couponDiv) {
                        couponDiv.remove();
                    }
                    couponError.style.display = 'none';

                    subtotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(result.subtotal) + 'đ';
                    discountSpan.textContent = new Intl.NumberFormat('vi-VN').format(result.total_discount) + 'đ';
                    totalSpan.textContent = new Intl.NumberFormat('vi-VN').format(Math.max(result.total, 0)) + 'đ';
                    discountInput.value = result.total_discount;
                    totalInput.value = Math.max(result.total, 0).toFixed(2);

                    alert(result.message);
                } else {
                    couponError.textContent = result.message || 'Lỗi khi xóa mã giảm giá.';
                    couponError.style.display = 'block';
                }
            } catch (error) {
                console.error('Error removing coupon:', error);
                couponError.textContent = 'Lỗi khi xóa mã giảm giá.';
                couponError.style.display = 'block';
            }
        };
    });
</script>
@endsection