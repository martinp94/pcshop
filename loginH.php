<?php

require_once 'core/init.php';

$user = new User();

if($user->isLoggedIn()){
  Redirect::to('index.php');
}

if(Input::exists()){
    if(Token::check(Input::get('token'))) {
      
      $validate = new Validate();

      $validation = $validate->check($_POST, array(
        'usernameH' => array('required' => true),
        'passwordH' => array('required' => true)
      ));


      if($validation->passed()){
        // Log user in
        $user = new User();

        $remember = (Input::get('rememberH') === 'on') ? true : false;
        $login = $user->login(Input::get('usernameH'), Input::get('passwordH'), $remember);

        if($login) {
          Redirect::to('index.php');
        } else {
          Redirect::to('index.php');
        }
      } else {
        foreach ($validation->errors() as $error) {
          
        }
          Redirect::to('index.php');

      }
    }
    Redirect::to('index.php');
  }