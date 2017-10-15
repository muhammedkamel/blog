<?php 
require_once __DIR__.'/postsview.php';
require_once __DIR__.'/../translations.php';
require_once 'partials/header.php';
require_once __DIR__.'/../controllers/ipscontroller.php';


$ipsController = new IPsController;
$ipsController->isBanned();


$postsView  = new PostsView;

if(isset($_GET['search']) && !empty($_GET['search'])){
	$posts = $postsView->search($_GET['search']);
}elseif(isset($_GET['page']) && ($page = intval($_GET['page'])) > 0){
    $pagination = $postsView->paginate($page, 'WHERE status_id= :status_id', [':status_id' => ACTIVE]);
    // needs the offset from the paginator
    $posts      = $postsView->showPosts($pagination['offset']);
}else{
    $pagination = $postsView->paginate(1, 'WHERE status_id= :status_id', [':status_id' => ACTIVE]);
    $posts      = $postsView->showPosts();
}

?>

<style>
	.summery{
		font-size: 1.3em;
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
			<?= $translate['en']['no_result']; ?></p>
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