<?php 
require_once 'core/init.php';

$user = new User();
$errors = array();

if($user->isLoggedIn()){
	if($user->data()->activated == true) {
		Redirect::to('index.php');
	}
} else {
	Redirect::to('index.php');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))) {

		$validate = new Validate();

		$validation = $validate->check($_POST, array (
			'activation_code' => array(
				'required' => true,
				'min' => 6,
				'max' => 6
			)
		));

		if($validation->passed()) {

			if(Input::get('activation_code') !== $user->data()->activation_code) {
				$errors[] = 'Pogresan kod';
				$_SESSION["errors"] = $errors;
				Redirect::to('index.php');
			
			} else {

				$user->update(array (
					'activated' => true
				));

				$errors[] = 'Uspješno ste aktivirali nalog!';
				$_SESSION["errors"] = $errors;
				Redirect::to('index.php');
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

<div class="container-fluid well">
	


<h3> Vaš nalog nije aktiviran. Unesite aktivacioni kod koji vam je poslat na e-mail adresu. </h3>


<form action="activation.php" method="post">

	<div class="form-group row">
		<div class="col-xs-2 col-xs-offset-5">
			
		
		<label for="activation_code"> Aktivacioni kod </label>
		<input type="text" class="form-control" style="" name="activation_code" id="activation_code" />

		</div>
	</div>

	<button type="submit" class="btn btn-success">Potvrdi</button>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
</form>

</div>