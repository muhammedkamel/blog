<?php 
require_once 'partials/header.php';
require_once __DIR__.'/../controllers/postscontroller.php';
require_once __DIR__.'/postsview.php';

$postsView = new PostsView;

if(isset($_GET['post']) && ($id = intval($_GET['post'])) > 0){
	$post = $postsView->showPost($id);
}else{
    header('Location: posts.php');
}

?>


<style>
	.body{
		font-size: 1.3em !important;
	}
</style>

<div class="container col-xs-8 col-xs-offset-2">

	<div class="col-xs-12" style="margin-top: 1em;">
	    <a href="admin/html/posts.php" class="btn btn-primary col-xs-3">Login <span class="glyphicon glyphicon-off"></span></a>
	    
		<form class="navbar-form col-xs-5 col-xs-offset-4" role="search" action="posts.php" method="GET">
			<div class="input-group add-on">
			  <input class="form-control" placeholder="Search" name="search" id="srch-term" type="text" value="<?php if(isset($_GET['search'])) echo $_GET['search'];?>">
			  	<div class="input-group-btn">
			    	<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
				</div>
			</div>
		</form>
	</div>

		<?php if($post) : ?>
			<div class="row">
				<h1 class="text-center"><?= $post->title;?></h1>
				<p class="body"><?= $post->body;?></p>
			</div>
		<?php else: ?> 
			<?php header('Location: '.ROOT_URL.'/404.php'); ?>
		<?php endif ?>
</div>

<?php require_once 'partials/footer.php';?>