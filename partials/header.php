<!doctype html>

	<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="<?= $base?>/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?= $base?>/assets/css/style.css">
		<title><?= $pageTitle ?></title>
	</head>
	<body>

		<section class="menu text-center fixed-top">
			<div class="container-fluid">
				<div class="row justify-content-center align-items-center bg-dark">
					<div class="col-sm-1">
						<a href="<?=$base?>/index.php">
							HOME
						</a>
					</div>
					<div class="col-sm-1">
						<a href="<?=$base?>/writePage.php">
							ESCREVER
						</a>
					</div>
					<div class="col-sm-1" >
						<a href="<?=$base?>/profile.php?id=">
							PERFIL
						</a>
					</div>
					<div class="col-sm-1" >
						<a href="<?=$base?>/savedPostPage.php">
							SALVOS
						</a>
					</div>
					<div class="col-sm-1">
						<a href="<?=$base?>/configUser.php">
							CONFIGS
						</a>
					</div>
					<div class="col-sm-1">
						<a onclick="confirm('Deseja deslogar ?')" href="<?= $base?>/logout.php">
							SAIR
						</a>
					</div>

				</div>
			</div>
		</section>