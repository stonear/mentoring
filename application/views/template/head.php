<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?> | simITS</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Favicon-->
  <link rel="icon" href="<?php echo base_url(); ?>asset/favicon.ico" type="image/x-icon">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
  <!-- Bootstrap Core Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
  <!-- Bootstrap Material Datetime Picker Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
  <!-- Bootstrap Select Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
  <!-- Waves Effect Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/node-waves/waves.css" rel="stylesheet" />
  <!-- Animation Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/animate-css/animate.css" rel="stylesheet" />
  <!-- JQuery DataTable Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
  <!-- Multi Select Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/multi-select/css/multi-select.css" rel="stylesheet">
  <!-- Sweetalert Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
  <!-- Custom Css -->
  <link href="<?php echo base_url(); ?>asset/css/style.css" rel="stylesheet">
  <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
  <link href="<?php echo base_url(); ?>asset/css/themes/all-themes.css" rel="stylesheet" />

  <style type="text/css">
    /* Kustom Form Wizard ================================= */
    .wizard > .actions a {
      background: #2196F3; }
      .wizard > .actions a:hover, .wizard > .actions a:active {
        background: #2196F3; }

    .wizard .steps .done a {
      background-color: rgba(33, 150, 243, 0.6); }
      .wizard .steps .done a:hover, .wizard .steps .done a:active, .wizard .steps .done a:focus {
        background-color: rgba(33, 150, 243, 0.5); }

    .wizard .steps .error a {
      background-color: #F44336 !important; }

    .wizard .steps .current a {
      background-color: #2196F3; }
      .wizard .steps .current a:active, .wizard .steps .current a:focus, .wizard .steps .current a:hover {
        background-color: #2196F3; }
  </style>

  <!-- Jquery Core Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap Core Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/bootstrap/js/bootstrap.js"></script>
  <!-- Moment Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/momentjs/moment.js"></script>
  <!-- Bootstrap Material Datetime Picker Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
  <!-- Select Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/bootstrap-select/js/bootstrap-select.js"></script>
  <!-- Slimscroll Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
  <!-- Jquery CountTo Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-countto/jquery.countTo.js"></script>
  <!-- Waves Effect Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/node-waves/waves.js"></script>
  <!-- Chart Plugins Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/chartjs/Chart.bundle.js"></script>
  <!-- Jquery DataTable Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/jquery.dataTables.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
  <!-- JQuery Steps Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/jquery-steps/jquery.steps.js"></script>
  <!-- Multi Select Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/multi-select/js/jquery.multi-select.js"></script>
  <!-- SweetAlert Plugin Js -->
  <script src="<?php echo base_url(); ?>asset/plugins/sweetalert/sweetalert.min.js"></script>
  <!-- Custom Js -->
  <script src="<?php echo base_url(); ?>asset/js/admin.js"></script>
  <!-- Demo Js -->
  <!-- <script src="<?php echo base_url(); ?>asset/js/demo.js"></script> -->
</head>