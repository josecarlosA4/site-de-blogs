<?php 

require 'config.php';
require 'models/Auth.php';
require 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;
	
Image::configure(['driver' => 'GD']);

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin($pdo);

$id = filter_input(INPUT_POST, 'id_post');
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
$body = filter_input(INPUT_POST, 'post-content', FILTER_SANITIZE_SPECIAL_CHARS);
$thumb = filter_input(INPUT_POST, 'image');

$array = ['errors' => ''];

if($id && $title && $category && $body && $thumb) {
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



	$thumbnail = '';
	
	if(isset($_FILES['file']) && $_FILES['file']['error'] === 0) {

		
		$image = $_FILES['file'];
		$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

		if(in_array($image['type'], $allowedTypes)) {
			$extension = explode('/', $image['type']);
			$extension = $extension[1];

			$thumbnail = md5(time().rand(0,999).time()).".".$extension;

			$img = Image::make($image['tmp_name'])
			->fit(300, 200)
			->save('assets/images/'.$thumbnail);
		} else {
			$array['errors'] = 'Tipo de imagem não permitido';
			header("Content-Type: multipart/form-data");
			echo json_encode($array);
			return $array;
		}

	} else {
		$thumbnail = $thumb;
	}

		$updated_at = date("Y-m-d H:i:s");

		$post = new PostDaoMySql($pdo);
		$post->editPost($id, $title, $category, $body, $thumbnail, $updated_at);

	
		$array['errors'] = '';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;

		return $array;

} else {
		$array['errors'] = 'Todos os campos devem estar preenchidos';
		header("Content-Type: multipart/form-data");
		echo json_encode($array);
		return $array;
}
