<?php
include_once 'config.php';
try {
    // Lấy dữ liệu từ form
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $image_name = ''; // Chỉ lưu tên tệp ảnh vào cơ sở dữ liệu
    
    // Kiểm tra dữ liệu đầu vào
    if ($user_id <= 0 || $product_id <= 0 || $rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ']);
        exit;
    }
    
    // Xử lý upload ảnh nếu có
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/';
        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        
        // Kiểm tra xem tệp có trùng tên trong thư mục images/ không
        if (file_exists($image_path)) {
            echo json_encode(['success' => false, 'message' => 'Tên ảnh đã tồn tại. Vui lòng chọn ảnh có tên khác.']);
            exit;
        }
        
        // Kiểm tra và tạo thư mục images nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Di chuyển file ảnh vào thư mục images
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi tải lên ảnh']);
            exit;
        }
    }
    
    // Chuẩn bị và thực thi câu lệnh SQL
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment, image, review_date) VALUES (:user_id, :product_id, :rating, :comment, :image, NOW())");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':image', $image_name); // Chỉ lưu tên tệp ảnh
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu vào cơ sở dữ liệu']);
    }
   
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage()]);
}
?>