<?php

require_once 'core/init.php';

$user = new User();
$user->logout();

if(Session::exists('cart')) Session::delete('cart');

Redirect::to('index.php');