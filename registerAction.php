<?php 

require 'config.php';
require 'models/Auth.php';

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$password_confirmation = filter_input(INPUT_POST, 'password-confirmation');
$birthdate = filter_input(INPUT_POST, 'birthdate');



if($name && $email && $password && $password_confirmation && $birthdate) {
	$auth = new Auth($pdo, $base);

	if(strlen($name) < 2) {
		$_SESSION['flash'] = 'O campo "nome" deve ter no minimo 2 caracteres';
		header('Location: '.$base.'/register.php');
		exit;
	}

	if($auth->emailExists($email) === true) {
		$_SESSION['flash'] = 'Este email já está cadastrado.';
		header('Location: '.$base.'/register.php');
		exit;
	}

	if(strlen($password) < 4 ) {
		$_SESSION['flash'] = 'O senha dever ter no minímo 4 caracteres.';
		header('Location: '.$base.'/register.php');
		exit;
	}

	if($password != $password_confirmation) {
		$_SESSION['flash'] = 'Senhas não são iguais.';
		header('Location: '.$base.'/register.php');
		exit;
	}

	$birthdate = explode('/', $birthdate);

	if(count($birthdate) != 3) {
		$_SESSION['flash'] = 'Está data é invalida';
		header('Location: '.$base.'/register.php');
		exit;
	}

	$birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

	if(strtotime($birthdate) === false) {
		$_SESSION['flash'] = 'Está data é invalida';
		header('Location: '.$base.'/register.php');
		exit;
	}

	$auth->create($name, $email, $password, $birthdate);
	

} else {
	$_SESSION['flash'] = 'Todos os campos devem estar preenchidos';
	header('Location: '.$base.'/register.php');
	exit;
}