<?php

require 'config.php';

?>

<!doctype html>
	<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="<?=$base?>/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?=$base?>/assets/css/style.css">
		<title>Login</title>
	</head>
	<body>
		<div class="container-fluid login-container">

			<h1 class="login-head">Faça login</h1>
			<?php if($_SESSION['flash']): ?>
					<div class="flash-auth">
						<?php echo $_SESSION['flash']?>
						<?php $_SESSION['flash'] = '';?>
					</div>
				
				<?php endif; ?>
			<form class="login-form" action="<?=$base?>/loginAction.php" method="POST">
				<input type="email" name="email" placeholder="Digite seu email...">
				<input type="password" name="password" placeholder="Digite sua senha...">
				<input type="submit" name="submit" value="Enviar" class="button">
			</form>
			Não tem conta?<a href="<?= $base?>/register.php">Crie uma</a>
		</div>
		

		<scrip src="<?=$base?>/assets/js/jquery-3.6.0.min.js"></scrip>
		<script type="text/javascript" src="<?=$base?>/assets/js/bootstrap.bundle.min.js"></script>
		<script src="https://kit.fontawesome.com/1dcd3273ac.js" crossorigin="anonymous"></script>
	</body>
	</html> 