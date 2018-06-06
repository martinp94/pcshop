<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();

if($user->isLoggedIn()){

	if(!$user->hasPermission('Admin')) {

		Redirect::to('index.php');
	}

} else {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {

		if(isset($_POST['naslov']) && isset($_POST['text'])) {
			if($_POST['naslov'] != '' && $_POST['text'] != '') {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(

					'naslov' => array(

						'required' => true,
						'min' => 6,
						'max' => 45
					),

					'text' => array(

						'required' => true,
						'max' => 20000
					)

				));

				if($validation->passed()) {

					$fields = array(
						'naslov' => $_POST['naslov'],
						'text' => $_POST['text'],
						'date' => date('Y-m-d H:i:s')
					);

					if($db->insert('news', $fields)) {
						Redirect::to('index.php?&news');
					}

				}

				Redirect::to('index.php?administracija');
			}
		}

		Redirect::to('index.php?administracija');
	}
}



?>




<div class="container-fluid">

	<form action="newsadd.php" method="post">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="form-group">
					<label for="naslov">Naslov</label>
					<input type="text" name="naslov" class="form-control">
				</div>
				
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<textarea class="form-control" name="text" rows="20" cols="100"></textarea>
				</div>
				
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<button type="submit" class="form-control btn btn-success">Dodaj novost</button>
				</div>
				
			</div>
		</div>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	</form>

</div>