<?php 

require 'config.php';
require 'models/Auth.php';


$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');



if($email && $password) {
  	$auth = new Auth($pdo, $base);

  	if($auth->validateLogin($email, $password)) {
  		header("Location: ".$base."/index.php");
		exit;
  	} else {
  		$_SESSION['flash'] = 'Email e/ou senha invalidos';
		header("Location: ".$base."/login.php");
		exit;	
  	}

} else {
	$_SESSION['flash'] = 'Todos os campos devem estar preenchidos';
	header("Location: ".$base."/login.php");
	exit;
}