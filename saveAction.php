<?php 

require 'config.php';
require 'models/Auth.php';
require 'dao/savedPostDaoMySql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin($pdo);

$id_post = filter_input(INPUT_POST, 'id_post');
$id_user = filter_input(INPUT_POST, 'id_user');
$check = filter_input(INPUT_POST, 'check') ;

$date = date("Y-m-d H:i:s");

$sRequest = new savedPostDaoMySql($pdo);
$array = $sRequest->saveActions($id_user, $id_post, $date, $check);

header("Content-Type: application/json");
echo json_encode($array);
return $array;