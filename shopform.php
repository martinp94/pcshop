<?php

include_once 'core/init.php';
$errors = array();
$user = new User();
$db = DB::getInstance();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

if(Input::exists()){
	
	$validate = new Validate();

		$validation = $validate->check($_POST,array(

			'shop_name' => array(
				'required' => 'true',
				'min' => 6,
				'max' => 45
			),
			'mbp' => array(
				'required' => 'true',
				'min' => 8,
				'max' => 8
			),
			'pib' => array(
				'required' => 'true',
				'min' => 9,
				'max' => 9
			),
			'adress' => array(
				'required' => 'true',
				'min' => 10,
				'max' => 128
			)
		));

		if($validation->passed()){

			$fields = array(

				'user_id' => $user->data()->id,
				'shop_name' => Input::get('shop_name'),
				'url_name' => str_replace(' ', '_', Input::get('shop_name')),
				'mbp' => Input::get('mbp'),
				'pib' => Input::get('pib'),
				'adress' => Input::get('adress'),
				'joined' => date('Y-m-d H:i:s'),
				'accepted' => false
			);

			if($db->insert('shops', $fields)) {
				$errors[] = 'Uspješna prijava';
			} else {
				$errors[] = 'Greška';
			}

			$_SESSION['errors'] = $errors;
			Redirect::to('index.php');


		} else {

			foreach ($validation->errors() as $error) {
				$errors[] = $error;
				
			}

			$_SESSION['errors'] = $errors;
			Redirect::to('index.php');
		}
} 

if(!$user->hasShop()){

?>

<form method="post" action="shopform.php">
	
	<div class="container-fluid" style="border: 1px solid aquamarine;">
		<div class="row well">
			<div class="col-md-12">
				<h3 class="text-center text-info">Prijava prodavnice</h3>
			</div>
		</div>

		<div class="form-group">

			<div class="row">
				<div class="col-md-4">
					<label for="shop_name">Naziv prodavnice</label>
				</div>

				<div class="col-md-8">
					<input class="form-control" type="text" name="shop_name"  />
				</div>
			</div>

		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-4">
					<label for="mbp">Matični broj firme</label>
				</div>

				<div class="col-md-8">
					<input class="form-control" type="text" name="mbp"  />
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-4">
					<label for="pib">PIB broj</label>
				</div>

				<div class="col-md-8">
					<input class="form-control" type="text" name="pib"  />
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-4">
					<label for="adress">Adresa </label>
				</div>

				<div class="col-md-8">
					<input class="form-control" type="text" name="adress"  />
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
					
					<button class="form-control btn btn-success" type="submit" name="submit"> Potvrdi  </button>
				
				</div>
			</div>
		</div>
		
	</div>

</form>

<?php
} else if($user->hasShop() && !$user->shop()->isAccepted()) {
	
?>

<div class="container-fluid well">
	<div class="row">
		<div class="col-md-12">
			<h2 class="text-info text-center">Obavještenje</h2>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">

			<p class="text-danger">Već ste prijavili svoju prodavnicu, administrator treba da je odobri ili odbije u narednih 24 sata.</p>
		</div>
		
	</div>
</div>

<?php
	

} else if ($user->hasShop()) {

	if($user->shop()->isAccepted()) {
		Redirect::to('index.php?shop&info');
	}
}
?>

