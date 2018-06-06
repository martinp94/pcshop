<?php
	require_once 'core/init.php';

	$user = new User();
	if($user->isLoggedIn()) {
		Redirect::to('index.php');
	}

	if(Input::exists()){
		if(Token::check(Input::get('token'))) {
			$validate = new Validate();

			$validation = $validate->check($_POST, array(
				'username' => array('required' => true),
				'password' => array('required' => true)
			));


			if($validation->passed()){
				// Log user in
				$user = new User();

				$remember = (Input::get('remember') === 'on') ? true : false;
				$login = $user->login(Input::get('username'), Input::get('password'), $remember);

				if($login) {
					Redirect::to('index.php');
				} 
			} else {
				foreach ($validation->errors() as $error) {
					echo $error . '<br>';
				}
			}
		}
	}
?>


<div class="jumbotron text-center">
	<form action="login.php" method="post">

	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="form-group">
			<label for="username">Korisničko ime</label>
			<input class="form-control" type="text" name="username" id="username" autocomplete="off" />
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="form-group">
			<label for="password">Lozinka</label>
			<input class="form-control" type="password" name="password" id="password" autocomplete="off" />
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="form-group">
			<label for="remember"> Zapamti me</label>
			<input type="checkbox" name="remember" id="remember" />
			</div>
		</div>
	</div>
		
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<input class="form-control btn btn-success" type="submit" value="Uloguj se" />
		</div>
	</div>

			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	</form>
</div>