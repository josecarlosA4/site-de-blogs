<?php 

require 'config.php';
require 'models/Auth.php';
require 'dao/PostCommentDaoMySql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin($pdo);

$id_post = $_POST['id_post'];
$id_user = $_POST['id_user'];
$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_SPECIAL_CHARS);


$array = ['errors' => ''];
if($id_post && $id_user && $body) {

	$created_at = date("Y-m-d H:i:s");

	$action = new PostCommentDaoMySql($pdo);
	$array[] =  $action->insertComment($id_post, $id_user, $body, $created_at);

	$array['errors'] = '';
	header("Content-Type: application/json");
	echo json_encode($array);
	return $array;

} 