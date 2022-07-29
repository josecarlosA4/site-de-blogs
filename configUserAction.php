<?php 

require 'config.php';
require 'models/Auth.php';
require 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;	
Image::configure(['driver' => 'GD']);

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin();

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate');
$password = filter_input(INPUT_POST, 'password');
$description = filter_input(INPUT_POST, 'description');
$passwordUser = filter_input(INPUT_POST, 'passwordUser');
$avatarUser = filter_input(INPUT_POST, 'avatarUser');
$coverUser = filter_input(INPUT_POST, 'coverUser');

$array = ['errors' => ''];

if($name && $email && $birthdate) {

	if(strlen($name) < 2) {
		$array['errors'] = 'O campo nome deve ter noo mínimo 2 caracteres';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;
		
	}

	if($email != $userInfo->email) {
		
		if($auth->emailExists($email) === true ) {
			$array['errors'] = 'Este email já está em uso';
			header("Content-Type: multipart/form-data");
			echo json_encode($array);
			return $array;
		
		} 
	}

	$birthdate = explode('/', $birthdate);

	if(count($birthdate) != 3) {
		
		$array['errors'] = 'Data de nascimento invalida!';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;
		
	}

	$birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

	if(strtotime($birthdate) === false) {
		$array['errors'] = 'Data de nascimento invalida!';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;
		
	}

	$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

	if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
		$avatarFile = $_FILES['avatar'];

		if(in_array($avatarFile['type'], $allowedTypes)) {

			$extension = explode('/', $avatarFile['type']);
			$extension = $extension[1];

			$avatar = md5(time().rand(0,9999).time()).'.'.$extension;

			$img = Image::make($avatarFile['tmp_name'])
					->fit(120, 120)
					->save('media/avatars/'.$avatar);
		} else {
			
			$array['errors'] = 'Formato de imagem para avatar invalida';
			header("Content-Type: multipart/form-data");
			echo json_encode($array);
			return $array;
			
		}

	} else {
		$avatar = $avatarUser;
	} 

	if(isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
		$coverFile = $_FILES['cover'];

		if(in_array($coverFile['type'], $allowedTypes)) {

			$extension = explode('/', $coverFile['type']);
			$extension = $extension[1];

			$cover = md5(time().rand(0,9999).time()).'.'.$extension;

			$img = Image::make($coverFile['tmp_name'])
					->fit(876, 300)
					->save('media/covers/'.$cover);
		} else {
			
			$array['errors'] = 'Formato de imagem para capa invalida';
			header("Content-Type: multipart/form-data");
			echo json_encode($array);
			return $array;
			
		}

	} else {
		$cover = $coverUser;
	}

	if($password == '') {
		$hash = $passwordUser;
	} else {
		if(strlen($password) < 4) {
			
			$array['errors'] = 'O campo de senha deve ter no mínimo 4 caracteres';
			header("Content-Type: multipart/form-data");
			echo json_encode($array);
			return $array;
			
		}

		$hash = password_hash($password, PASSWORD_DEFAULT);

	}

	$id = $userInfo->id;

	$user = new UserDaoMySql($pdo);
	$user->updateUser($id, $name, $email, $hash, $birthdate, $avatar, $cover, $description);

	$array['errors'] = '';
	$array['success'] = 'Campos alterados com sucesso';
	header("Content-Type: multipart/form-data");
	echo json_encode($array);
	return $array;

} else {
	
	$array['errors'] = 'Existem campos nulos';
	header("Content-Type: multipart/form-data");
	echo json_encode($array);
	return $array;
	
}