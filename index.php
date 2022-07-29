<?php 

require 'config.php';
require 'models/Auth.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin();

$rPosts = new PostDaoMySql($pdo);
$posts = $rPosts->getHomeFeed($userInfo->id);

$pageTitle = 'HOME';

?>
	 <?php  require 'partials/header.php';?>
		
		<section class="body-content">
			<div class="container-fluid">
				<div class="row search">
					<div id="form">
						<input type="text" id="search" name="search" placeholder="Pesquise por algo...">
					</div>
				</div>

			<div class="row">
				
				<?php if(isset($posts)): ?>
					<?php foreach($posts['posts'] as $post): ?>
						<?php  require 'partials/feed-item.php'?>
					<?php endforeach;?>
				<?php endif;?>

			</div>

			<?php if($posts['pages'] > 1): ?>
				<?php for($i = 0; $i < $posts['pages']; $i++ ): ?>
					 <a class="paginate-item" href="<?=  $base?>/index.php?page=<?=$i + 1 ?>"><?= $i + 1 ?></a>
				<?php endfor;?>
			<?php endif;?>
		</div>

		</section>

 <?php  require 'partials/footer.php';?>

<script type="text/javascript" src="<?=$base?>/assets/js/jquery-3.6.0.min.js"></script>
 <script type="text/javascript">
 	$(()=>{
 		
 		const BASE = 'http://localhost/projetos_php/myBlogsOO'
 		$('#search').bind('keyup', function(e){
 			if(e.keyCode === 13) {
 					var term = $(this).val();

 				
 				location.href = BASE+'/searchResultPage.php?term='+term;
 				
 			}
 		})	
 	})
 </script>
	