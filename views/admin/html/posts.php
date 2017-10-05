<?php 
require_once __DIR__.'/../../../controllers/postscontroller.php'; 
require_once __DIR__.'/../../../controllers/statusescontroller.php'; 
$postsController    = new PostsController;
$posts              = $postsController->paginatePostsWithStatus(0, 10);
$statusesController = new StatusesController;
$statuses           = $statusesController->getAllStatuses();
$status_id          = 0;

if(isset($_POST['action']) && $_POST['action'] == 'add' && !empty($_POST['data'])){
    // add post    
    if($postsController->addNewPost($_POST['data'])){
        echo json_encode(['success' => true]);
        exit;
    }else{
        header('HTTP/1.1 503 Service Temporarily Unavailable');
    }

}elseif(isset($_POST['action']) && $_POST['action'] == 'edit' && ($id = intval($_POST['id'])) > 0){
    if($post = $postsController->getPostByID($id)){
        // get the post status
        // $post->status = $statuses[$post->status_id - 1]->status;
        $status_id = $post->status_id;
        header('Content-Type: application/json');
        echo json_encode($post);
        exit;
    }else{
        header('HTTP/1.1 503 Service Temporarily Unavailable');
    }
}elseif(isset($_POST['action']) && $_POST['action'] == 'update' && !empty($_POST['data'])){
    if($postsController->editPost($_POST['id'], $_POST['data'])){
        echo json_encode(['success' => true]);
        exit;
    }else{
        header('HTTP/1.1 503 Service Temporarily Unavailable');
    }
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
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
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
                    <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Blog Dashboard</span></h3>
                </div>
                <ul class="nav" id="side-menu">
                    <li style="padding: 70px 0 0;">
                        <a href="index.php" class="waves-effect"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>Dashboard</a>
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
    <script>

        function getPostData(id){
            $.ajax({
                url:    'posts.php',
                method: 'POST',
                data: {action: 'edit', id: id}
            }).done(function(data){
                postID      = '<input type="hidden" name="id" value="'+data.id+'">';
                title       = data.title;
                body        = data.body;
                summery     = data.summery;
                status_id   = data.status_id;
                date        = data.date;
                console.log(data);
            });
        }

        function buildHTMLBody(data){
            console.log(data);
            return postID+'<div class="form-group">\
                    <label for="title" class="control-label">Title</label>\
                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="'+title+'">\
                </div>\
                <div class="form-group">\
                    <label for="body" class="control-label">Body</label>\
                    <textarea name="body" id="body" cols="30" rows="10" class="form-control" placeholder="Body here">'+body+'</textarea>\
                </div>\
                <div class="form-group">\
                    <label for="summery" class="control-label">Summery</label>\
                    <textarea name="summery" id="summery" cols="30" rows="5" class="form-control" placeholder="summery here">'+summery+'</textarea>\
                </div>\
                <div class="form-group">\
                    <label for="status" class="control-label">Status</label>\
                    <select id="status" name="status">\
                     <?php foreach($statuses as $status): ?>\
                        <option value="<?= $status->id;?>" <?php echo $status_id; if($status->id == $status_id) echo 'selected';?>><?= $status->status;?></option>\
                     <?php endforeach ?>\
                    </select>\
                </div>\
                <div class="container">\
                    <div class="row">\
                        <div class="col-sm-6">\
                            <div class="form-group">\
                                <div class="input-group date" id="publish-date">\
                                    <input type="text" class="form-control" id="date" value="'+date+'"/>\
                                    <span class="input-group-addon">\
                                    <span class="glyphicon glyphicon-calendar"></span>\
                                    </span>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>';        
        }

        function makeBody(ele){
            var postID = title = body = summery = status = status_id = date = '';

            if(typeof ele === 'undefined') ele = null;

            if(ele){
                var id = $(ele).parent().children('input[name="id"]').val();
                return $.when(getPostData(id)).done(buildHTMLBody('hamada'));
            }else{
                return buildHTMLBody(postID, title, body, summery, status_id, date);
            }
            
        }

        $(function(){
            $('#posts').on('click', '.edit', function(){
                var body = makeBody(this);
                data = {
                    id: 'edit-post',
                    size: 'lg',
                    title: 'Edit Post',
                    body:  body,
                    footer: true,
                    autohide: true
                }
                generateModal(data);
            });

            // show add post modal
            $('#add').on('click', function(){
                console.log('clicked');
                var body = makeBody();
                // <!-- <div class="form-group">\
                //     <label for="publish-date" class="control-label">Publish date</label>\
                //     <input readonly type="text" name="publish-date" id="publish-date" class="form-control">\
                // </div>'; -->
                data = {
                    id: 'add-post',
                    size: 'lg',
                    title: 'Add New Post',
                    body:  body,
                    footer: true,
                    autohide: true
                }
                generateModal(data);
            });

        });
        
    </script>

</body>

</html>
