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
		<title>Registrar-se</title>
	</head>
	<body>
		<div class="container-fluid register-body">

		

			<h1>Registre-se</h1>
				<?php if($_SESSION['flash']): ?>
					<div class="flash-auth">
						<?php echo $_SESSION['flash']?>
						<?php $_SESSION['flash'] = '';?>
					</div>
				
				<?php endif; ?>
			<form class="register-form" action="<?=$base?>/registerAction.php" method="POST">
				<input type="text" name="name" placeholder="Digite seu nome...">
				<input type="email" name="email" placeholder="Digite seu email...">
				<input type="password" name="password" placeholder="Digite sua senha...">
				<input type="password" name="password-confirmation" placeholder="Confirme sua senha...">
				<input type="text" name="birthdate" id="birthdate" placeholder="Data de aniversario...">	
				<input type="submit" name="submit" value="Enviar" class="button">
			</form>
			Já tem conta ?<a href="<?=$base?>/login.php">Faça login<a>
		</div>
		

		<scrip src="<?=$base?>/assets/js/jquery-3.6.0.min.js"></scrip>
		<script type="text/javascript" src="<?=$base?>/assets/js/bootstrap.bundle.min.js"></script>
		<script src="https://kit.fontawesome.com/1dcd3273ac.js" crossorigin="anonymous"></script>
	 	<script src="https://unpkg.com/imask"></script>
	    <script>
	        IMask(
	            document.getElementById('birthdate'),
	            {
	                mask:"00/00/0000"
	            }
	        );
	    </script>
	</body>
	</html> 