<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Product.php'; ?>
<?php include '../classes/Category.php'; ?>
<?php include '../classes/Brand.php'; ?>
<?php
$pd = new Product();
$cat = new Category();
$brand = new Brand();
$message = '';
if (isset($_GET['productId'])) {
    $id = $_GET['productId'];
    $delpro = $pd->delete_product($id);
    $message = $delpro['message'];
}

// Lấy dữ liệu lọc
$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : '';
$brand_id = isset($_GET['brand_id']) ? $_GET['brand_id'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Lấy danh sách danh mục và thương hiệu cho dropdown
$categories = $cat->show_category();
$brands = $brand->show_brand();
?>
<div class="content">
    <div class="box">
        <h2>Danh sách sản phẩm</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <!-- Form lọc -->
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label for="cat_id">Danh mục:</label>
                <select name="cat_id" id="cat_id">
                    <option value="">Tất cả</option>
                    <?php if ($categories): while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['cat_id']; ?>" <?php echo $cat_id == $cat['cat_id'] ? 'selected' : ''; ?>>
                            <?php echo $cat['cat_name']; ?>
                        </option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div class="filter-group">
                <label for="brand_id">Thương hiệu:</label>
                <select name="brand_id" id="brand_id">
                    <option value="">Tất cả</option>
                    <?php if ($brands): while ($brand = $brands->fetch_assoc()): ?>
                        <option value="<?php echo $brand['brand_id']; ?>" <?php echo $brand_id == $brand['brand_id'] ? 'selected' : ''; ?>>
                            <?php echo $brand['brand_name']; ?>
                        </option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div class="filter-group">
                <label for="keyword">Tìm kiếm:</label>
                <input type="text" name="keyword" id="keyword" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Nhập tên sản phẩm">
            </div>
            <button type="submit" class="btn btn-filter">Lọc</button>
        </form>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Hình ảnh</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Mô tả</th>
                    <th>Loại</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pdlist = $pd->filter_products($cat_id, $brand_id, $keyword);
                if ($pdlist) {
                    $i = 0;
                    while ($result = $pdlist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><?php echo number_format($result['price'], 0, ',', '.') . ' VND'; ?></td>
                            <td><img src="Uploads/<?php echo $result['image']; ?>" alt="Product Image" width="80"></td>
                            <td><?php echo $result['cat_name']; ?></td>
                            <td><?php echo $result['brand_name']; ?></td>
                            <td><?php echo substr($result['product_desc'], 0, 50) . '...'; ?></td>
                            <td><?php echo $result['type'] == 0 ? 'Non-Featured' : 'Featured'; ?></td>
                            <td>
                                <a href="productedit.php?productId=<?php echo $result['product_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Sửa</a>
                                <a href="?productId=<?php echo $result['product_id']; ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Xóa</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='9'>Không tìm thấy sản phẩm.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<style>
.filter-form {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.filter-group {
    display: flex;
    flex-direction: column;
}
.filter-group label {
    margin-bottom: 5px;
}
.filter-group select, .filter-group input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.btn-filter {
    padding: 8px 15px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-filter:hover {
    background-color: #218838;
}
</style>
<?php include 'inc/footer.php'; ?>