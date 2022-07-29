<?php

require 'config.php';
require 'models/Auth.php';

$auth = new Auth($pdo, $base);
$auth->checkLogin();


$id = filter_input(INPUT_GET, 'id');

$action = new PostDaoMySql($pdo);
$action->deletePost($id);

header("Location: ".$base."/profile.php?id=");
exit;