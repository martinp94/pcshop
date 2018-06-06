<?php

include_once 'core/init.php';

$validate = new Validate();
$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(isset($_GET['delete'])) {
	if($_GET['delete'] != '') {

		$db = DB::getInstance();

		$pid = $db->get('comments', array('id', '=', $_GET['delete']));
		if($pid->count()) {
			$pid = $pid->first()->product_id;
		}

		if($db->get('comments', array('reply_to', '=', $_GET['delete']))->count()) {
			$db->delete('comments', array('reply_to', '=', $_GET['delete']));
		}

		if($db->delete('comments', array('id', '=', $_GET['delete']))->count()) {
			Redirect::to('index.php?proizvod=' . $pid);
		}
	}

	
}


if(Input::exists()) {
	if(isset($_POST['pid'])) {
		if(isset($_POST['text'])) {
				
				$validation = $validate->check($_POST, array(

					'text' => array(
						'required' => true,
						'min' => 3,
						'max' => 2048
					)
				));

				if($validation->passed()) {

					if(isset($_POST['submitcomment'])) {
						if(Token::check(Input::get('token'))) {
							uploadComment($_POST['pid'], $user->data()->id, $_POST['text'], date('Y-m-d H:i:s'));
						} 
					}

					if(isset($_POST['submitreply'])) {
						if(isset($_POST['to_comment'])) {

							reply($_POST['pid'], $user->data()->id, $_POST['text'], date('Y-m-d H:i:s'), $_POST['to_comment']);
						} 
						
					} 
					
				}
			}
		}


}


function uploadComment($productId, $userId, $text, $date){

	$db = DB::getInstance();

	$fields = array(
		'product_id' => $productId,
		'user_id' => $userId,
		'text' => $text,
		'date' => $date
	);

	if($db->insert('comments', $fields)) {
		Redirect::to('index.php?proizvod=' . $productId);
	} else {
		Redirect::to('index.php');
	}

}

function reply($productId, $userId, $text, $date, $replyTo){
	$db = DB::getInstance();

	$fields = array(
		'product_id' => $productId,
		'user_id' => $userId,
		'text' => $text,
		'date' => $date,
		'reply_to' => $replyTo
	);

	if($db->insert('comments', $fields)) {
		Redirect::to('index.php?proizvod=' . $productId);
	} else {
		Redirect::to('index.php');
	}
}

function deleteComment($commentId) {

}