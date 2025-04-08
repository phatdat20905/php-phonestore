<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php include '../classes/brand.php'; ?>
<?php
$cat = new Brand();
if (isset($_GET['delId'])) {
	$id = $_GET['delId'];
	$delbrand = $cat->delete_brand($id);
}
?>
<div class="grid_10">
	<div class="box round first grid">
		<h2>Danh sách thương hiệu</h2>
		<div class="block">
			<?php
			if (isset($delbrand)) {
				echo $delbrand;
			}
			?>
			<table class="data display datatable" id="example">
				<thead>
					<tr>
						<th>Serial No.</th>
						<th>Category Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<t>
					<?php
					$show_cat = $cat->show_brand();
					if ($show_cat) {
						$i = 0;
						while ($result = $show_cat->fetch_assoc()) {
							$i++;
					?>
							<tr class="odd gradeX">
								<td><?php echo $i ?></td>
								<td><?php echo $result['brandName'] ?></td>
								<td><a href="brandedit.php?brandId=<?php echo $result['brandId'] ?>">Edit</a> || <a
										onclick="return confirm('Are you sure you want to delete <?php echo $result['brandName'] ?>?')"
										href="?delId=<?php echo $result['brandId'] ?>">Delete
									</a>
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
</div>
<script type="text/javascript">
	$(document).ready(function() {
		setupLeftMenu();

		$('.datatable').dataTable();
		setSidebarHeight();
	});
</script>
<?php include 'inc/footer.php'; ?>