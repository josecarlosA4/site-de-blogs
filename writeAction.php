<?php


require 'config.php';
require 'models/Auth.php';
require 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;
	
Image::configure(['driver' => 'GD']);

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin();

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
$body = filter_input(INPUT_POST, 'post-content', FILTER_SANITIZE_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);

$array = ['errors' => ''];
if($title && $category && $body && isset($_FILES['file'])) {
	
	$image = $_FILES['file'];

	if(strlen($title) > 60) {
		$array['errors'] = 'O título deve ter menos de 60 caracteres';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;
	}

	if(strlen($category) > 60) {
		$array['errors'] = 'A categoria deve ter menos de 60 caracteres';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;
	}

	$allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

	if(in_array($image['type'], $allowedTypes)) {
		$extension = explode('/', $image['type']);
		$extension = $extension[1];

		$imageName = md5(time().rand(0,999).time()).".".$extension;

		$img = Image::make($image['tmp_name'])
		->fit(300,200)
		->save('assets/images/'.$imageName);

	} else {
		$array['errors'] = 'Extensão de imagem não permitida';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;
	}

	$created_at = date("Y-m-d H:i:s");
	$id_user = $userInfo->id;

	$post = new PostDaoMySql($pdo);
	$post->insertPost($id_user, $body,$title,$created_at,$imageName,$category);

	$array['errors'] = '';
	header("Content-Type: multipart/form-data");
	echo json_encode($array);
	return $array;
} else {
	$array['errors'] = 'Todos os campos devem estar preenchidos';
	header("Content-Type: multipart/form-data");
	echo json_encode($array);
	return $array;
}
?>






