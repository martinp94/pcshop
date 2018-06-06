<?php

include_once 'core/init.php';

$user = new User();
$shop = null;
$db = DB::getInstance();
$toEdit = '';
$min = '';
$max = '';
$errors = array();
$url_name = '';

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if($user->hasShop()) {
	if(!$user->shop()->isAccepted()) {
		Redirect::to('index.php');
	}
	
} 

if (!$user->hasShop()){
	Redirect::to('index.php');
} else if($user->hasShop()){
	if($user->shop()->isAccepted()) {
		$shop = $user->shop();
		
	} 
	
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		if(Input::get('telToAdd_provider') !== '' && Input::get('telToAdd_num') !== '') {
			$validate = new Validate();
		
			$validation = $validate->check($_POST, array(

				'telToAdd_provider' => array(
					'required' => true,
					'min' => 3,
					'max' => 3
				),
				'telToAdd_num' => array(
					'required' => true,
					'min' => 6,
					'max' => 8
				)

			));

			if($validation->passed()) {

				try {

				
					$user->addPhone(array(

						'shop_id' => $shop->data()->id,
						'provider' => Input::get('telToAdd_provider'),
						'num' => Input::get('telToAdd_num')

					));

					Redirect::to('index.php');
				} catch(Exception $e) {
					Redirect::to('index.php');
				}
			

			} else {


				foreach ($validation->errors() as $error) {
					$errors[] = $error;
				}

					$_SESSION["errors"] = $errors;
					Redirect::to('index.php');
			}
		
			Redirect::to('index.php');
		} 

		if(Input::get('shop_name') !== ''){
			$toEdit = 'shop_name';
			$min = '6';
			$max = '45';
			$url_name = str_replace(' ', '_', Input::get('shop_name'));
		} else if (Input::get('adress') !== ''){
			$toEdit = 'adress';
			$min = '10';
			$max = '128';
		} else {
			Redirect::to('index.php?shop&info');
		}

		$validate = new Validate();

		$validation = $validate->check($_POST, array(

			$toEdit => array(
				'required' => true,
				'min' => $min,
				'max' => $max
			)

		));

		if($validation->passed()) {

			$fields = array();

			if($url_name != '') {
				$fields = array(

				$toEdit => Input::get($toEdit),
				'url_name' => $url_name
				
				);
			} else {
				$fields = array(

				$toEdit => Input::get($toEdit)

				);
			}
			

			if($db->update('shops', $shop->data()->id, $fields)){

				Redirect::to('index.php?shop&edit');

			} else {
				Redirect::to('index.php?shop&edit');
			}

		} else {


			foreach ($validation->errors() as $error) {
				$errors[] = $error;
			}

			$_SESSION["errors"] = $errors;
			Redirect::to('index.php');
		}
	}
}

?>

<div class="container-fluid shopEditDiv">
		<div class="row">
        <div class="col-md-4">
          

          <?php
            if($shop->data()->image !== null){

          ?>

              <img src="images/uploads/shop_images/<?php echo $shop->data()->image; ?>" class="img-fluid img-thumbnail" alt="profilna slika" >

          <?php
            } else {
          ?>
              <img src="images/shop.png" class="img-fluid img-thumbnail" alt="profilna slika" >
          <?php
            }
          ?>





          <form action="imgupload.php?shopimg" method="post" enctype="multipart/form-data">
          		<input type="hidden" name="size" value="1000000" />
          		<div>
          			<input type="file" name="imageToUpload" />
          		</div>
          		<div>
          			<button type="submit" class="btn btn-success">Promjeni</button>
          		</div>
          		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
          </form>
        
        </div>
        <div class="col-md-8">
          <h3><?php echo escape($shop->data()->shop_name); ?></h3>
        </div>
    	</div>
</div>

<div class="container-fluid shopEditDiv">
	<div class="row">
		<div class="col-md-12">
			<form action="shopedit.php" method="post">
				<table class="table table-dark table-hover">
  					<tbody>
					    <tr>
					      <th scope="row">Naziv prodavnice</th>
					      <td><input type="text" name="shop_name" placeholder="<?php echo escape($shop->data()->shop_name); ?>" /></td>
					      <td><button type="submit" class="btn btn-info">Izmjeni</button></td>
					      
					    </tr>
					    <tr>
					      <th scope="row">Adresa</th>
					      <td><input type="text" name="adress" placeholder="<?php echo escape($shop->data()->adress); ?>" /></td>
					      <td><button type="submit" class="btn btn-info">Izmjeni</button></td>
					      
					    </tr>

					    <tr>
					      <th scope="row">Telefoni</th>
					      <td colspan="2"></td>
					      
					    </tr>

					    <?php 
					      	$phones = $shop->phoneNumbers();
					        if(!empty($phones)) {
					        	foreach ($phones as $telRow) {
					    ?>

						<tr>
						      
						    <th scope="row"></th>
						    <td colspan="2"><?php echo $telRow->provider . '-' . $telRow->num; ?></td>
						      
						</tr>


				        <?php 
					      		}
					      	}

				        ?>



					    <tr>
					    	<td><select name="telToAdd_provider">
					    		<option value="067">067</option>
					    		<option value="068">068</option>
					    		<option value="069">069</option>
					    	</select></td>
					    	<td><input type="text" name="telToAdd_num" placeholder="Telefon" /></td>
					    	<td><button type="submit" class="btn btn-info">Dodaj telefon</button></td>
					    </tr>
  					</tbody>
				</table>
					
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
			</form>
		</div>
	</div>
</div>