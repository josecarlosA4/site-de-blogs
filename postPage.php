<?php 

require 'config.php';
require 'models/Auth.php';
require 'dao/PostCommentDaoMySql.php';
require 'dao/savedPostDaoMySql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin($pdo);

$id = filter_input(INPUT_GET, 'id');

$posts = new PostDaoMySql($pdo);
$post = $posts->generatePost($id);

$date = explode(' ', $post->created_at);
$date = $date[0];
$date = explode('-', $date);
$date = $date[2].'/'.$date[1].'/'.$date[0];

$pRequest = new PostCommentDaoMySql($pdo);
$comments = $pRequest->listComments($post->id);

$sRequest = new savedPostDaoMySql($pdo);

$check = $sRequest->checkSave($userInfo->id, $id);

if($check == 1) {
	$msg = 'salvo';
} else if($check == 0 ) {
	$msg = 'salvar';
}

$pageTitle = $post->title;
?>

<?php require 'partials/header.php'; ?>
<section class="blog-body">
	<div class="container-fluid">
		<div class="actions-area">
		<?php if($userInfo->id === $post->user->id):?>
				<a href="editPostPage.php?id=<?=$post->id?>" class="edit-button">EDITAR</a>
				<a onclick="confirm('Deseja realmente excluir esse post ?')" href="deletePost.php?id=<?=$post->id?>" class="delete-button">EXCLUIR</a>
		<?php endif;?>
		<div class="save-form">
			<button id="save" class="save-button <?= $msg ?>"><?= strtoupper($msg) ?></button>
			<input type="hidden" id="check" name="check" value="<?= $check ?>">
		</div>
		</div>
		<div class="blog-header">
			<div class="media d-flex">
				<img src="<?=$base?>/media/avatars/<?=$post->user->avatar?>" class="mr-3 avatar">
				<div class="media-body flex-grow-1 ms-3">
					<h5><a href="<?=$base?>/profile.php?id=<?=$post->user->id?>"><?= $post->user->name ?></a></h5>
					<?php if(!empty($post->user->description)):?>
						<p><?= str_replace('&#13;&#10;', '<br>', $post->user->description) ?></p>
					<?php endif; ?>
					<p><i>em <?=$date?></i></p>
				</div>
			</div>
		</div>

		<div class="blog-body-content">
			<div class="media-body">
				<h2><?= $post->title?></h2>
				<p>
					<?=str_replace('&#13;&#10;', '<br>', $post->body)?>
				</p>
			</div>
		</div>

		<div class="blog-comments">					

			<ul style="list-style: none;" class="comments-list">
				<h3>Comentários: </h3>

				<li class="media d-flex user-comment-area">
					<img src="<?=$base?>/media/avatars/<?=$userInfo->avatar?>" class="mr-3 avatar">
					<div class="media-body flex-grow-1 ms-3">
						<h5><?=$userInfo->name?></h5>
						<textarea class="comments-textearea" style="width: 100%;" type="text" id="body" name="body"></textarea>
						<input type="hidden" id="id_user" name="id_user" value="<?= $userInfo->id?>">
						<input type="hidden" id="id_post" name="id_post" value="<?= $post->id?>">
						<button id="comment-send">Enviar</button>
					</div>
				</li>
				<ul id="comment-tmp" style="list-style: none; " >
						
				</ul>
				<?php if(isset($comments)): ?>
					<?php foreach($comments as $item): ?>
						<li class="media d-flex ">
							<a href="<?=$base?>/profile.php?id=<?=$item->user->id?>">
								<img src="<?=$base?>/media/avatars/<?= $item->user->avatar ?>" class="mr-3 avatar">
							</a>
							<div class="media-body flex-grow-1 ms-3">
								<a style="color: black;" href="<?=$base?>/profile.php?id=<?=$item->user->id?>"><h5><?=$item->user->name?></h5></a>
								<p><?=str_replace('&#13;&#10;', '<br>', $item->body)?></p>
							</div>
						</li>
					<?php endforeach; ?>
				<?php endif;?>	
	
			</ul>
		</div>
	</div>
</section>

<?php require 'partials/footer.php'; ?>

<script type="text/javascript" src="<?=$base?>/assets/js/jquery-3.6.0.min.js"></script>

<script type="text/javascript">

	$('#comment-send').bind('click', ()=>{
		let u_id_post = $("#id_post").val();
		let u_id_user = $("#id_user").val();
		let u_body = $("#body").val();

		$.ajax({
			url: 'commentAction.php',
			method: 'POST',
			data: {
				id_post: u_id_post, 
				id_user: u_id_user, 
				body: u_body
			},
			dataType: 'json'
		}).done((res)=>{
			if(res.errors == '') {
				const BASE = 'http://localhost/projetos_php/myBlogsOO/media/avatars/';

				let html = '';
				html += '<li class="media d-flex ">'
				html += '<img src="'+BASE+res[0].user[1]+'" class="mr-3 avatar">'
				html += '<div class="media-body flex-grow-1 ms-3">';
				html += '<h5>'+res[0].user[0]+'</h5>';
				html += '<p>'+res[0].comment+'</p>'
				html += '</div>';
				html += '</li>';

				$('#comment-tmp').prepend(html);

				$('#body').val('');
				
			}
		});
	});
</script>

<script type="text/javascript">
	$(()=>{
		var number = $("#check").val();

		$('#save').bind('click', ()=>{
			let u_id_post = $("#id_post").val();
			let u_id_user = $("#id_user").val();
			let u_check = $("#check").val();

			if(number == check) {
				check = $("#check").val();
			} else {
				check = number;
			}


			$.ajax({
				url: 'saveAction.php',
				method: 'POST',
				data:{
					id_post: u_id_post,
					id_user: u_id_user,
					check: u_check
				},
				dataType:'json'
			}).done((res)=>{

				if(number == 1) {
				number = 0;
				} else if(number == 0) {
					number = 1;
				}

				if(res.errors == '') {
					if($('#save').hasClass('salvo')) {
						$('#save').removeClass('salvo')
						$('#save').addClass('salvar')
						$('#save').html('SALVAR')
					} else {
						$('#save').removeClass('salvar')
						$('#save').addClass('salvo')
						$('#save').html('SALVO')
					}
				}
			});
		});
	});
</script>







