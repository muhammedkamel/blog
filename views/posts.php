<?php 
require_once __DIR__.'/postsview.php';
require_once 'partials/header.php';

$postsView  = new PostsView;

if(isset($_GET['search']) && !empty($_GET['search'])){
	$posts = $postsView->search($_GET['search']);
}elseif(isset($_GET['page']) && ($page = intval($_GET['page'])) >= 0){
    $pagination = $postsView->paginate($page);
    // needs the offset from the paginator
    $posts      = $postsView->showPosts($pagination['offset']);
}else{
    $pagination = $postsView->paginate();
    $posts      = $postsView->showPosts();
}

?>

<style>
	.summery{
		font-size: 1.3em;
	}
</style>

<div class="container col-xs-8 col-xs-offset-2">
	<form class="navbar-form" style="text-align: right; margin-top: 1em;" role="search" action="posts.php" method="GET">
		<div class="input-group add-on">
		  <input class="form-control" placeholder="Search" name="search" id="srch-term" type="text" value="<?php if(isset($_GET['search'])) echo $_GET['search'];?>">
		  	<div class="input-group-btn">
		    	<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
	</form>
	<hr>
	<?php if($posts):
	  foreach ($posts as $post) :?>
		<div class="row">
			<h1 class="text-center"><?= $post->title;?></h1>
			<hr>
			<p class="summery"><?= $post->summery;?>
			 ... <a class="btn btn-info" href="post.php?post=<?= $post->id ?>" target="_blank">Read More</a>
			</p>
		</div>
	<?php endforeach ?>
	<?php else:?>
		<div class="alert alert-danger" role="alert">
			<p style="font-size: 2em;">
			<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			There is no posts with this search key</p>
		</div>
	<?php endif?>
	
	<?php if(isset($pagination)): ?>
		<nav aria-label="pagination">
	      <ul class="pager">
	        <li><a href="?page=<?= $pagination['previous'];?>">Previous</a></li>
	        <li><a href="?page=<?= $pagination['next'];?>">Next</a></li>
	      </ul>
	    </nav>
	<?php endif?>
</div>

<?php require_once 'partials/footer.php';?>