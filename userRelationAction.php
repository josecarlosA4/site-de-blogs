<?php 

require 'config.php';
require 'models/Auth.php';
require_once 'dao/UserRelationDaoMySql.php';

$auth = new Auth($pdo, $base);
$auth->checkLogin();

$user_from = filter_input(INPUT_POST, 'user_from');
$user_to = filter_input(INPUT_POST, 'user_to');
$check = filter_input(INPUT_POST, 'check');

$request = new UserRelationDaoMySql($pdo);
$number = $request->relation($user_from, $user_to, $check);

$array = ['errors' => '' ,'number' => $number];
header("Content-Type: multipart/form-data");
echo json_encode($array);
return $array;
?>


