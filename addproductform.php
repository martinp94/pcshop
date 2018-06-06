<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$shop = array();
$category = 'Kategorija';
$step = Session::exists('productAddStep') ? Session::get('productAddStep') : 1;
$errors = array();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if($user->hasShop()) {
	if(!$user->shop()->isAccepted()) {
		Redirect::to('index.php');
	}

	$shop = $user->shop();
	
} 

if(Session::exists('categoryName')) {
	
	$category = Session::get('categoryName');
	if($category == 'Kategorija') {
		Redirect::to('index.php?shop&addproduct');
	}
}


if(Input::exists()) {
	if(Token::check(Input::get('token'))) {

		$validate = new Validate();

		if(isset($_POST['submitbasic'])) {
			
				if(Input::get('brand') != '' && Input::get('model') != '' && Input::get('price') != '' && Input::get('producttype') != '') {

					$validation = $validate->check($_POST, array(

						'brand' => array(
							'required' => true,
							'min' => 2,
							'max' => 45
						),
						'model' => array(
							'required' => true,
							'min' => 2,
							'max' => 45
						),
						'price' => array(
							'required' => true
						)
					));

					if($validation->passed()) {

						if($shop->addProduct($shop->data()->id,Input::get('brand'), Input::get('model'), Input::get('price'), Input::get('producttype'))) {


							Redirect::to('index.php?shop&addproduct=' .  Input::get('producttype'));

						}



					} else {
						
						foreach ($validation as $error) {
							$errors[] = $error;
							$_SESSION['errors'] = $errors;
						}
					}

				}

		}

		if(isset($_POST['submitspecs'])) {

			if(Session::exists('specifications')) {
				$specs = Session::get('specifications');

				$valArr = array();

				foreach ($specs as $key => $spec) {
					if(Input::get($key) == '') {


						Session::delete('productAddStep');
					}

					$valArr[$key] = array(

						'required' => true,
						'min' => 6,
						'max' => 45

					);
				}

				$validation = $validate->check($_POST,$valArr);

				if($validation->passed()) {
					
					$fields = array();

					foreach ($specs as $key => $spec) {
						
						$fields[$key] = Input::get($key);
					}

					$fields['products_id'] = Input::get('pid');
					
					

					if($db->insert('specifications', $fields)) {
						Session::put('productAddStep', 3);
						Redirect::to('index.php?shop&addproduct=' .  Input::get('producttype'));

					} 


				} else {

					foreach ($validation->errors() as $error) {
						$errors[] = $error;
						$_SESSION['errors'] = $errors;
					}
				}


				

			} else {

				Session::delete('productAddStep');
				Redirect::to('index.php?shop&addproduct');
			}
		}

		if(isset($_POST['submitspecskomponente'])) {

			if(Session::exists('specifications')) {
				$specs = Session::get('specifications');

				$table = $specs[count($specs) - 1]->COLUMN_NAME;

				$fields = array();

				foreach ($specs as $key => $spec) {
					if ($spec->COLUMN_NAME != 'id' && $spec->COLUMN_NAME != 'products_id' && $key != (count($specs)-1)) {

						$fields[$spec->COLUMN_NAME] = Input::get($spec->COLUMN_NAME);
					}
					
				}

				$fields['products_id'] = Input::get('pid');

				if($db->insert($table, $fields)) {

					if($db->update('products', $fields['products_id'], array('komponente_type' => $table))){
						Session::put('productAddStep', 3);
						Redirect::to('index.php?shop&addproduct=' .  Input::get('producttype'));
					}
						

				}
			}
		}

	}

}


?>

<div class="row text-center">

	<?php 
		$product = Session::exists('product') ? Session::get('product') : null;

		if($step == 1) {
			
	?>

			<form method="post" action="addproductform.php">
				
				<div class="col-md-6 col-md-offset-3">
					
						
						<div class="form-group">

							<label for="brand">Brend</label>
							<input type="text" class="form-control text-center" name="brand" />
							
						</div>

						<div class="form-group">

							<label for="model">Model</label>
							<input type="text" class="form-control text-center" name="model" />
							
						</div>

						<div class="form-group">

							<label for="price">Cijena</label>
							<input type="text" class="form-control text-center price" name="price" /> 
							
						</div>

						<div class="form-group">

							<button type="submit" name="submitbasic" class="btn btn-success form-control"> Potvrdi</button>


							<input type="hidden" name="producttype" value="<?php echo $_GET['addproduct']; ?>" />
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
							
						</div>



					
				</div>


			</form>


	<?php
		
		} else if($step == 2) {

	?>

			<form method="post" action="addproductform.php">
				

				<?php

					$specifications = array();
					
					if($shop->lastAddedProduct($shop->data()->id) != null) {
						$product = $shop->lastAddedProduct($shop->data()->id);

						Session::put('product', $product);
						switch ($product->type) {
							case 'desktop':
								$specifications = array(

									'processor' => 'Procesor',
									'ram' => 'Ram memorija',
									'mbo' => 'Matična ploča',
									'gpu' => 'Grafička kartica',
									'hdd' => 'Hard disk',
									'ssd' => 'Solid state drive',
									'case' => 'Kućište',
									'psu' => 'Napajanje',
									'software' => 'Operativni sistem',
									'other' => 'Ostalo'

								);
								break;

							case 'laptop':
								
								$specifications = array(

									'processor' => 'Procesor',
									'ram' => 'Ram memorija',
									'gpu' => 'Grafička kartica',
									'hdd' => 'Hard disk',
									'ssd' => 'Solid state drive',
									'case' => 'Kućište',
									'display' => 'Displej',
									'battery' => 'Baterija',
									'psu' => 'Punjač',
									'cam' => 'Kamera',
									'net' => 'Konekcija',
									'ports' => 'Portovi',
									'card_reader' => 'Čitač kartica',
									'keyboard' => 'Tastatura',
									'software' => 'Operativni sistem',
									'other' => 'Ostalo'

								);
								break;

							case 'mobilni':
								
								$specifications = array(

									'processor' => 'Procesor',
									'ram' => 'Ram memorija',
									'case' => 'Kućište',
									'display' => 'Displej',
									'battery' => 'Baterija',
									'cam' => 'Kamera',
									'net' => 'Konekcija',
									'card_reader' => 'Čitač kartica',
									'keyboard' => 'Tastatura',
									'software' => 'Operativni sistem',
									'other' => 'Ostalo'

								);

								break;

							case 'tablet':
								
								$specifications = array(

									'processor' => 'Procesor',
									'ram' => 'Ram memorija',
									'case' => 'Kućište',
									'display' => 'Displej',
									'battery' => 'Baterija',
									'cam' => 'Kamera',
									'net' => 'Konekcija',
									'card_reader' => 'Čitač kartica',
									'keyboard' => 'Tastatura',
									'software' => 'Operativni sistem',
									'other' => 'Ostalo'

								);

								break;
							
							default:
								# code...
								break;
						}
					} else {
						Session::delete('productAddStep');
						Redirect::to('index.php');
					}

					Session::put('specifications', $specifications);

				?>

				<div class="col-md-8 col-md-offset-2">
					
				<?php

					if($product->type == 'komponente') {

						$tableName = '';
						
						if(isset($_GET['category'])) {
							if($_GET['category'] != '') {

								switch ($_GET['category']) {
									case 'procesor':
										$tableName = 'processors';
										break;

									case 'rammemorija':
										$tableName = 'ram_mem';
										break;

									case 'matičnaploča':
										$tableName = 'mobos';
										break;

									case 'grafičkakartica':
										$tableName = 'gpus';
										break;

									case 'harddisk':
										$tableName = 'hdds';
										break;

									case 'solidstatedrive':
										$tableName = 'ssds';
										break;

									case 'napajanje':
										$tableName = 'psus';
										break;

									case 'kućište':
										$tableName = 'cases';
										break;
									
									default:
										
										break;
								}

								if($tableName != '') {

									$query = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "."'pcshopmp'"." AND TABLE_NAME = '".$tableName."'");

									if($query->count()) {
										
										$specifications = $query->results();
										$specifications[] = (object)array('COLUMN_NAME' => $tableName);
										Session::put('specifications', $specifications);
											
											foreach ($specifications as $key => $value) {
												if($value->COLUMN_NAME != 'id' && $value->COLUMN_NAME != 'products_id'  && $key != (count($specifications)-1)){
													
				?>
													<div class="form-group">

														<label for="<?php echo $value->COLUMN_NAME; ?>"><?php echo $value->COLUMN_NAME; ?></label>
														<input type="text" class="form-control text-center" name="<?php echo $value->COLUMN_NAME; ?>" />
														
													</div>
					<?php
												}
											}
					?>
													<div class="form-group">

														<button type="submit" name="submitspecskomponente" class="btn btn-success form-control"> Potvrdi</button>
														<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
														<input type="hidden" name="pid" value="<?php echo $product->lastid; ?>" />
														<input type="hidden" name="producttype" value="<?php echo $_GET['addproduct']; ?>" />
							
													</div>

					<?php
									}
										
								}

							} 
						}
						
					
					?>

						<div id="komponentelist">
							<select id="komponente">

								<option>Izaberi</option>

								<option>Procesor</option>

								<option>Ram memorija</option>

								<option>Matična ploča</option>

								<option>Grafička kartica</option>

								<option>Hard disk</option>

								<option>Solid state drive</option>

								<option>Napajanje</option>

								<option>Kućište</option>

							</select>
						</div>

					<?php

					} else {
						
						foreach ($specifications as $key => $value) {
					?>
						
						<div class="form-group">

							<label for="<?php echo $key; ?>"><?php echo $value; ?></label>
							<input type="text" class="form-control text-center" name="<?php echo $key; ?>" />
							
						</div>

					<?php
						}
					?>	
						

						<div class="form-group">

							<button type="submit" name="submitspecs" class="btn btn-success form-control"> Potvrdi</button>
							<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
							<input type="hidden" name="pid" value="<?php echo $product->lastid; ?>" />
							<input type="hidden" name="producttype" value="<?php echo $_GET['addproduct']; ?>" />
							
						</div>

				<?php
					}
				?>
				</div>



			</form>

		<?php	
		 } else if($step == 3) {
				


		?>

		<h2 class="text-primary bg-light">Dodavanje slike</h2>

		<hr>
		<br>

		<form action="imgupload.php?productimg" method="post" enctype="multipart/form-data">
	          		<input type="hidden" name="size" value="1000000" />
	          		<div class="col-md-offset-3">
	          			
	          			<input type="file" name="imageToUpload" />
	          		</div>
	          		<br>
	          		<div class="form-group">
	          			<button type="submit" class="btn btn-success">Dodaj sliku</button>
	          		</div>

	          		<input type="hidden" name="producttype" value="<?php echo $_GET['addproduct']; ?>" />
	          		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	    </form>

		<?php
			} else if ($step == 4) {

				Session::delete('productAddStep');
		?>

		<div  class="bg-success">
			<h3><strong class="text-primary">Uspješno ste dodali proizvod! Kliknite na dugme da se vratite na prodavnicu.</strong> </h3>
			<button class="btn btn-success" onclick="window.location.href='index.php?shop&info'">Prodavnica</button>
		</div>
		

	<?php
		}
	?>

</div>

<script>
	
	$(function(){
		$('.price').mask('000.000.000.000.000.00€', {reverse: true});

		$("#komponente").change(function(){

			var category = 'Izaberi';

			if($(this).find(":selected").text() != 'Izaberi') {
				category = $(this).find(":selected").text();
				switch(category){
					case 'Procesor':
						category = category.toLowerCase();
						break;

					case 'Ram memorija':
						category = category.replace(' ','').toLowerCase();
						break;

					case 'Matična ploča':
						category = category.replace(' ','').toLowerCase();
						break;

					case 'Grafička kartica':
						category = category.replace(' ','').toLowerCase();
						break;

					case 'Hard disk':
						category = category.replace(' ','').toLowerCase();
						break;

					case 'Solid state drive':
						category = category.replace(' ','').replace(' ','').toLowerCase();
						break;

					case 'Napajanje':
						category = category.toLowerCase();
						break;

					case 'Kućište':
						category = category.toLowerCase();
						break;

					default:
						break;
				}

				
				window.location.href = "index.php?shop&addproduct=komponente&category=" + category;
			}

			
		});

		if(location.search != '?shop&addproduct=komponente'){
			$("#komponentelist").remove();
		}
		
	});

	
</script>