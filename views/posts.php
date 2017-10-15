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


	<hr style="margin-bottom: 5em; ">
	<?php if($posts):
	  foreach ($posts as $post) :?>
		<div class="row">
		  	<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title"><?= $post->title;?></h3>
			  </div>
			  <div class="panel-body">
			    <p class="summery"><?= $post->summery;?></p>
			  </div>
			  <div class="panel-footer">
			  	<a class="btn btn-info" href="post.php?post=<?= $post->id ?>" target="_blank">Read More</a>
			  </div>
			</div>
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


<?php require_once 'partials/footer.php';?>