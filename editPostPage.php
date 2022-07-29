<?php 

require 'config.php';
require 'models/Auth.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin($pdo);

$id = filter_input(INPUT_GET, 'id');

$posts = new PostDaoMySql($pdo);
$post = $posts->generatePost($id);

$pageTitle = $post->title." - EDITAR";

?>

<?php require 'partials/header.php'; ?>

<section class="post-area">
	<div class="container-fluid">

		<div class="errors-area">
			
		</div>

		<h2>Escreva um novo post</h2><br>
		<div style="margin-bottom: 50px">
			<input type="hidden" name="id_post" id="id_post" value="<?=$post->id?>">
			<input type="hidden" name="image" id="image" value="<?=$post->thumbnail ?>">
			Título:(max:60 caracteres)<br>
			<textarea name="title" id="title" class="title-post"><?=$post->title ?></textarea><br>

			Catégoria:(max:60 caracteres)<br>
			<textarea name="category" id="category" class="title-post"><?=$post->category?></textarea><br>

			Thumbnail:(300x200)<br>
			<input type="file" id="file" name="file"><br><br>

			Contéudo:<br>
			<textarea class="post-content" id="post-content" name="post-content"><?=$post->body?></textarea>
						
			<div class="actions-area">
				<button id="submit" name="submit">Enviar</button>
				<a href="<?=$base?>/postPage.php?id=<?=$post->id?>" class="delete-button">Cancelar</a>
			</div>
		
		</div>
	</div>
</section>

<?php require 'partials/footer.php'; ?>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script>
	document.querySelector('#submit').addEventListener('click', async (e)=>{

		let title = document.querySelector('#title').value;
		let category = document.querySelector('#category').value;
		let file = document.querySelector('#file').files[0];
		let post_content = document.querySelector('#post-content').value;
		let id_post = document.querySelector('#id_post').value;
		let image = document.querySelector('#image').value;

		let body = new FormData();
		body.append('id_post', id_post);
		body.append('image', image);
		body.append('title', title);
		body.append('post-content', post_content);
		body.append('category', category);
		body.append('file', file);

		let req = await fetch('editPostAction.php', {
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
			window.location.href = 'postPage.php?id='+id_post;
		}		
		
	});
	
</script>