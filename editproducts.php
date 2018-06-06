<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$shop = array();
$products = array();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if($user->hasShop()) {
	if(!$user->shop()->isAccepted()) {
		Redirect::to('index.php');
	}
} 


if(Input::exists()) {
	if(isset($_POST['submitpricechange'])) {
		if(isset($_POST['pid'])) {
			if(isset($_POST['price'])) {
				if($_POST['price'] != '') {
					$price = str_replace("€", '', $_POST['price']);

					if($db->update('products', $_POST['pid'], array('price' => $price))) {
						Redirect::to('index.php?shop&editproducts');
					}
					
				} else {
					Redirect::to('index.php?shop&editproducts');
				}
			}
		}
	}

	if(isset($_POST['submitavailible'])) {
		if(isset($_POST['pid'])) {
			if(isset($_POST['pavailible'])) {
				$availible = 1 - $_POST['pavailible'];
				echo $availible;
			}

			if($db->update('products', $_POST['pid'], array('availible' => $availible))) {
						Redirect::to('index.php?shop&editproducts');
			} 

		}
		
	}
	
}


if($user->hasShop()){

	if($user->shop()->isAccepted()) {
		$shop = $user->shop();



		$products = $shop->products();
		
		if($products != null) {
?>
		
		<div class="container-fluid">
			
			<div class="row text-center">
				<div class="col-md-3">
					<strong>Slika</strong>
				</div>
				<div class="col-md-2">
					<strong>Osnovno</strong>
				</div>
				<div class="col-md-7">
					<strong>Opcije</strong>
				</div>
		</div>

<?php
			foreach ($products as $product) {
?>
			<hr>
			<br>

				<div class="row text-center">

					<div class="col-md-3">
						<img src="images/uploads/product_images/<?php echo $product->image; ?>" class="img-fluid img-thumbnail img-responsive" alt="slika proizvoda">
					</div>

					<div class="col-md-2">
						<?php echo $product->brand . '<br>'; ?>
						<?php echo $product->model . '<br>'; ?>
						<?php 
							if($product->availible == 0) {
								echo '<strong style="color:red;"> Nedostupno </strong>';
							} else {
								echo number_format((float)$product->price, 2, ',', '') . ' €'; 
							}
						?>
					</div>

					<div class="col-md-7">
						<form method="post" action="editproducts.php">

							<div class="form-group">
								
								
									<label for="price">Cijena</label>
									<input type="text" name="price" class="price" placeholder="<?php echo number_format((float)$product->price, 2, ',', '') . ' €'; ?>">

									<button type="submit" name="submitpricechange" class="btn btn-info">Promjeni
									</button>

							</div>

							<div class="form-group">
									<?php
										if($product->availible == 0) {
									?>
										<button type="submit" name="submitavailible" class="btn btn-success form-control">Dostupno</button>
									<?php
										} else {
									?>
										<button type="submit" name="submitavailible" class="btn btn-danger form-control">Nedostupno</button>
									<?php
										}
									?>
								<input type="hidden" name="pavailible" value="<?php echo $product->availible; ?>">
							</div>


							<div class="form-group">
								<input type="hidden" name="pid" value="<?php echo $product->id; ?>">
								
							</div>
							
							
						</form>
					</div>
					
				</div>
			<br>
				
<?php
			}
		}
	}
}

		
		
?>

		</div>


<script>
	
	$(function(){
		$('.price').mask('000.000.000.000.000.00€', {reverse: true});
	});

</script>