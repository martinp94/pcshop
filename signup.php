<?php 
require_once 'core/init.php';
	
	$user = new User();
	if($user->isLoggedIn()) {
		Redirect::to('index.php');
	}


?>

<div class="container-fluid">
	<div class="row">
		<div id="changePW" style="color: white;" class="col-md-6 col-md-offset-3 text-center">
			<h2> Registracija </h2>
			<hr>

			<form action="register.php" method="post">

				<div class="form-group">
					<label for="username">Korisničko ime</label>
					<input type="text" class="form-control" name="username" id="username" value="" autocomplete="off" />
				</div>

				<div class="form-group">
					<label for="email">E-mail adresa</label>
					<input type="email" class="form-control" name="email" id="email" value="" autocomplete="off" />

				</div>

				<div class="form-group">
					<label for="password">Lozinka</label>
					<input type="password" class="form-control" name="password" id="password" />

				</div>

				<div class="form-group">
					<label for="password_repeat">Ponovi lozinku</label>
					<input type="password" class="form-control" name="password_repeat" id="password_repeat" />

				</div>

				<div class="form-group">
					<label for="fname">Ime</label>
					<input type="text" class="form-control" name="fname" id="fname" value="" />

				</div>

				<div class="form-group">
					<label for="lname">Prezime</label>
					<input type="text" class="form-control" name="lname" id="lname" value="" />

				</div>

				<div class="form-group">
					<label for="adress">Adresa</label>
					<input type="text" class="form-control" name="adress" id="adress" value="" />

				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-success form-control"> Potvrdi</button>

				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
				</div>
			</form>
		</div>
	</div>
</div>

