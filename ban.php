<?php

include 'core/init.php';

$errors = array();
$db = DB::getInstance();

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(!$user->hasPermission('Admin')) {

		Redirect::to('index.php');
}

if(Input::get('userid')){
	if(Input::get('userid') != '') {
		$user1 = new User(Input::get('userid'));

		if(Input::get('submitbantime')){
			if(Input::get('bantime')){
			
				$bantime = Input::get('bantime');

				if(is_numeric($bantime) && $bantime < 365) {

					$banDate = new DateTime("now");
					$interval = new DateInterval('P' . $bantime . 'D');
					$expires = clone $banDate;
					$expires->add(new DateInterval('P' . $bantime . 'D'));

					$errors[] = 'ban: ' . $banDate->format('Y-m-d H:i:s') . ' do ' . $expires->format('Y-m-d H:i:s');
				    $_SESSION['errors'] = $errors;
					
				    $fields = array(
				    	'user_id' => Input::get('userid'),
				    	'ban_date' => $banDate->format('Y-m-d H:i:s'),
				    	'expires' => $expires->format('Y-m-d H:i:s')

				    );

				    if($db->insert('users_bans', $fields)){

				    	
				    	$username = $user1->data()->username;
				    	Redirect::to('index.php?administracija&users=' . $username);

				    } else {
				    	Redirect::to('index.php');
				    }

				} else {
					Redirect::to('index.php');
				}

			} else {
				Redirect::to('index.php');
			}
		}

		if(Input::get('submitpermanentban')) {

			$username = $user1->data()->username;

			if($user1->isBan()) {
				$fields = array(
						'permanent' => 1,
				    	'expires' => null
					);

			    if($db->updateNew('users_bans', 'user_id', Input::get('userid'), $fields)){
			    	
			    	Redirect::to('index.php?administracija&users=' . $username);

			    } else {
			    	Redirect::to('index.php');
			    }
			} else {
			    	
			    $fields = array(
				    	'user_id' => Input::get('userid'),
				    	'ban_date' => date('Y-m-d H:i:s'),
				    	'permanent' => 1,
				    	'expires' => null

				    );

			    if($db->insert('users_bans', $fields)) {
				    Redirect::to('index.php?administracija&users=' . $username);

			    } else {
			    	Redirect::to('index.php');
			    }
				
			}

			
		}

	} else {
		Redirect::to('index.php');
	}

} else {
	Redirect::to('index.php');
}