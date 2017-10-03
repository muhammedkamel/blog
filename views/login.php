<?php require_once 'partials/header.php';?>

<div class="container col-xs-6 col-xs-offset-3">
	<h1 style="text-align: center;">Please Sign in</h1>
	<form action="">
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
	</form>

</div>

<?php require_once 'partials/footer.php';?>