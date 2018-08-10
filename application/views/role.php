<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Select Role | simITS</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Favicon-->
  <link rel="icon" href="<?php echo base_url(); ?>asset/favicon.ico" type="image/x-icon">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
  <!-- Bootstrap Core Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
  <!-- Waves Effect Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/node-waves/waves.css" rel="stylesheet" />
  <!-- Custom Css -->
  <link href="<?php echo base_url(); ?>asset/css/style.css" rel="stylesheet">
  <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
  <link href="<?php echo base_url(); ?>asset/css/themes/all-themes.css" rel="stylesheet" />

  <!-- Jquery Core Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap Core Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/bootstrap/js/bootstrap.js"></script>
  <!-- Waves Effect Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/node-waves/waves.js"></script>
  <!-- Custom Js -->
  <script src="<?php echo base_url(); ?>asset/js/admin.js"></script>
  <!-- Demo Js -->
  <script src="<?php echo base_url(); ?>asset/js/demo.js"></script>
</head>
<body class="theme-blue">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Silahkan tunggu...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>asset/images/shield-2.png" style="width: auto; height: 100%;"></img></a>
            </div>
        </div>
    </nav>
    <section class="content" style="margin-left: 0px;">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-3 col-md-offset-3">
                    <div class="card">
                        <div class="header">
                            <h2>DOSEN PEMBINA</h2>
                        </div>
                        <div class="body">
                            Masuk sebagai dosen pembina.<br><br>
                            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Auth/select_role/pembina" method="post">
                                <button type="submit" class="btn btn-primary btn-block waves-effect"><i class="material-icons">input</i> MASUK</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="header">
                            <h2>DOSEN KELAS</h2>
                        </div>
                        <div class="body">
                            Masuk sebagai dosen kelas.<br><br>
                            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Auth/select_role/dosen" method="post">
                                <button type="submit" class="btn btn-primary btn-block waves-effect"><i class="material-icons">input</i> MASUK</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>