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

if($user->hasShop()){
	if($user->shop()->isAccepted()) {
		$shop = $user->shop();

		$products = $shop->products();
		
		if($products != null) {		
?>
		<br>

		<div id="categorylist">
			Kategorija: 
		
			<select id="category">

			<option>Izaberi</option>

			<option>Desktop računar</option>

			<option>Laptop računar</option>

			<option>Tablet računar</option>

			<option>Mobilni telefon</option>

			<option>Računarske komponente</option>

			</select>
		</div>

		<br>

		<div class="row text-center">
			<div class="col-md-3">
				<strong>Slika</strong>
			</div>
			<div class="col-md-3">
				<strong>Osnovno</strong>
			</div>
			<div class="col-md-6">
				<strong>Specifikacije</strong>
			</div>
		</div>

		<?php

			if(isset($_GET['category'])){

				$tmp = array();

				foreach ($products as $product) {
					if($product->type == $_GET['category']) {

						$tmp[] = $product;
					}
				}

				$products = $tmp;
			}

			
			foreach ($products as $product) {
		?>
			<br>
		
			<div class="row">

				<div class="col-md-3">

					<img src="images/uploads/product_images/<?php echo $product->image; ?>" class="img-fluid img-thumbnail img-responsive" alt="slika proizvoda" >
					
				</div>

				<div class="col-md-3 text-center">
					
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

		<?php

				if($product->komponente_type != null){
					$specs = $db->get($product->komponente_type, array('products_id', '=', $product->id));
				} else {
					$specs = $db->get('specifications', array('products_id', '=', $product->id));
				}
				

				if($specs->count()) {
					$specs = $specs->first();

		?>

					<div class="col-md-6">

						<?php
							foreach ($specs as $key => $value) {
								if($key != 'products_id' && $key != 'id' && $value != '') {
						?>

							<?php echo $key . ': ' . $value . '<br>'; ?> 

						<?php
								}
							}
						?>
					</div>

		<?php
				} else {
					echo 'No specs';
				}

		?>

			

			</div>
			<br>
			<hr>
		<?php

							
						
			}

		}
	}
}

?>

<script>

$(function(){
	$("#category").change(function(){

		var category = 'Izaberi';

		if($(this).find(":selected").text() != 'Izaberi') {
			category = $(this).find(":selected").text();
			switch(category){
				case 'Desktop računar':
					category = category.substr(0, category.indexOf(' ')).toLowerCase();
					break;

				case 'Laptop računar':
					category = category.substr(0, category.indexOf(' ')).toLowerCase();
					break;

				case 'Tablet računar':
					category = category.substr(0, category.indexOf(' ')).toLowerCase();
					break;

				case 'Mobilni telefon':
					category = category.substr(0, category.indexOf(' ')).toLowerCase();
					break;

				case 'Računarske komponente':
					category = category.substr(category.indexOf(' ') + 1).toLowerCase();
					break;

				default:
					break;
			}

				window.location.href = "index.php?shop&products&category=" + category;
		}
	});

	if(location.search != '?shop&products'){
		$("#categorylist").remove();
	}

});

</script>