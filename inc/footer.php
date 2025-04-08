</div>
   <div class="footer">
   	  <div class="wrapper">	
	     <div class="section group">
				<div class="col_1_of_4 span_1_of_4">
						<h4>Information</h4>
						<ul>
						<li><a href="#">About Us</a></li>
						<li><a href="#">Customer Service</a></li>
						<li><a href="#"><span>Advanced Search</span></a></li>
						<li><a href="#">Orders and Returns</a></li>
						<li><a href="#"><span>Contact Us</span></a></li>
						</ul>
					</div>
				<div class="col_1_of_4 span_1_of_4">
					<h4>Why buy from us</h4>
						<ul>
						<li><a href="about.php">About Us</a></li>
						<li><a href="faq.php">Customer Service</a></li>
						<li><a href="#">Privacy Policy</a></li>
						<li><a href="contact.php"><span>Site Map</span></a></li>
						<li><a href="details.php"><span>Search Terms</span></a></li>
						</ul>
				</div>
				<div class="col_1_of_4 span_1_of_4">
					<h4>My account</h4>
						<ul>
							<li><a href="contact.php">Sign In</a></li>
							<li><a href="index.php">View Cart</a></li>
							<li><a href="#">My Wishlist</a></li>
							<li><a href="#">Track My Order</a></li>
							<li><a href="faq.php">Help</a></li>
						</ul>
				</div>
				<div class="col_1_of_4 span_1_of_4">
					<h4>Contact</h4>
						<ul>
							<li><span>+88-01713458599</span></li>
							<li><span>+88-01813458552</span></li>
						</ul>
						<div class="social-icons">
							<h4>Follow Us</h4>
					   		  <ul>
							      <li class="facebook"><a href="#" target="_blank"> </a></li>
							      <li class="twitter"><a href="#" target="_blank"> </a></li>
							      <li class="googleplus"><a href="#" target="_blank"> </a></li>
							      <li class="contact"><a href="#" target="_blank"> </a></li>
							      <div class="clear"></div>
						     </ul>
   	 					</div>
				</div>
			</div>
			<div class="copy_right">
				<p>Training with live project &amp; All rights Reseverd </p>
		   </div>
     	</div>
    </div>
	<script>
	$('.buy_checked').change(function(){
		var id_cart = $(this).val();
        if($(this).is(':checked')){
			var cart_status = 1;
			$.ajax({
				url: 'ajax/stick_buy.php',
                type: 'post',
                data: {id_cart: id_cart, cart_status: cart_status},
                success: function(response){
                    console.log(response);
                }
			})
		} else {
			var cart_status = 0;
            $.ajax({
                url: 'ajax/stick_buy.php',
                type: 'post',
                data: {id_cart: id_cart, cart_status: cart_status},
                success: function(response){
                    console.log(response);
                }
            })
		}
	})
 </script>
    <script type="text/javascript">
		$(document).ready(function() {
			/*
			var defaults = {
	  			containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
	 		};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
	</script>
    <a href="#" id="toTop" style="display: block;"><span id="toTopHover" style="opacity: 1;"></span></a>
    <link href="css/flexslider.css" rel='stylesheet' type='text/css' />
	  <script defer src="js/jquery.flexslider.js"></script>
	  <script type="text/javascript">
		$(function(){
		  SyntaxHighlighter.all();
		});
		$(window).load(function(){
		  $('.flexslider').flexslider({
			animation: "slide",
			start: function(slider){
			  $('body').removeClass('loading');
			}
		  });
		});
	  </script>
	  <script>
				 function remove_background(product_id)
					{
						for(var count = 1; count <= 5; count++)
						{
						$('#'+product_id+'-'+count).css('color', '#ccc'); 
						}
					}
					//hover chuột đánh giá sao
					$(document).on('mouseenter', '.rating', function(){
							var index = $(this).data("index"); //3
							var product_id = $(this).data('product_id'); //13
						
							// alert(index);
							// alert(product_id);
							remove_background(product_id);
							for(var count = 1; count<=index; count++)
							{
							$('#'+product_id+'-'+count).css('color', '#ffcc00');
							}
					});
					  //nhả chuột ko đánh giá
						$(document).on('mouseleave', '.rating', function(){
							var index = $(this).data("index");
							var product_id = $(this).data('product_id');
							var rating = $(this).data("rating");
							remove_background(product_id);
							//alert(rating);
							for(var count = 1; count<=rating; count++)
							{
							$('#'+product_id+'-'+count).css('color', '#ffcc00');
							}
							});

				</script>
				<script>
					 $('.rating').click(function(){
						var index = $(this).data("index"); //3
						var product_id = $(this).data('product_id');
						var customer_id = $(this).data('customer_id');
						$.ajax(
								{ url: 'ajax/rating.php',
									data: {index:index, product_id:product_id, customer_id:customer_id},
									type: 'POST',
									success: function(data) {
										
											alert('Đánh giá '+index+' sao thành công');
										
											

											}
							});
					})
					$('.rating_login').click(function(){
						alert('Vui lòng đăng nhập để đánh giá sản phẩm');
                        window.location.href = "login.php";
					})
				</script>
</body>
</html>
