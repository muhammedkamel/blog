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
                                <tr>
                                    <td>1</td>
                                    <td>title</td>
                                    <td>summery</td>
                                    <td>body</td>
                                    <td>publish date</td>
                                    <td>draft</td>
                                    <td>
                                        <button class="btn btn-success edit"><i class="glyphicon glyphicon-edit"></i></button>
                                        <button class="btn btn-danger delete"><i class="glyphicon glyphicon-trash"></i></button>
                                    </td>
                                </tr><tr>
                                    <td>2</td>
                                    <td>title</td>
                                    <td>summery</td>
                                    <td>body</td>
                                    <td>publish date</td>
                                    <td>active</td>
                                    <td>
                                        <button class="btn btn-success edit"><i class="glyphicon glyphicon-edit"></i></button>
                                        <button class="btn btn-danger delete"><i class="glyphicon glyphicon-trash"></i></button>
                                    </td>
                                </tr>
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
    <script src="bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $(function(){
            $('#add').on('click', function(){
                console.log('clicked');
                var body = '<div class="form-group">\
                    <label for="title" class="control-label">Title</label>\
                    <input type="text" name="title" id="title" class="form-control" placeholder="Title">\
                </div>\
                <div class="form-group">\
                    <label for="body" class="control-label">Body</label>\
                    <textarea name="body" id="body" cols="30" rows="10" class="form-control" placeholder="Body here"></textarea>\
                </div>\
                <div class="form-group">\
                    <label for="summery" class="control-label">Summery</label>\
                    <textarea name="summery" id="summery" cols="30" rows="5" class="form-control" placeholder="summery here"></textarea>\
                </div>\
                <div class="form-group">\
                    <label for="publish-date" class="control-label">Publish date</label>\
                    <input readonly type="text" name="publish-date" id="publish-date" class="form-control">\
                </div>';

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

        $('#posts').on('click', '.edit', function(){
            console.log(this);
            $.ajax({
                url: '../../../'
            }).done(function(){

            });
        });
    </script>

</body>

</html>