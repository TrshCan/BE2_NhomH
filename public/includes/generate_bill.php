<?php
session_start();
require_once '../includes/User_Database.php';
require_once '../includes/Product_Database.php';
require_once '../includes/Order_Database.php';
require_once '../includes/OrderDetail_Database.php';

$order_db = new Order_Database();
$order_detail_db = new Order_Detail_Database();
$user_db = new User_Database();
$product_db = new Product_Database();

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

if (!$order_id || !$email) {
    header('Location: ../index.php');
    exit();
}

// Fetch user
$user = $user_db->getUser($email);
if (!$user) {
    header('Location: ../index.php');
    exit();
}

// Fetch order
$order = $order_db->getOrderById($order_id);
if (!$order || $order['user_id'] != $user['user_id']) {
    header('Location: ../index.php');
    exit();
}

// Fetch order details
$order_details = $order_detail_db->getOrderDetailsByOrderId($order_id);
$items = [];
foreach ($order_details as $detail) {
    $product = $product_db->getProductById_1item($detail['product_id']);
    $items[] = [
        'product_name' => $product['product_name'],
        'quantity' => $detail['quantity'],
        'price' => $detail['price']
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #<?php echo htmlspecialchars($order_id); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bill-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .bill-header { text-align: center; margin-bottom: 20px; }
        .bill-table { width: 100%; border-collapse: collapse; }
        .bill-table th, .bill-table td { border: 1px solid #ddd; padding: 8px; }
        .bill-table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="bill-container">
        <div class="bill-header">
            <h2>Hóa Đơn Mua Hàng</h2>
            <p>Mã Đơn Hàng: <?php echo htmlspecialchars($order_id); ?></p>
            <p>Ngày: <?php echo htmlspecialchars($order['order_date']); ?></p>
        </div>
        <h4>Thông Tin Khách Hàng</h4>
        <p><strong>Họ Tên:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
        <p><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <h4>Chi Tiết Đơn Hàng</h4>
        <table class="bill-table">
            <thead>
                <tr>
                    <th>Sản Phẩm</th>
                    <th>Số Lượng</th>
                    <th>Đơn Giá</th>
                    <th>Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price']) . 'đ'; ?></td>
                    <td><?php echo number_format($item['price'] * $item['quantity']) . 'đ'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <p><strong>Tạm Tính:</strong> <?php echo number_format($order['total_amount'] + ($order['discount'] ?? 0)) . 'đ'; ?></p>
            <p><strong>Giảm Giá:</strong> <?php echo number_format($order['discount'] ?? 0) . 'đ'; ?></p>
            <p><strong>Tổng Cộng:</strong> <?php echo number_format($order['total_amount']) . 'đ'; ?></p>
            <p><strong>Phương Thức Thanh Toán:</strong> 
                <?php 
                echo $order['payment_method'] === 'cod' ? 'Thanh Toán Khi Nhận Hàng' : 
                     ($order['payment_method'] === 'bank' ? 'Thẻ Ngân Hàng' : 'Ví Điện Tử'); 
                ?>
            </p>
        </div>
        <div class="text-center mt-4">
            <button class="btn btn-primary" onclick="downloadPDF()">Tải PDF</button>
            <button class="btn btn-secondary" onclick="downloadImage()">Tải Hình Ảnh</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.querySelector('.bill-container');
            const opt = {
                margin: 10,
                filename: 'Bill_<?php echo htmlspecialchars($order_id); ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }

        function downloadImage() {
            html2canvas(document.querySelector('.bill-container')).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Bill_<?php echo htmlspecialchars($order_id); ?>.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>
</html>