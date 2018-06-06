<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$errors = array();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

if(Input::exists()){
	if(Input::get('supportmessage')){
		
		$validate = new Validate();

		$validation = $validate->check($_POST, array(

			'supportmessage' => array(
				'required' => true,
				'min' => 10,
				'max' => 2000
			)

		));


		if($validation->passed()){
			$fields = array(
				'user_id' => $user->data()->id,
				'date' => date('Y-m-d H:i:s'),
				'question' => Input::get('supportmessage')
			);

			if($db->insert('support_questions' , $fields)) {
				$errors[] = 'Poruka poslata!';
			} else {
				$errors[] = 'Greška : Poruka nije poslata, pokušajte ponovo.';
			}

		} else {
			
			foreach($validation->errors() as $error){
				$errors[] = $error . '<br>';
			}
		}

	}
		$_SESSION['errors'] = $errors;
		Redirect::to('index.php');
} 

?>



<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<h4>Ukoliko imate problema u radu, pošaljite nam poruku. Administrator će Vam odgovoriti u roku od narednih 24 sata.</h4>
		</div>
	</div>

		<form method="post" action="usersupport.php">
			
		
			<div class="row">
				<div class="col-md-6 form-group">
					<textarea class="form-control" name="supportmessage" style="resize: none;" rows="6" cols="60"></textarea>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 form-group">
					<button type="submit" class="form-control btn btn-success" name="submit">Pošalji</button>
				</div>
			</div>
		
		</form>
	
</div>