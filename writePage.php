<?php 

require 'config.php';
require 'models/Auth.php';

$auth = new Auth($pdo, $base);
$auth->checkLogin();

$pageTitle = 'Escrever';

?>

<?php require 'partials/header.php'; ?>

<section class="post-area">
	<div class="container-fluid">

		<div class="errors-area">
			
		</div>

		<h2>Escreva um novo post</h2><br>
		<div style="margin-bottom: 50px;">
			Título:(max:60 caracteres)<br>
			<textarea name="title" id="title" class="title-post"></textarea><br>

			Catégoria:(max:60 caracteres)<br>
			<textarea name="category" id="category" class="title-post"></textarea><br>

			Thumbnail:(300x200)<br>
			<input type="file" id="file" name="file"><br><br>

			Contéudo:<br>
			<textarea class="post-content" id="post-content" name="post-content"></textarea>

			<button id="submit" name="submit">Enviar</button>
		</div>
	</div>
</section>

<?php require 'partials/footer.php'; ?>


<script>
	document.querySelector('#submit').addEventListener('click', async (e)=>{

		let title = document.querySelector('#title').value;
		let category = document.querySelector('#category').value;
		let file = document.querySelector('#file').files[0];
		let post_content = document.querySelector('#post-content').value;

		let body = new FormData();
		body.append('title', title);
		body.append('post-content', post_content);
		body.append('category', category);
		body.append('file', file);

		let req = await fetch('writeAction.php', {
			method:'POST',
			body:body,
		});

		let data =  await req.json();
		
		if(data.errors != '') {
			let html = '';
			html += '<div class="flash">';
			html += data.errors;
			html += '</div>';

			document.querySelector('.errors-area').innerHTML = html;
		}

		if(data.errors == '') {
			window.location.href = 'profile.php?id=';
		}		
		
	});
</script>

