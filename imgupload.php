<?php
require_once 'core/init.php';

$user = new User();
$size = 0;
$uploadOk = 0;
$target_file = null;
$target_dir = "images/uploads/";
$uploadFor = 'user';
$db = DB::getInstance();
$errors = array();

$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

if(isset($_GET['userimg'])){
	$target_dir .= "profile_images/";
	$uploadFor = 'user';
} else if(isset($_GET['shopimg'])) {
	$target_dir .= "shop_images/";
	$uploadFor = 'shop';
} else if(isset($_GET['productimg'])) {
	$target_dir .= "product_images/";
	$uploadFor = 'product';
} else {
	Redirect::to('index.php');
}

if(Input::get('token')) {
	if(Input::get('size')){
		if(isset($_FILES['imageToUpload'])) {
			$file_name = $_FILES['imageToUpload']['name'];
			
				if($_FILES['imageToUpload']['error'] !== UPLOAD_ERR_OK){
					if($_FILES['imageToUpload']['error'] === UPLOAD_ERR_INI_SIZE)
					die('File too big');
				}

					$size = $_FILES['imageToUpload']['size'];

					if($size > Input::get('size')) {
						$uploadOk = 0;
					} else {
				
						$imageFileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));

						if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && 
							$imageFileType != "gif" ) {
		    				$uploadOk = 0;
						
						} else {
					
							$file_name_to_save = '';
					
							if($uploadFor == 'user') {
								
								$file_name_to_save = $user->data()->username . 'profileimg' . time() . strtolower(generate_random_string(9)) . '.' . $imageFileType;
							
							} else if($uploadFor == 'shop') {
								
								if($user->hasShop()){
									$file_name_to_save = $user->shop()[0]->url_name . 'shopimg' . time() . strtolower(generate_random_string(9)) . '.' . $imageFileType;
								} else {
									return;
								}
						
							} else if($uploadFor == 'product') {

								$product = Session::get('product');
								$file_name_to_save = $product->lastid . 'productimg' . time() . strtolower(generate_random_string(9)) . '.' . $imageFileType;
							}

							$target_file = $target_dir . basename($file_name_to_save);
							
							if(file_exists($target_file)) {
								$uploadOk = 0;
							} else {
								
								if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file)) {

		        					$errors[] = "The file ". basename($file_name_to_save). " has been uploaded.";
		        					$Session['errors'] = $error;

		        					if($uploadFor == 'user') {
										$user->update(array(

		        						'profile_image' => $file_name_to_save

		        					), $user->data()->id);

									} else if($uploadFor == 'shop'){
										
										$fields = array(
											'image' => $file_name_to_save
										);

										$db->update('shops', $user->shop()[0]->id, $fields);
								
									} else if($uploadFor == 'product') {

										if(Session::exists('product')) {
											if(Input::get('producttype') != '') {
												
												$product = Session::get('product');

												$fields = array(
													'image' => $file_name_to_save
												);

												$db->update('products', $product->lastid, $fields);
												
												Session::put('productAddStep', 4);
												Redirect::to('index.php?shop&addproduct=' .  Input::get('producttype'));
											} else {
												Redirect::to('index.php?shop&addproduct=' .  Input::get('producttype'));
											}
										}	
									}


		    					} else {
		        					$errors[] = "Greška prilikom čuvanja slike.";
		        					$_SESSION['errors'] = $errors;
		        					Redirect::to('index.php');
								}
							}
					
						}
					}

		} 
	}
}

