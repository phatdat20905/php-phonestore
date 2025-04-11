<div class="header_bottom">
	<div class="header_bottom_left">
		<div class="section group">
			<?php
			$getLatestApple = $product->getLastestByBrand(1);
			if ($getLatestApple && $resultApple = $getLatestApple->fetch_assoc()) {
			?>
				<div class="listview_1_of_2 images_1_of_2">
					<div class="listimg listimg_2_of_1">
						<a href="details.php"><img src="admin/uploads/<?php echo $resultApple['image']; ?>" alt="Ảnh sản phẩm" /></a>
					</div>
					<div class="text list_2_of_1">
						<h2><?php echo $fm->textShorten($resultApple['product_name'], 15); ?></h2>
						<p><?php echo  $fm->textShorten($resultApple['product_desc'], 35); ?></p>
						<div class="button"><span><a href="details.php?proid=<?php echo $resultApple['product_id']; ?>">Chi tiết</a></span></div>
					</div>
				</div>
			<?php
			}
			?>
			<?php
			$getLatestSS = $product->getLastestByBrand(2);
			if ($getLatestSS && $resultSS = $getLatestSS->fetch_assoc()) {
			?>
				<div class="listview_1_of_2 images_1_of_2">
					<div class="listimg listimg_2_of_1">
						<a href="details.php"><img src="admin/uploads/<?php echo $resultSS['image']; ?>" alt="Ảnh sản phẩm" /></a>
					</div>
					<div class="text list_2_of_1">
						<h2><?php echo $fm->textShorten($resultSS['product_name'], 15); ?></h2>
						<p><?php echo  $fm->textShorten($resultSS['product_desc'], 35); ?></p>
						<div class="button"><span><a href="details.php?proid=<?php echo $resultSS['product_id']; ?>">Chi tiết</a></span></div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
		<div class="section group">
			<?php
			$getLatestXiaomi = $product->getLastestByBrand(3);
			if ($getLatestXiaomi && $resultXiaomi = $getLatestXiaomi->fetch_assoc()) {
			?>
				<div class="listview_1_of_2 images_1_of_2">
					<div class="listimg listimg_2_of_1">
						<a href="details.php"><img src="admin/uploads/<?php echo $resultXiaomi['image']; ?>" alt="Ảnh sản phẩm" /></a>
					</div>
					<div class="text list_2_of_1">
						<h2><?php echo $fm->textShorten($resultXiaomi['product_name'], 15); ?></h2>
						<p><?php echo  $fm->textShorten($resultXiaomi['product_desc'], 35); ?></p>
						<div class="button"><span><a href="details.php?proid=<?php echo $resultXiaomi['product_id']; ?>">Chi tiết</a></span></div>
					</div>
				</div>
			<?php
			}
			?>
			<?php
			$getLatestOppo = $product->getLastestByBrand(4);
			if ($getLatestOppo && $resultOppo = $getLatestOppo->fetch_assoc()) {
			?>
				<div class="listview_1_of_2 images_1_of_2">
					<div class="listimg listimg_2_of_1">
						<a href="details.php"><img src="admin/uploads/<?php echo $resultOppo['image']; ?>" alt="Ảnh sản phẩm" /></a>
					</div>
					<div class="text list_2_of_1">
						<h2><?php echo $fm->textShorten($resultOppo['product_name'], 15); ?></h2>
						<p><?php echo  $fm->textShorten($resultOppo['product_desc'], 35); ?></p>
						<div class="button"><span><a href="details.php?proid=<?php echo $resultOppo['product_id']; ?>">Chi tiết</a></span></div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="header_bottom_right_images">
		<!-- FlexSlider -->
		<section class="slider">
			<div class="flexslider">
				<ul class="slides">
					<?php
					$getSlider = $slider->get_active_sliders();
					if ($getSlider) {
						while ($resultSlider = $getSlider->fetch_assoc()) {
					?>
							<li><img src="admin/uploads/<?php echo $resultSlider['slider_image']; ?>" alt="<?php echo $resultSlider['slider_name']; ?>" /></li>
					<?php
						}
					}
					?>
				</ul>
			</div>
		</section>
		<!-- FlexSlider -->
	</div>
	<div class="clear"></div>
</div>