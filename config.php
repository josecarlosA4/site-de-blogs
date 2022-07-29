<?php

session_start();

$base = 'http://localhost/projetos_php/myBlogsOO';
$db_name = 'myblogs';
$db_host = '';
$db_user = '';
$db_pass = '';

$pdo = new PDO("mysql:dbname=".$db_name.";host=".$db_host,$db_user,$db_pass);
