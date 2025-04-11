<?php
include 'lib/session.php';
Session::init();
include_once 'lib/database.php';
include_once 'helpers/format.php';

spl_autoload_register(function ($className) {
	include_once "classes/" . $className . ".php";
});

$db = new Database();
$fm = new Format();
$ct = new Cart();
$cat = new Category();
$comment = new Comment();
$compare = new Compare();
$cs = new Customer();
$inventory = new Inventory();
$order = new Order();
$postCat = new PostCategory();
$product = new Product();
$blog = new Blog();
$brand = new Brand();
$rating = new Rating();
$slider = new Slider();
$wl = new Wishlist();
$contact = new Contact();
?>

<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");

// Lấy thông tin khách hàng nếu đã đăng nhập
$login_check = Session::get('customer_login');
$customer_id = null; // Khởi tạo mặc định là null
$customer = null;
if ($login_check) {
	$customer_id = Session::get('customer_id');
	$customer_data = $cs->show_customer($customer_id);
	$customer = $customer_data ? $customer_data->fetch_assoc() : null;
}

if (isset($_GET['customer_id'])) {
	$logout_customer_id = $_GET['customer_id'];
	$ct->del_all_data_cart();
	$compare->del_all_compare($logout_customer_id);
	$wl->del_all_wishlist($logout_customer_id);
	Session::destroy();
}
?>

<!DOCTYPE HTML>

<head>
	<title>Store Website</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/menu.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/customer.css" rel="stylesheet" type="text/css" media="all" />
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Doppio+One' rel='stylesheet' type='text/css'>
</head>

<body>
	<div class="wrap">
		<div class="header_top">
			<div class="logo">
				<a href="index.php"><img src="images/logo.png" alt="Store Logo" /></a>
			</div>
			<div class="header_top_right">
				<div class="search_box">
					<form action="search.php" method="POST">
						<input type="text" placeholder="Tìm kiếm sản phẩm" name="tukhoa">
						<button type="submit" name="search_product" class="search_btn"><i class="fas fa-search"></i></button>
					</form>
				</div>
				<div class="account_section">
					<?php if ($login_check && $customer): ?>
						<div class="customer_info">
							<span>Xin chào, <?php echo $fm->textShorten($customer['name'], 15); ?></span>
							<div class="profile_dropdown">
								<img src="admin/uploads/<?php echo $customer['image'] ? $customer['image'] : 'default_avatar.png'; ?>" alt="Profile" class="profile_pic" />
								<ul class="dropdown_menu">
									<li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
									<li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Giỏ hàng</a></li>
									<li><a href="compare.php"><i class="fas fa-balance-scale"></i> So sánh sản phẩm</a></li>
									<li><a href="wishlist.php"><i class="fas fa-heart"></i> Danh sách yêu thích</a></li>
									<li><a href="order_history.php"><i class="fas fa-history"></i> Lịch sử đơn hàng</a></li>
									<li><a href="?customer_id=<?php echo $customer_id; ?>"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
								</ul>
							</div>
						</div>
					<?php else: ?>
						<div class="auth_links">
							<a href="login.php" class="auth_btn login_btn"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
							<a href="register.php" class="auth_btn register_btn"><i class="fas fa-user-plus"></i> Đăng ký</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="menu">
			<ul class="navbar">
				<li><a href="index.php">Trang chủ</a></li>
				<li><a href="products.php">Danh mục sản phẩm</a>
					<ul class="dropdown">
						<?php
						$getall_category = $cat->show_category_frontend();
						if ($getall_category && $getall_category->num_rows > 0) {
							while ($result_allcat = $getall_category->fetch_assoc()) {
						?>
								<li><a href="productbycat.php?catId=<?php echo $result_allcat['cat_id']; ?>"><?php echo $result_allcat['cat_name']; ?></a></li>
						<?php
							}
						} else {
							echo '<li><a href="#">Không có danh mục</a></li>';
						}
						?>
					</ul>
				</li>
				<li><a href="topbrands.php">Thương hiệu</a>
					<ul class="dropdown">
						<?php
						$getall_brand = $brand->show_brand();
						if ($getall_brand && $getall_brand->num_rows > 0) {
							while ($result_allbrand = $getall_brand->fetch_assoc()) {
						?>
								<li><a href="productbybrand.php?brandId=<?php echo $result_allbrand['brand_id']; ?>"><?php echo $result_allbrand['brand_name']; ?></a></li>
						<?php
							}
						} else {
							echo '<li><a href="#">Không có thương hiệu</a></li>';
						}
						?>
					</ul>
				</li>
				<li><a href="blog.php">Danh mục tin tức</a>
					<ul class="dropdown">
						<?php
						$getall_postcat = $postCat->show_category();
						if ($getall_postcat && $getall_postcat->num_rows > 0) {
							while ($result_allpostcat = $getall_postcat->fetch_assoc()) {
						?>
								<li><a href="blogbycat.php?postCatId=<?php echo $result_allpostcat['post_cat_id']; ?>"><?php echo $result_allpostcat['title']; ?></a></li>
						<?php
							}
						} else {
							echo '<li><a href="#">Không có danh mục tin tức</a></li>';
						}
						?>
					</ul>
				</li>
				<?php
				if ($login_check && $customer_id) {
					$check_order = $order->check_order($customer_id);
					if ($check_order == true) {
						echo '<li><a href="cart.php">Giỏ hàng</a></li>';
					}
				}
				?>
				<li><a href="about.php">Về chúng tôi</a></li>
				<li><a href="contact.php">Liên hệ</a></li>
			</ul>
		</div>