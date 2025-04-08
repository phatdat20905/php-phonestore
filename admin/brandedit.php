<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/brand.php' ?>
<?php
$cat = new Brand();
if (!isset($_GET['brandId']) || $_GET['brandId'] == NULL) {
    echo "<script>window.location='brandlist.php';</script>";
} else {
    $id = $_GET['brandId'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandName = $_POST['brandName'];

    $updateBrand = $cat->update_brand($brandName, $id);
}

?>
<div class="grid_10">
    <div class="box round first grid">
        <h2>Sửa thương hiệu</h2>
        <div class="block copyblock">
            <?php
            if (isset($updateBrand)) {
                echo $updateBrand;
            }
            ?>
            <?php
            $get_cat_name = $cat->get_brand_by_id($id);
            if ($get_cat_name) {
                while ($result = $get_cat_name->fetch_assoc()) {

            ?>
                    <form action="" method="post">
                        <table class="form">
                            <tr>
                                <td>
                                    <input type="text" value="<?php echo $result['brandName'] ?>" name="brandName" placeholder="Sửa danh mục thương hiệu ..." class="medium" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="submit" Value="Save" />
                                </td>
                            </tr>
                        </table>
                    </form>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<?php include 'inc/footer.php'; ?>