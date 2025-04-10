<?php
session_start();
include '../includes/Product_Database.php';

$products = new Product_Database();
// Calculate total if cart exists
$total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id=>$items) {
        $item = $products->getProductById($id);
        $total += $item['price'] * $items['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>TechGear Shop</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="TechGear Shop Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="public/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
  
    <link rel="stylesheet" type="text/css" href="../assets/styles/main_styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/styles/cart.css">
</head>

<body>

<div class="super_container">

	<!-- Header -->

	<header class="header trans_300">
		<!-- Main Navigation -->
		<div class="main_nav_container">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-right">
						<div class="logo_container">
							<a href="../../index.php">tech<span>gear</span></a>
						</div>
						<nav class="navbar">
							<ul class="navbar_menu">
								<li><a href="../../index.php">home</a></li>
								<li><a href="#">shop</a></li>
								<li><a href="#">promotion</a></li>
								<li><a href="#">pages</a></li>
								<li><a href="#">blog</a></li>
								<li><a href="contact.html">contact</a></li>
							</ul>
							<div class="navbar_user">
								<div class="search-bar">
								  <input
									type="text"
									id="searchInput"
									placeholder="Tìm kiếm sản phẩm..."
								  />
								  <button id="searchButton"><i class="fa fa-search" aria-hidden="true"></i></button>
								</div>
								<div class="checkout">
								  <a href="cart.php">
									<i class="fa fa-shopping-cart" aria-hidden="true"></i>
									<span id="checkout_items" class="checkout_items">2</span>
								  </a>
								</div>
								<div class="user-dropdown">
								  <a href="#" class="user-icon"><i class="fa fa-user" aria-hidden="true"></i></a>
								  <div class="dropdown-menu">
									<a href="#" class="dropdown-item">Đăng nhập</a>
									<a href="#" class="dropdown-item">Đăng xuất</a>
								  </div>
								</div>
							  </div>
							<div class="hamburger_container">
								<i class="fa fa-bars" aria-hidden="true"></i>
							</div>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</header>
</div>
<!-- Main Content -->
<main class="cart-page container mt-5 my-5">
    <h1 class="text-center mb-4">Giỏ Hàng</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info text-center" role="alert">
            Giỏ hàng của bạn đang trống. <a href="../../index.php" class="alert-link">Tiếp tục mua sắm!</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-warning text-dark">
                    <tr>
                        <th scope="col">Hình Ảnh</th>
                        <th scope="col">Sản Phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số Lượng</th>
                        <th scope="col">Tổng</th>
                        <th scope="col">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $id=>$items): 
                        $item = $products->getProductById($id)?>
                        <tr>
                            <td>
                                <img src="../assets/images/<?php echo $item['image_url']; ?>" alt="Product Image" class="img-thumbnail" style="width: 80px; height: 80px;">
                            </td>
                            <td><?php echo $item['product_name']; ?></td>
                            <td><?php echo $item['price'] . 'đ'; ?></td>
                            <td>
                                <input type="number" class="form-control w-25 mx-auto" value="<?php echo $items['quantity']; ?>" min="1" readonly>
                            </td>
                            <td><?php echo $item['price'] * $items['quantity'] . 'đ'; ?></td>
                            <td>
                                <a href="#" onclick="confirmDelete('<?php echo $id; ?>')" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="fw-bold"><?php echo number_format($total, 0) . 'đ'; ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-end gap-3">
            <a href="#" onclick="confirmDeleteAll()" class="btn btn-warning">Xóa Tất Cả</a>
            <a href="checkout.php" class="btn btn-primary checkout-btn">Thanh Toán</a>
        </div>
    <?php endif; ?>
</main>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-4">
    <p>© 2025 Website Bán Hàng. All rights reserved.</p>
</footer>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?")) {
            window.location.href = "../includes/cart_crud.php?action=delete&id=" + id;
        }
    }

    function confirmDeleteAll() {
        if (confirm("Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng không?")) {
            window.location.href = "../includes/cart_crud.php?action=deleteall";
        }
    }
</script>

</body>
</html>
