<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$shop = array();
$categoryName = 'Kategorija';

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if($user->hasShop()) {
	if(!$user->shop()->isAccepted()) {
		Redirect::to('index.php');
	}
	
	$shop = $user->shop();
} 

if(isset($_GET['addproduct'])) {
	if($_GET['addproduct'] != '') {
		switch ($_GET['addproduct']) {
			case 'desktop':
				$categoryName = 'Desktop računar';
				break;

			case 'laptop':
				$categoryName = 'Laptop računar';
				break;

			case 'tablet':
				$categoryName = 'Tablet računar';
				break;

			case 'mobilni':
				$categoryName = 'Mobilni telefon';
				break;

			case 'komponente':
				$categoryName = 'Računarska komponenta';
				break;
			
			default:
				$categoryName = 'Kategorija';
				break;
		}

		Session::put('categoryName', $categoryName);
	}
}

?>

<div class="text-center">
<h1 class="text-primary">Dodavanje proizvoda</h1>
	
	<?php
		if(!Session::exists('productAddStep')) {
	?>
	<h4 id="categoryname"><?php echo $categoryName; ?></h4>

	<?php
		}
	?>

	<div id="categorylist">
		<select id="category">

		<option>Izaberi</option>

		<option>Desktop računar</option>

		<option>Laptop računar</option>

		<option>Tablet računar</option>

		<option>Mobilni telefon</option>

		<option>Računarske komponente</option>

		</select>
	</div>
	

</div>

<div class="container-fluid">

	<?php

		if(isset($_GET['addproduct'])) {
			if($_GET['addproduct'] != '') {

				
				include 'addproductform.php';
			}
		}

	?>
	
</div>


<script type="text/javascript">
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

				
				window.location.href = "index.php?shop&addproduct=" + category;
			}

			
		});

		if($("#categoryname").html() != 'Kategorija'){
			$("#categorylist").remove();
		}


		

	});
</script>

