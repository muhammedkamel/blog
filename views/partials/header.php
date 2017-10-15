<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<style>
	.body{
		font-size: 1.3em !important;
	}
	.summery{
		font-size: 1.3em;
	}
	.panel-title{
		font-weight: bold;
		font-size: 1.5em;
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