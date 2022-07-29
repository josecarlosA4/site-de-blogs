<?php 

require 'config.php';
require 'models/Auth.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin();

$pageTitle = 'Configurações - '.$userInfo->name;

$date = explode("-", $userInfo->birthdate);
$date = $date[2].'/'.$date[1].'/'.$date[0];

?>

<?php require 'partials/header.php'; ?>

<section class="config-body">
	<div class="container-fluid config-page-body">
	<h1>Configurações do usuário: </h1>
	<div class="config-form">
		<div class="error-alert inativeNone"></div>
		<div class="success-alert inativeNone"></div>
		Cover:
		<input type="file" id="cover" name="cover">
		Avatar:
		<input type="file" id="avatar" name="avatar">
		<input type="text" name="name" id="name" value="<?=$userInfo->name?>" >
		<input type="email" name="email" id="email" value="<?=$userInfo->email?>" placeholder="Digite seu email...">
		<input type="text" name="birthdate" id="birthdate" id="birthdate" value="<?=$date?>" placeholder="Data de aniversario...">
		<input type="password" name="password" id="password" placeholder="Digite sua nova senha...">
		<textarea name="description" id="description"><?=$userInfo->description ?></textarea>
		<input type="hidden" id="passwordUser" name="passwordUser" value="<?=$userInfo->password?>">
		<input type="hidden" id="avatarUser" name="avatarUser" value="<?=$userInfo->avatar?>">
		<input type="hidden" id="coverUser" name="coverUser" value="<?=$userInfo->cover?>">

		<div class="action-buttons">
			<button id="submit" name="submit" class="button">Enviar</button>
			<a class="cancel-button" href="<?=$base?>/index.php">Cancelar</a>
		</div>			
	</div>
	</div>
</section>

<?php require 'partials/footer.php'; ?>
<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'),
        {
            mask:"00/00/0000"
        }
    );
</script>
<script>
	document.querySelector('#submit').addEventListener('click', async (e)=>{
		let name = document.querySelector("#name").value;
		let email = document.querySelector("#email").value;
		let birthdate = document.querySelector("#birthdate").value;
		let password = document.querySelector("#password").value;
		let description = document.querySelector("#description").value;
		let passwordUser = document.querySelector("#passwordUser").value;
		let avatarUser = document.querySelector("#avatarUser").value;
		let coverUser = document.querySelector("#coverUser").value;
		let cover = document.querySelector("#cover").files[0];
		let avatar = document.querySelector("#avatar").files[0];

		let body = new FormData();
		body.append('name', name);
		body.append('email', email );
		body.append('birthdate', birthdate);
		body.append('password', password);
		body.append('description', description);
		body.append('passwordUser', passwordUser);
		body.append('avatarUser', avatarUser);
		body.append('coverUser', coverUser);
		body.append('avatar', avatar);
		body.append('cover', cover);

		let req = await fetch('configUserAction.php', {
			method:'POST',
			body:body
		});	

		let data = await req.json();

		console.log(data);

		if(data.errors != '') {
			let html = ''
			html += data.errors;

			document.querySelector('.success-alert').classList.add('inativeNone');
			document.querySelector('.error-alert').classList.remove('inativeNone');
			document.querySelector('.error-alert').classList.add('activeFlex');
			document.querySelector('.error-alert').innerHTML = html;
		}

		if(data.errors == '') {
			let html = ''
			html += data.success;

			document.querySelector('.error-alert').classList.add('inativeNone');
			document.querySelector('.success-alert').classList.remove('inativeNone');
			document.querySelector('.success-alert').classList.add('activeFlex');
			document.querySelector('.success-alert').innerHTML = html;
		}

	});
</script>
