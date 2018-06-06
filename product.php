<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$product = null;
$specs = null;
$commentEnabled = true;
$comments = null;
$cart = null;
$shop = null;

if(!$user->isLoggedIn()){
	$commentEnabled = false;
}

if(isset($_GET['proizvod'])) {
	if($_GET['proizvod'] != '') {

		$product = $db->query('SELECT pdt.id, pdt.brand, pdt.model, pdt.date_created, pdt.price, pdt.type, pdt.image, 										  pdt.komponente_type, pdt.availible, pdt.shop_id, sh.shop_name
							   FROM products pdt LEFT JOIN shops sh
							   ON pdt.shop_id = sh.id
							   WHERE pdt.id = ?', array($_GET['proizvod']));

		if($product->count()) {

			$product = $product->first();
			$shop = $product->shop_id;

			if($user->isLoggedIn()) {
				$cart = new Cart($shop, [
					'cartMaxItem' => 120,
					'itemMaxQuantity' => 100
				]);
			}

			if($product->type != 'komponente') {
				$specs = $db->get('specifications', array('products_id', '=', $_GET['proizvod']));
			} else {
				$specs = $db->get($product->komponente_type, array('products_id', '=', $_GET['proizvod']));
			}
			
			if($specs->count()) {
				$specs = $specs->first();
			} else {
				Redirect::to('index.php');
			}

			

		} else {
			Redirect::to('index.php');
		}
		
	} else {
		Redirect::to('index.php');
	}
}

if(Input::exists()) {
	
	if(isset($_POST['add'])) {
		$cart->add($product->id, $product->brand, $product->model, $product->price, $product->image);
		
	} 

	if(isset($_POST['remove'])) {
		
		$cart->removeCartItem($product->id);

	}	

	Redirect::to('index.php?proizvod=' . $product->id);
}

?>



<div class="container-fluid">

	<div class="row shopHead">
		
		<div class="col-md-1">
			<img src="images/shop.png" alt="shop" class="img-fluid" width="100">
		</div>

		<div class="col-md-9">
			<h2 style="color: white;"><strong> <?php echo $product->shop_name; ?> </strong></h2>
		</div>
		
	</div>
	
	<div class="row">

		<div class="col-md-4">
			<img src="images/uploads/product_images/<?php echo $product->image; ?>" class="img-responsive">
			
		</div>

		<div class="col-md-4 text-center">

			<div class="productInfo">
				<h2><strong><?php echo $product->brand; ?></strong></h2>
				<h3><strong><?php echo $product->model; ?></strong></h3>
				<h3><strong><?php echo number_format((float)$product->price, 2, ',', '') . ' €'; ?></strong></h3>
			</div>

			<div class="container-fluid">
				<br>
				<form method="post" action="product.php?proizvod=<?php echo $product->id; ?>">
				
					<div class="row">
						<div class="col-md-12">
							<?php 
							if($user->isLoggedIn()){
								if(empty($cart->getItems()[$product->shop_id][$product->id])) {

							
							?>
								<button id="cartButton" type="submit" name="add" class="btn btn-primary"> Dodaj u korpu </button>
							<?php
							} else {
							?>
								<button id="cartButton" type="submit" name="remove" class="btn btn-danger"> Ukloni iz korpe </button>
							<?php
								}
							}
							?>
						</div>
					</div>

					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />

				</form>
			</div>
			
		</div>

		<div class="col-md-4">

			<div class="container-fluid text-center">
				 	<div class="productInfo">
				 	<h3>Specifikacije</h3>
				 	
				 	<?php
				 		foreach ($specs as $key => $value) {
				 			if($key != 'id' && $key != 'products_id' && $value != null) {
					?>
								<div class="row">
									<div class="col-md-6"><strong><?php echo $key; ?></strong></div>
									<div class="col-md-6"><?php echo $value; ?></div>
								</div>
								<hr>
					<?php
							}
				 		}
				 	?>
				 	</div>
			</div>
			
		</div>
	</div>
</div>

<?php

include 'productcommentsection.php';

?>

<script>
	$(".productInfo").on("mouseover", function(){
				$(this).css("background-color", "#4d94ff");
				$(this).css("color", "white");
			}).on("mouseout", function(){
				$(this).css("background-color", "white");
				$(this).css("color", "black");
			});


	<?php

		foreach ($comments as $comment) {
	?>
		$("#reply<?php echo $comment->comment_id; ?>").click(function(){
			$("#replydiv<?php echo $comment->comment_id; ?>").removeClass("hidden");
		});

	<?php
		}
	?>

	$("#cartButton").click(function(){
		//alert($(this).attr("name", "remove"));
	});

</script>

