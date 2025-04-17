<?php
require_once 'danhgia_db.php';
require_once 'config.php';
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['product_id'] = 1;
$danhgia_db = new danhgia_db($conn);
$product = $danhgia_db->getProductById($_SESSION['product_id']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đánh Giá Sản Phẩm</title>
    <link rel="stylesheet" href="./danhgia.css">
    <style>
        /* CSS cho các ngôi sao */
        .stars {
            font-size: 24px;
            cursor: pointer;
        }

        .star {
            display: inline-block;
            color: #ccc;
            /* Màu xám mặc định */
            transition: color 0.2s;
        }

        .star.active {
            color: gold;
            /* Màu vàng khi được chọn */
        }

        .product-image {
            max-width: 100px;
            max-height: 100px;
        }

        /* CSS cho vùng hiển thị ảnh đã chọn */
        #image-preview-container {
            margin-top: 10px;
            display: none;
        }

        .image-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            padding: 5px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }

        .image-name {
            margin-left: 10px;
            flex-grow: 1;
        }

        .remove-image {
            cursor: pointer;
            color: red;
            margin-left: 10px;
        }

        .image-preview {
            max-width: 50px;
            max-height: 50px;
        }

        /* Ẩn input file mặc định */
        #file-input {
            display: none;
        }

        /* CSS cho bộ đếm ký tự */
        .char-counter {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }

        .char-counter.limit {
            color: red;
        }

        /* CSS cho thông báo */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            display: none;
        }

        .notification.success {
            background-color: #4CAF50;
        }

        .notification.error {
            background-color: #f44336;
        }

        .notification.warning {
            background-color: #ff9800;
            /* Màu cam cho cảnh báo */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Đánh Giá Sản Phẩm</h1>
        <div class="product-info">
            <img src=".<?= $product['image_url'] ?>" alt="<?= $product['product_name'] ?>" class="product-image">
            <p><?= $product['product_name'] ?><br>
                <span class="type"><?= $product['description'] ?></span>
            </p>
        </div>
        <div class="rating">
            <p>Chất lượng sản phẩm
                <span class="stars">
                    <span class="star" data-value="1">★</span>
                    <span class="star" data-value="2">★</span>
                    <span class="star" data-value="3">★</span>
                    <span class="star" data-value="4">★</span>
                    <span class="star" data-value="5">★</span>
                    <input type="hidden" id="rating-value" name="rating" value="5">
                </span>
                <span id="quality">Tuyệt vời</span>
            </p>
        </div>
        <div class="review-box">
            <label for="review">Đánh giá chất lượng sản phẩm:</label>
            <textarea id="review" name="comment" placeholder="Hãy chia sẻ những điều bạn thích về sản phẩm này với những người mua khác nhé." maxlength="255"></textarea>
            <div class="char-counter"><span id="char-count">0</span>/255</div>
        </div>

        <!-- Container để hiển thị ảnh đã chọn -->
        <div id="image-preview-container"></div>

        <!-- Các trường ẩn cho user_id và product_id -->
        <input type="hidden" id="user-id" name="user_id" value="<?= $_SESSION['user_id'] ?>">
        <input type="hidden" id="product-id" name="product_id" value="<?= $product['product_id'] ?>">

        <div class="buttons">
            <!-- Input file ẩn -->
            <input type="file" id="file-input" accept="image/*">
            <!-- Nút hiển thị -->
            <button class="btn add-image" id="upload-button" style="margin-right: 8px;">
                Thêm Hình ảnh
            </button>
            <div class="button-group">
                <button class="btn back" id="back-button">Trở Lại</button>
                <button class="btn submit" id="submit-review">Hoàn Thành</button>
            </div>
        </div>

        <!-- Container cho thông báo -->
        <div id="notification" class="notification"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const div = document.querySelector('.container');
            const containerWidth = div.clientWidth; // Chiều rộng (px)
            const containerHeight = div.clientHeight; // Chiều cao (px)

            div.style.top = `calc(50% - ${containerHeight / 2}px)`; 
            div.style.left = `calc(50% - ${containerWidth / 2}px)`; 


        });


        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý đánh giá sao
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating-value');
            const qualityText = document.getElementById('quality');
            const qualityLabels = ['Không thích', 'Không hài lòng', 'Bình thường', 'Tốt', 'Tuyệt vời'];
            let currentRating = 5; // Mặc định 5 sao

            // Thiết lập mặc định ban đầu (5 sao)
            updateStars(currentRating);

            // Thêm sự kiện click cho từng ngôi sao
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    currentRating = value;
                    ratingInput.value = value;
                    updateStars(value);
                    updateQualityText(value);
                });
            });

            // Hàm cập nhật trạng thái các ngôi sao
            function updateStars(count) {
                stars.forEach(star => {
                    const starValue = parseInt(star.getAttribute('data-value'));
                    if (starValue <= count) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }

            // Hàm cập nhật text mô tả chất lượng
            function updateQualityText(count) {
                qualityText.textContent = qualityLabels[count - 1];
            }

            // --- XỬ LÝ GIỚI HẠN KÝ TỰ TRONG TEXTAREA ---
            const reviewTextarea = document.getElementById('review');
            const charCounter = document.getElementById('char-count');
            const MAX_CHARS = 255;

            // Đảm bảo thuộc tính maxlength được đặt
            reviewTextarea.setAttribute('maxlength', MAX_CHARS);

            // Cập nhật số ký tự khi nhập
            reviewTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCounter.textContent = length;

                // Đổi màu khi gần đạt giới hạn
                if (length >= MAX_CHARS) {
                    document.querySelector('.char-counter').classList.add('limit');
                } else {
                    document.querySelector('.char-counter').classList.remove('limit');
                }
            });

            // --- XỬ LÝ UPLOAD HÌNH ẢNH ---
            const fileInput = document.getElementById('file-input');
            const uploadButton = document.getElementById('upload-button');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const selectedFiles = []; // Mảng lưu trữ các file đã chọn

            // Khi click vào nút thêm hình ảnh
            uploadButton.addEventListener('click', function() {
                // Kiểm tra xem đã có hình ảnh được chọn chưa
                if (selectedFiles.length >= 1) {
                    alert("Chỉ được thêm tối đa 1 hình ảnh!");
                    return;
                }
                fileInput.click();
            });

            // Khi chọn file
            fileInput.addEventListener('change', function(event) {
                if (this.files && this.files.length > 0) {
                    // Kiểm tra xem đã có file nào chưa và giới hạn số lượng
                    if (selectedFiles.length >= 1 || this.files.length > 1) {
                        alert("Chỉ được thêm tối đa 1 hình ảnh!");
                        this.value = ''; // Reset input
                        return;
                    }

                    // Hiển thị container preview
                    imagePreviewContainer.style.display = 'block';

                    // Xử lý file được chọn
                    const file = this.files[0];

                    // Kiểm tra xem file có phải là ảnh không
                    if (!file.type.match('image.*')) {
                        alert('Vui lòng chỉ chọn file ảnh!');
                        this.value = '';
                        return;
                    }

                    // Thêm file vào mảng đã chọn
                    selectedFiles.push(file);

                    // Tạo thẻ div hiển thị thông tin file
                    const imageItem = document.createElement('div');
                    imageItem.className = 'image-item';

                    // Tạo preview ảnh
                    const img = document.createElement('img');
                    img.className = 'image-preview';
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };

                    reader.readAsDataURL(file);

                    // Tạo thẻ span hiển thị tên file
                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'image-name';
                    nameSpan.textContent = file.name;

                    // Tạo nút xóa
                    const removeButton = document.createElement('span');
                    removeButton.className = 'remove-image';
                    removeButton.innerHTML = '×';

                    // Thêm sự kiện xóa file
                    removeButton.addEventListener('click', function() {
                        selectedFiles.pop(); // Xóa file khỏi mảng
                        imageItem.remove(); // Xóa khỏi giao diện

                        // Ẩn container khi không còn file nào
                        imagePreviewContainer.style.display = 'none';
                    });

                    // Ghép các phần tử vào div chứa
                    imageItem.appendChild(img);
                    imageItem.appendChild(nameSpan);
                    imageItem.appendChild(removeButton);

                    // Thêm vào container
                    imagePreviewContainer.appendChild(imageItem);

                    // Reset input file để có thể chọn cùng file nhiều lần
                    this.value = '';
                }
            });

            // --- XỬ LÝ NÚT TRỞ LẠI ---
            document.getElementById('back-button').addEventListener('click', function() {
                window.history.back(); // Quay lại trang trước đó
            });

            // --- XỬ LÝ NÚT HOÀN THÀNH ---
            document.getElementById('submit-review').addEventListener('click', function() {
                const reviewText = document.getElementById('review').value;
                const userId = document.getElementById('user-id').value;
                const productId = document.getElementById('product-id').value;
                const notification = document.getElementById('notification');

                // Kiểm tra bắt buộc chọn ít nhất 1 ảnh
                if (selectedFiles.length === 0) {
                    notification.textContent = 'Vui lòng chọn ít nhất 1 hình ảnh!';
                    notification.className = 'notification warning';
                    notification.style.display = 'block';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);
                    return;
                }

                // Kiểm tra comment tối thiểu 8 ký tự
                if (reviewText.length < 8) {
                    notification.textContent = 'Nội dung đánh giá phải có ít nhất 8 ký tự!';
                    notification.className = 'notification warning';
                    notification.style.display = 'block';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);
                    return;
                }

                // Tạo FormData để gửi lên server
                const formData = new FormData();
                formData.append('user_id', userId);
                formData.append('product_id', productId);
                formData.append('rating', currentRating);
                formData.append('comment', reviewText);

                // Thêm file ảnh vào FormData
                if (selectedFiles.length > 0) {
                    formData.append('image', selectedFiles[0]);
                }

                // Gửi dữ liệu lên server
                fetch('submit_review.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hiển thị thông báo thành công
                            notification.textContent = 'Đánh giá đã được lưu thành công!';
                            notification.className = 'notification success';
                            notification.style.display = 'block';

                            // Ẩn thông báo sau 3 giây và quay lại
                            setTimeout(() => {
                                notification.style.display = 'none';
                                window.history.back();
                            }, 3000);
                        } else {
                            // Hiển thị thông báo thất bại
                            notification.textContent = 'Lưu đánh giá thất bại: ' + (data.message || 'Lỗi không xác định');
                            notification.className = 'notification error';
                            notification.style.display = 'block';

                            // Ẩn thông báo sau 3 giây
                            setTimeout(() => {
                                notification.style.display = 'none';
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        // Hiển thị thông báo lỗi nếu fetch thất bại
                        notification.textContent = 'Lỗi kết nối: ' + error.message;
                        notification.className = 'notification error';
                        notification.style.display = 'block';

                        // Ẩn thông báo sau 3 giây
                        setTimeout(() => {
                            notification.style.display = 'none';
                        }, 3000);
                    });
            });
        });
    </script>
</body>

</html>