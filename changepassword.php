<?php 
require_once 'core/init.php';


$user = new User();
$errors = array();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

if($user->data()->activated == false) {
	Redirect::to('index.php');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))) {
		
		$validate = new Validate();
			$validation = $validate->check($_POST, array (
				'password_current' => array(
					'required' => true,
					'min' => 6
				),
				'password_new' => array(
					'required' => true,
					'min' => 6
				),
				'password_new_again' => array(
					'required' => true,
					'min' => 6,
					'matches' => 'password_new'
				)
		));

		if($validation->passed()) {
			if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
				$errors[] = 'Pogrešna lozinka!';
				$_SESSION["errors"] = $errors;
				Redirect::to('index.php');
			
			} else {
				
				$salt = Hash::salt(32);
				$user->update(array (
					'password' => Hash::make(Input::get('password_new'), $salt),
					'salt' => $salt
				));

				$errors[] = 'Uspješno ste promijenili lozinku!';
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




<div class="container-fluid">
	<div class="row text-center" style="color: white;">
		<div id="changePW" class="col-md-4  col-md-offset-4">
			<h2 style="color: white;">Promjena lozinke</h2>

			<hr>

			<form action="changepassword.php" method="post">
			<div class="form-group">
				<label for="password_current"> Trenutna lozinka </label>
				<input type="password" class="form-control" name="password_current" id="password_current" />
			</div>

			<div class="form-group">
				<label for="password_new"> Nova lozinka </label>
				<input type="password" class="form-control" name="password_new" id="password_new" />
			</div>

			<div class="form-group">
				<label for="password_new_again"> Potvrdi novu lozinku </label>
				<input type="password" class="form-control" name="password_new_again" id="password_new_again" />
			</div>

			<button class="btn btn-success form-control" type="submit"> Promjeni </button>
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
			</form>


		</div>
	</div>

</div>