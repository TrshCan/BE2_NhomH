<?php
session_start();
include '../includes/header.php';
include '../includes/User_Database.php';
include '../includes/Product_Database.php';

$products = new Product_Database();
$userDB = new User_Database();
$email = $_SESSION['email'];
$user = $userDB->getUser($email);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Thanh Toán</title>
    <!-- Custom CSS -->
    <link href="../assets/styles/checkout.css" rel="stylesheet">
</head>

<body>
    <div class="container checkout-container my-5">
        <a href="cart.php" class="btn btn-link mb-3">← Quay lại giỏ hàng</a>
        <h2 class="text-center mb-4 text-primary">Thanh Toán</h2>
        <?php
        // Calculate totals from cart
        $subtotal = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $items) {
                $item = $products->getProductById_1item($id);
                $subtotal += $item['price'] * $items['quantity'];
            }
        }
        $total = $subtotal; // Adjusted by coupon in JS
        ?>
        <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-warning text-center" role="alert">
            Giỏ hàng của bạn đang trống. <a href="../index.php" class="alert-link">Tiếp tục mua sắm!</a>
        </div>
        <?php else: ?>
        <div class="row">
            <!-- Thông Tin Giao Hàng (Left Column) -->
            <div class="col-lg-8">
                <div class="card card-custom p-4 mb-4">
                    <h4 class="mb-4">Thông Tin Giao Hàng</h4>
                    <form method="POST" action="../includes/process_checkout.php" id="checkout-form">
                        <div class="row g-3">
                            <input type="text" class="form-control" id="id" name="id" value="<?= $user['user_id'] ?>"
                                hidden>
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">Họ</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Tên</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Địa Chỉ</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="col-12">
                                <label for="phone" class="form-label">Số Điện Thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <!-- Split into three dropdowns -->
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
                                    <input class="form-check-input" type="radio" name="payment" id="cod" value="cod"
                                        checked>
                                    <label class="form-check-label" for="cod">Thanh Toán Khi Nhận Hàng (COD)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="bank" value="bank">
                                    <label class="form-check-label" for="bank">Thẻ Ngân Hàng/ATM/Visa/Master/JCB/QR
                                        Code</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="e-wallet"
                                        value="e-wallet">
                                    <label class="form-check-label" for="e-wallet">Ví Điện Tử (PayPal, etc.)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-warning alert-custom mt-3" role="alert">
                                    Lưu ý: Vui lòng kiểm tra kỹ địa chỉ giao hàng trước khi đặt hàng.
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Tóm Tắt Đơn Hàng (Right Column) -->
            <div class="col-lg-4">
                <div class="card card-custom p-4">
                    <h4 class="mb-4">Tóm Tắt Đơn Hàng</h4>
                    <?php foreach ($_SESSION['cart'] as $id => $items):
                            $item = $products->getProductById_1item($id) ?>
                    <div class="summary-item d-flex justify-content-between align-items-center">
                        <div class="summary-item-content">
                            <img src="../assets/images/<?php echo $item['image_url']; ?>"
                                alt="<?php echo $item['product_name']; ?>" class="product-img">
                            <span><?php echo $item['product_name']; ?></span>
                        </div>
                        <span class="summary-item-price"><?php echo $item['price'] * $items['quantity'] . 'đ'; ?></span>
                    </div>
                    <?php endforeach; ?>
                    <div class="coupon-section">
                        <input type="text" class="form-control" id="coupon-code" name="coupon_code"
                            placeholder="Nhập mã giảm giá">
                        <button type="button" class="btn btn-coupon btn-sm text-white" onclick="applyCoupon()">Áp
                            Dụng</button>
                    </div>
                    <hr>
                    <div class="summary-item d-flex justify-content-between">
                        <span>Tạm Tính</span>
                        <span id="subtotal"><?php echo $subtotal . 'đ'; ?></span>
                    </div>
                    <div class="summary-item d-flex justify-content-between">
                        <span>Giảm Giá Mã Coupon</span>
                        <span id="coupon-discount">0đ</span>
                        <input type="hidden" id="discount-amount" name="discount" value="0">
                    </div>
                    <div class="summary-item d-flex justify-content-between fw-bold">
                        <span>Tổng Cộng</span>
                        <span id="total"><?php echo $total . 'đ'; ?></span>
                        <input type="hidden" id="total-amount" name="total" value="<?php echo $total; ?>">
                    </div>
                    <button type="submit" class="btn btn-custom btn-lg w-100 mt-4">Đặt Hàng</button>
                    </form> <!-- Closing form tag here -->
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function applyCoupon() {
        const couponCode = document.getElementById('coupon-code').value.trim();
        const subtotalElement = document.getElementById('subtotal');
        const couponDiscountElement = document.getElementById('coupon-discount');
        const totalElement = document.getElementById('total');
        let subtotal = 14499000; // Base subtotal in VND (14,499,000₫)

        let discount = 0;
        // Example coupon logic (you can expand this)
        if (couponCode.toUpperCase() === 'SAVE10') {
            discount = subtotal * 0.1; // 10% discount
            alert('Mã giảm giá áp dụng thành công! Bạn được giảm 10%.');
        } else if (couponCode !== '') {
            alert('Mã giảm giá không hợp lệ.');
        }

        // Update totals
        couponDiscountElement.innerText = discount.toLocaleString() + '₫';
        const total = subtotal - discount;
        totalElement.innerText = total.toLocaleString() + '₫';
    }
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", async function() {
        const provinceSelect = document.getElementById("province");
        const districtSelect = document.getElementById("district");
        const wardSelect = document.getElementById("ward");

        let locationData = [];

        // Load JSON data
        async function loadLocationData() {
            try {
                const response = await fetch("../assets/nested-divisions.json");
                locationData = await response.json();
                populateProvinces();
            } catch (error) {
                console.error("Error loading JSON:", error);
            }
        }

        // Populate Province Dropdown
        function populateProvinces() {
            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
            locationData.forEach(province => {
                const option = document.createElement("option");
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        }

        // Populate Districts based on selected Province
        function populateDistricts(provinceCode) {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;

            if (!provinceCode) return;

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

        // Populate Wards based on selected District
        function populateWards(districtCode, provinceCode) {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;

            if (!districtCode || !provinceCode) return;

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

        // Event Listeners
        provinceSelect.addEventListener("change", function() {
            populateDistricts(this.value);
        });

        districtSelect.addEventListener("change", function() {
            populateWards(this.value, provinceSelect.value);
        });

        // Initialize data
        loadLocationData();
    });
    </script>

</body>

</html>