<?php 
require_once 'core/init.php';

$validationErrors = array();

if(Input::exists()){
	if(Token::check(Input::get('token'))){
	
	$validate = new Validate();

	$validation = $validate->check($_POST, array(
		'username' => array(
			'required' => true,
			'min' => 6,
			'max' => 20,
			'unique' => 'users'
		),
		'password' => array(
			'required' => true,
			'min' => 6
		),
		'password_repeat' => array(
			'required' => true,
			'matches' => 'password'
		),
		'fname' => array(
			'required' => true,
			'min' => 2,
			'max' => 20
		),
		'lname' => array(
			'required' => true,
			'min' => 2,
			'max' => 20
		),
		'email' => array(
			'required' => true,
			'min' => 5,
			'max' => 32,
			'unique' => 'users'
		),
		'adress' => array(
			'required' => true,
			'min' => 10,
			'max' => 128
		)

	));

	if($validation->passed()){
		
		$user = new User();

		$salt = Hash::salt(32);

		try{

			$actCode = generate_random_string();

			$user->create(array(
				'username' => Input::get('username'),
				'password' => Hash::make(Input::get('password'), $salt),
				'email' => Input::get('email'),
				'adress' => Input::get('adress'),
				'activated' => false,
				'salt' => $salt,
				'f_name' => Input::get('fname'),
				'l_name' => Input::get('lname'),
				'joined' => date('Y-m-d H:i:s'),
				'permissions' => 1,
				'activation_code' => $actCode

			));

			$user->login(Input::get('username'), Input::get('password'), false);

			$mailSender = new MailSender();
			$mailSender->send_mail(Input::get('email'), 'Tvoj aktivacioni kod je ' . $actCode);
			
			$validationErrors[] = $mailSender->error();
			$_SESSION['errors'] = $validationErrors;

		} catch (Exception $e){
			
			$_SESSION['errors'][] = $e;
			
		}


	} else {
		// output errors
		foreach($validation->errors() as $error){
			$validationErrors[] = $error . '<br>';
		}

		$_SESSION["errors"] = $validationErrors;
	}

	}
} 

Redirect::to('index.php');

?>