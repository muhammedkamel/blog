<?php 
require_once __DIR__.'/../../../config.php';
require_once ROOT_DIR.'/helpers/authenticate.php';
require_once __DIR__.'/../../../controllers/ipscontroller.php';

// check if it's banned
$ipsController = new IPsController;
$ipsController->isBanned();

// logout
$authenticate = new Authenticate;
$authenticate->logout();