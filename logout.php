<?php

require 'config.php';

$_SESSION['token'] = '';
header("Location: ".$base."/index.php");
exit;