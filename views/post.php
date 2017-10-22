<?php
require_once 'partials/header.php';
// require_once __DIR__.'/../Controllers/PostsController.php';
require_once __DIR__ . '/postsview.php';

$postsView = new PostsView;

if (isset($_GET['post']) && ($id = intval($_GET['post'])) > 0) {
	$post = $postsView->showPost($id);
} else {
	header('Location: posts.php');
}

?>




<?php if ($post): ?>
	<div class="row">
		<h1 class="text-center"><?=$post->title;?></h1>
		<p class="body"><?=$post->body;?></p>
	</div>
<?php else: ?>
	<?php header('Location: ' . ROOT_URL . '404.php');?>
<?php endif?>


<?php require_once 'partials/footer.php';?>