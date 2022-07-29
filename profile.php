<?php

require 'config.php';
require 'models/Auth.php';



$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin();

$id = filter_input(INPUT_GET, 'id');

if(!$id) {
	$id = $userInfo->id;
}

$request = new UserDaoMySql($pdo);
$posts = $request->getProfileFeed($id);



$user = $request->findById($id);

$relation = new UserRelationDaoMySql($pdo);
$check = $relation->checkFollow($userInfo->id, $id);


if($check == 1) {
	$msg = 'unfollow';
} else {
	$msg = 'follow';
}

$pageTitle = $userInfo->name." - Perfil";

?>

<?php require 'partials/header.php'; ?>

<section class="profile-header">
	<div class="container-fluid">
		<div class="cover">
			<img src="<?=$base?>/media/covers/<?=$user->cover?>">
		</div>
		<div class="row justify-content-between align-items-center">
			<div class="avatar">
				<img src="<?=$base?>/media/avatars/<?=$user->avatar?>">
				<?= $user->name ?>
			</div>
			<?php if($id != $userInfo->id): ?>
				<button id="follow-button-profile" class="follow-button <?= $msg ?>"><?= strtoupper($msg)?></button>
				<input type="hidden" id="user_from" name="user_from" value="<?=$userInfo->id?>">
				<input type="hidden" id="user_to" name="user_to" value="<?= $id?>">
				<input type="hidden" id="check" name="check" value="<?=$check ?>">
			<?php endif;?>
		</div>	
	</div>
</section>

<section class="body-content profile-content">
	<div class="container-fluid">
		<div class="row ">

			<?php if(count($posts['posts']) > 0):?>
				<?php foreach($posts['posts'] as $post): ?>
					<?php require 'partials/feed-item.php'; ?>
				<?php endforeach;?>
			<?php else: ?>
				<i>Não há registros...</i>
			<?php endif; ?>
		</div>
		<div style="margin-bottom: 30px;">
			<?php if($posts['pages'] > 1 ): ?>
			<?php for($i = 0; $i < $posts['pages']; $i++): ?>	
				<a class="paginate-item" href="<?=$base?>/profile.php?id=<?=$id?>&page=<?=$i + 1?>"><?= $i + 1 ?></a>  
			<?php endfor;?>
			<?php endif;?>	
		</div>
	
	</div>	
</section>

<?php require 'partials/footer.php'; ?>

<script type="text/javascript">

	var number = document.querySelector('#check').value;

	document.querySelector('#follow-button-profile').addEventListener('click', async (e)=>{

		let from = document.querySelector('#user_from').value;
		let to = document.querySelector('#user_to').value;
		let check = document.querySelector('#check').value;
		if(number == check ) {
			check = document.querySelector('#check').value;
		} else {
			check = number;
		}


		
		
		let body = new FormData();
		body.append('user_from', from);
		body.append('user_to', to);
		body.append('check', check);

		let req = await fetch('userRelationAction.php', {
			method:'POST',
			body:body
		});


		let data = await req.json();
		console.log(data);
		

		if(number == 1) {
			number = 0;
		} else if (number == 0) {
			number = 1;
		}

		if(data.errors == '') {
			if(e.target.classList.contains('follow')) {
				e.target.classList.remove('follow');
				e.target.classList.add('unfollow')
				e.target.innerHTML = 'UNFOLLOW';
			} else if(e.target.classList.contains('unfollow')) {
				e.target.classList.remove('unfollow');
				e.target.classList.add('follow')
				e.target.innerHTML = 'FOLLOW';
			}

		}
		
		
	});
</script>
