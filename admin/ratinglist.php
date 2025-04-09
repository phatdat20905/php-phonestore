<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/Rating.php'; ?>
<?php
$rating = new Rating();
$message = '';
if (isset($_GET['delId'])) {
    $id = $_GET['delId'];
    $delRating = $rating->delete_rating($id);
    $message = $delRating['message'];
}
?>
<div class="content">
    <div class="box">
        <h2>Danh sách đánh giá</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th>Số sao</th>
                    <th>Bình luận</th>
                    <th>Ngày đánh giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ratinglist = $rating->show_ratings();
                if ($ratinglist) {
                    $i = 0;
                    while ($result = $ratinglist->fetch_assoc()) {
                        $i++;
                ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $result['product_name']; ?></td>
                            <td><?php echo $result['customer_name']; ?></td>
                            <td><?php echo $result['rating']; ?> <i class="fas fa-star" style="color: #f39c12;"></i></td>
                            <td><?php echo $result['comment'] ? $result['comment'] : 'Không có'; ?></td>
                            <td><?php echo $result['created_at']; ?></td>
                            <td>
                                <a href="?delId=<?php echo $result['rating_id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?');"><i class="fas fa-trash"></i> Xóa</a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'inc/footer.php'; ?>