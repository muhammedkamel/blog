<?php
require_once __DIR__.'/../helpers/authenticate.php';
require_once 'partials/header.php';

$authenticate = new Authenticate;

if(isset($_POST['username'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])){
	$error = $authenticate->login($_POST['username'], $_POST['password']);
}

?>

<div class="container col-xs-6 col-xs-offset-3">
	<h1 style="text-align: center;">Please Sign in</h1>
	<form action="login.php" method="POST">
		<div class="form-group">
			<label for="username" class="control-label"></label>
			<input type="text" name="username" id="username" class="form-control"></div>
		<div class="form-group">
			<label for="password" class="control-label"></label>
			<input type="password" name="password" id="password" class="form-control">
		</div>
		<div class="form-group">
			<button class="btn btn-block btn-primary">Login</button>
		</div>
		<?php if(isset($error)):?>
			<div class="alert alert-danger" role="alert">
				<p class="text-center" style="font-size: 1.5em;">
				<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				<?= $error; ?></p>
			</div>
		<?php endif?>
	</form>

</div>

<?php require_once 'partials/footer.php';?>