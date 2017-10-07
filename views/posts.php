<?php 
require_once __DIR__.'/postsview.php';
require_once 'partials/header.php';

$postsView  = new PostsView;

if(isset($_GET['page']) && ($page = intval($_GET['page'])) >= 0){
    $pagination = $postsView->paginate($page);
    // needs the offset from the paginator
    $posts      = $postsView->getPosts($pagination['offset']);
}else{
    $pagination = $postsView->paginate();
    $posts      = $postsView->getPosts();
}

?>
<div class="container col-xs-8 col-xs-offset-2">
	<?php foreach ($posts as $post) :?>
		<div class="row">
			<h1 class="text-center"><?= $post->title;?></h1>
			<p class="summery"><?= $post->summery;?></p>
			<p class="body" style="display: none;"><?= $post->body;?></p>
			<a href="#" class="read">Read More</a>
		</div>
	<?php endforeach?>

	<nav aria-label="pagination">
      <ul class="pager">
        <li><a href="?page=<?= $pagination['previous'];?>">Previous</a></li>
        <li><a href="?page=<?= $pagination['next'];?>">Next</a></li>
      </ul>
    </nav>
</div>

<?php require_once 'partials/footer.php';?>

<script>
	$('.row').on('click', '.read', readMore);

	function readMore(){
		var parent = $(this).hide().parent();
		parent.children('.summery').slideUp().end()
		.children('.body').slideDown();
	}
</script>