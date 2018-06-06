<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$products = array();
$asc = true;
if(!Session::exists('asc')) {
	Session::put('asc', $asc);
}


if(!$user->isLoggedIn()) {
	
}

if(isset($_POST['orderBy'])) {

	if($_POST['orderBy'] == 'Najjeftiniji') {
		$asc = true;
	} else if($_POST['orderBy'] == 'Najskuplji') {
		$asc = false;
	} else {
		$asc = true;
	}
	
	Session::put('asc', $asc);
}



if(isset($_GET['proizvodi'])) {
	if($_GET['proizvodi'] != '') {
		$type = $_GET['proizvodi'];
		
		if($type == 'komponente') {
			if(isset($_GET['type'])) {
				$products = $db->getOrderBy('products', array('komponente_type', '=', $_GET['type']), 'price', Session::get('asc'));
			} else {
				include 'choosecomponenttype.php';
			}
			
		} else {
			$products = $db->getOrderBy('products', array('type', '=', $type), 'price', Session::get('asc'));

		}

		if($products) {
			if($products->count()) {
			$products = $products->results();
			} else {
				Redirect::to('index.php');
			}
		}
		
		
	} else {
		Redirect::to('index.php');
	}
}


if($products) {
?>

<div class="row">
	<div class="col-md-2 col-md-offset-6">
		<select id="category">
			<?php
				if($_SESSION['asc'] == 'Najjeftiniji') {
			?>
					<option>Najjeftiniji</option>
					<option>Najskuplji</option>
			<?php 
				} else {
			?>		

					<option>Najskuplji</option>
					<option>Najjeftiniji</option>
					
			<?php
				}
			?>
		</select>
	</div>
</div>

<br>

<?php 
}
$productCounter = 0;
$itemsPerRow = 4;
$rowCount = ceil(count($products) / $itemsPerRow);

for ($i=0; $i < $rowCount; $i++) { 
	$row = array_slice($products, $i * $itemsPerRow, $itemsPerRow);
?>


	<div class="row">

<?php
	foreach ($row as $product) {
?>
		<div id="product<?php echo $productCounter; ?>"  class="col-md-3 productlistview">
			<a href="index.php?proizvod=<?php echo $product->id; ?>"><img class="img-fluid" src="images/uploads/product_images/<?php echo $product->image; ?>" /></a>
			
			<h6><strong>Brend: </strong><?php echo $product->brand; ?></h6>
			<h6><strong>Model: </strong> <?php echo $product->model; ?></h6>
			<h5><strong><strong>Cijena: </strong> <?php echo number_format((float)$product->price, 2, ',', '') . ' €'; ?></strong></h5>
		</div>
	
<?php
		$productCounter++;
	}
		
?>
	</div>

<?php
}
?>

<script>
	$(function(){

		
		<?php 
			for ($i=0; $i < $productCounter; $i++) { 
		?>

			$("#product<?php echo $i; ?>").on("mouseover", function(){
				$(this).css("background-color", "#4d94ff");
				$(this).css("color", "white");
			}).on("mouseout", function(){
				$(this).css("background-color", "white");
				$(this).css("color", "black");
			});

		<?php
			}
		?>

		$("#category").change(function(){
			var orderBy = $(this).find(":selected").text();
			
			$.post('listproducts.php?proizvodi=<?php echo $_GET['proizvodi'] ?>', {orderBy: orderBy}, function(data){
				console.log(data);
				location.href = 'index.php?proizvodi=<?php echo $_GET['proizvodi'] ?>';
			});


		});
	});
</script>