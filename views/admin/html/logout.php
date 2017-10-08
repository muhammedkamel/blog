<?php 
require_once __DIR__.'/../../../config.php';
require_once ROOT_DIR.'/helpers/authenticate.php';
require_once __DIR__.'/../../../controllers/ipscontroller.php';


$ipsController = new IPsController;
$ipsController->isBanned();

$authenticate = new Authenticate;

$authenticate->logout();