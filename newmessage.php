<?php

include_once 'core/init.php';

$user = new User();

$messenger = null;
$messages = array();
$db = null;
$errors = array();

if($user->isLoggedIn()){
	$messenger = new Messenger($user->data()->id);
	$db = DB::getInstance();

} else {
	Redirect::to('index.php');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate = new Validate();

		$validation = $validate->check($_POST,array(
			'to_user' => array(
				'required' => 'true',
				'min' => 6,
				'max' => 45
			),

			'message' => array(
				'required' => 'true',
				'min' => 10,
				'max' => 4000
			)
		));

		if($validation->passed()) {

			if($messenger->sendMessage($user->data()->id, Input::get('to_user'), Input::get('message'))) {
				Redirect::to('index.php?poruke');
			}
			
		} else {

			foreach ($validation->errors() as $error) {
				$errors[] = $error;
				$_SESSION['errors'] = $error;
			}
		}

	}
}


?>

<form method="post" action="newmessage.php">
	<div class="form-group">
		<label for="to_user" class="text-primary">Za &nbsp;</label>
		<?php 
			if(isset($_GET['newmessage'])) {
				if($_GET['newmessage'] != ''){
		?>
			<input type="text" name="to_user" value="<?php echo $_GET['newmessage']; ?>" />
		<?php
				} else {
		?>
			<input type="text" name="to_user" />
		<?php
				}
			}
		?>
	</div>

	<div class="form-group">
		
		<textarea style="resize: none;" class="form-control" rows="8" name="message"></textarea>
	</div>

	<div class="form-group">
		
		<button class="form-control btn btn-success"> Pošalji </button>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	</div>
</form>