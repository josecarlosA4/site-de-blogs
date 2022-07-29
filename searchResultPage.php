<?php 

require 'config.php';
require 'models/Auth.php';
require 'dao/savedPostDaoMySql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkLogin();

$term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_SPECIAL_CHARS);
$page = filter_input(INPUT_GET, 'page');


$rPosts = new PostDaoMySql($pdo);
$posts = $rPosts->getResultSearch($term);



$pageTitle = 'Pesquisa: '.$term;

?>
	 <?php  require 'partials/header.php';?>
		
		<section class="body-content">
			<div class="container-fluid">
			<h2 style="margin-bottom: 30px;">Exibindo resultados de: <?= $term ?></h2>

			<div class="row">
				
				<?php if($posts['posts'] != false): ?>
					<?php foreach($posts['posts'] as $post): ?>
						<?php  require 'partials/feed-item.php'?>
					<?php endforeach;?>
				<?php else: ?>
					<i>Não há registros...</i>
				<?php endif;?>

			</div>

				<?php if($posts['posts'] != false): ?>
					<?php for($i = 0; $i < $posts['pages']; $i++): ?>
						<a class="paginate-item" href="<?=  $base?>/searchResultPage.php?page=<?=$i + 1 ?>&term=<?= $term ?>"><?= $i + 1 ?></a>
					<?php endfor; ?>
				<?php endif; ?>
			
		</div>	
		</section>

 <?php  require 'partials/footer.php';?>
	</html> 