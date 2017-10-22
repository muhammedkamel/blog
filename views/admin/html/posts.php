<?php 
require_once __DIR__.'/../../postsview.php';
require_once __DIR__.'/../../../Helpers/Authenticate.php';
require_once __DIR__.'/../../../Controllers/IPsController.php';

use Blog\Controllers\IPsController as IPsController;
use Blog\Helpers\Authenticate as Authenticate;

// check if the IP is banned
$ipsController = new IPsController;
$ipsController->isBanned();

// authenticate the user
$authenticate = new Authenticate;
$authenticate->is_loggedin();


$postsView  = new PostsView;

// you need to check if the edit method changes it 
$offset     = 0;


if(isset($_POST['action']) && $_POST['action'] == 'add' && !empty($_POST['data'])){ // add new post
    $postsView->addPost($_POST['data']);
}elseif(isset($_POST['action'], $_POST['id']) && $_POST['action'] == 'get' && ($id = intval($_POST['id'])) > 0){ // get post to edit
    $postsView->getPost($id);
}elseif(isset($_POST['action']) && $_POST['action'] == 'edit' && !empty($_POST['data']) && ($id=intval($_POST['id'])) > 0){ // update post
    $postsView->editPost($id, $_POST['data']);
}elseif(isset($_POST['action']) && $_POST['action'] == 'delete' && ($id = intval($_POST['id'])) > 0){ // delete post
    $postsView->deletePost($id);
}elseif(isset($_POST['action']) && $_POST['action'] == 'get_statuses'){ // get all statuses
    if($statuses = $postsView->getStatuses()){
        header('Content-Type: application/json');
        echo json_encode($statuses);
        exit;
    }else{
        header('HTTP/1.1 503 Service Temporarily Unavailable');
    }
}

// paginate posts
if(isset($_GET['page']) && ($page = intval($_GET['page'])) >= 0){
    $pagination = $postsView->paginate($page);
    // needs the offset from the paginator
    $posts      = $postsView->getPosts($pagination['offset']);
}else{
    $pagination = $postsView->paginate();
    $posts      = $postsView->getPosts();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>Blog dashboard</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="../plugins/bower_components/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/default.css" id="theme" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style>
        #publish-date :hover{
            cursor: pointer;
        }
    </style>
</head>

<body class="fix-header">
   
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->

        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <a href="logout.php" class="btn btn-primary btn-block">Logout <span class="glyphicon glyphicon-off"></span></a>
                </div>
                <ul class="nav" id="side-menu">
                    <li style="padding: 70px 0 0;">
                        <a href="../../posts.php" class="waves-effect"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>Home</a>
                    </li>
                    <li>
                        <a href="posts.php" class="waves-effect"><i class="fa fa-list-alt fa-fw" aria-hidden="true"></i>Posts</a>
                    </li>
                    <li>
                        <a href="banned-ips.php" class="waves-effect"><i class="fa fa-ban fa-fw" aria-hidden="true"></i>Banned IPs</a>
                    </li>


                </ul>

            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
                
            <div class="container-fluid">
                <div class="row">
                    <button class="btn btn-info btn-block" id="add">Add new Post <i class="glyphicon glyphicon-plus-sign"></i></button>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="posts" style="background-color: white;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Summery</th>
                                    <th>Body</th>
                                    <th>Publish date</th>
                                    <th>Status</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($posts as $post): ?>
                                <tr>
                                    <td><?= $post->id; ?></td>
                                    <td><?= $post->title;?></td>
                                    <td><?= $post->summery;?></td>
                                    <td><?= $post->body;?></td>
                                    <td><?= $post->publish_at;?></td>
                                    <td><?= $post->status;?></td>
                                    <td>
                                        <input type="hidden" name="id" value="<?= $post->id;?>">
                                        <button class="btn btn-success edit"><i class="glyphicon glyphicon-edit"></i></button>
                                        <button class="btn btn-danger delete"><i class="glyphicon glyphicon-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <nav aria-label="pagination">
              <ul class="pager">
                <li><a href="?page=<?= $pagination['previous'];?>">Previous</a></li>
                <li><a href="?page=<?= $pagination['next'];?>">Next</a></li>
              </ul>
            </nav>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2017 &copy; Ample Admin brought to you by wrappixel.com </footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap-datetimepicker/moment.min.js"></script>
    <script src="bootstrap-datetimepicker/transition.js"></script>
    <script src="bootstrap-datetimepicker/collapse.js"></script>
    
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Counter js -->
    <script src="../plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!-- chartist chart -->
    <script src="../plugins/bower_components/chartist-js/dist/chartist.min.js"></script>
    <script src="../plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="../plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/dashboard1.js"></script>
    <script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>
    <script src="bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
