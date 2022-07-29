<?php 

require 'config.php';
require 'models/Auth.php';
require 'dao/savedPostDaoMySql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin();

$sPosts = new  savedPostDaoMySql($pdo);
$posts = $sPosts->listSaved($userInfo->id);

$pageTitle = 'Seus posts salvos';

?>
	 <?php  require 'partials/header.php';?>
		
		<section class="body-content">
			<div class="container-fluid">
				<h2 style="margin-bottom: 30px;">POSTS SALVOS</h2>

				<div class="row">
					
					<?php if($posts['posts'] != false): ?>
						<?php foreach($posts['posts'] as $post): ?>
							<?php  require 'partials/feed-item.php'?>
						<?php endforeach;?>
					<?php else: ?>
						<i>Não há registros...</i>
					<?php endif;?>

				</div>

					<?php for($i = 0; $i < $posts['pages']; $i++): ?>	
						<a class="paginate-item" href="<?=$base?>/savedPostPage.php?page=<?=$i + 1?>"><?= $i + 1 ?></a>  
					<?php endfor;?>
				
			</div>	
		</section>

 <?php  require 'partials/footer.php';?>
	</html> 