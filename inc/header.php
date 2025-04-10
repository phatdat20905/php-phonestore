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
?>

<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: max-age=2592000");
?>

<!DOCTYPE HTML>

<head>
	<title>Store Website</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/menu.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/customer.css" rel="stylesheet" type="text/css" media="all" />
	<script src="js/jquerymain.js"></script>
	<script src="js/script.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/nav.js"></script>
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript" src="js/nav-hover.js"></script>
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
						<input type="submit" value="SEARCH" name="search_product">
					</form>
				</div>
				<!-- <div class="shopping_cart">
					<div class="cart">
						<a href="cart.php" title="View my shopping cart" rel="nofollow">
							<span class="cart_title">Cart</span>
							<span class="no_product">
								<?php
								$check_cart = $ct->check_cart();
								if ($check_cart) {
									$sum = Session::get("sum");
									$qty = Session::get("qty");
									echo $fm->format_currency($sum) . 'đ - Qty: ' . $qty;
								} else {
									echo 'Empty';
								}
								?>
							</span>
						</a>
					</div>
				</div> -->
				<?php
				if (isset($_GET['customer_id'])) {
					$customer_id = $_GET['customer_id'];
					$ct->del_all_data_cart();
					$compare->del_all_compare($customer_id);
					$wl->del_all_wishlist($customer_id);
					Session::destroy();
				}
				?>
				<div class="login_customer">
					<?php
					$login_check = Session::get('customer_login');
					if ($login_check == true) {
						echo '<a href="?customer_id=' . Session::get('customer_id') . '">Logout</a>';
					} else {
						echo '<a href="login.php">Login</a>';
					}
					?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="menu">
			<ul class="navbar">
				<li><a href="index.php">Trang chủ</a></li>
				<li><a href="products.php">Sản phẩm</a>
					<ul class="dropdown">
						<?php
						$getall_category = $cat->show_category_frontend();
						if ($getall_category) {
							while ($result_allcat = $getall_category->fetch_assoc()) {
						?>
								<li><a href="productbycat.php?catId=<?php echo $result_allcat['cat_id']; ?>"><?php echo $result_allcat['cat_name']; ?></a></li>
						<?php
							}
						}
						?>
					</ul>
				</li>
				<li><a href="topbrands.php">Thương hiệu</a>
					<ul class="dropdown">
						<?php
						$getall_brand = $brand->show_brand();
						if ($getall_brand) {
							while ($result_allbrand = $getall_brand->fetch_assoc()) {
						?>
								<li><a href="productbybrand.php?brandId=<?php echo $result_allbrand['brand_id']; ?>"><?php echo $result_allbrand['brand_name']; ?></a></li>
						<?php
							}
						}
						?>
					</ul>
				</li>
				<?php
				$customer_id = Session::get('customer_id');
				if ($customer_id) {
					$check_order = $order->check_order($customer_id);
					if ($check_order == true) {
						echo '<li><a href="orderdetails.php">Ordered</a></li>';
					}
				}
				?>
				<?php
				$login_check = Session::get('customer_login');
				if ($login_check) {
					echo '<li><a href="profile.php">Profile</a></li>';
					echo '<li><a href="cart.php">Cart</a></li>';
					echo '<li><a href="compare.php">Compare</a></li>';
					echo '<li><a href="wishlist.php">Wishlist</a></li>';
					echo '<li><a href="history_order.php">Lịch sử đơn hàng</a></li>';
				}
				?>
				<li><a href="contact.php">Contact</a></li>
			</ul>
		</div>