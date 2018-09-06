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
  <!-- Bootstrap Select Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
  <!-- Waves Effect Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/node-waves/waves.css" rel="stylesheet" />
  <!-- Animation Css -->
  <link href="<?php echo base_url(); ?>asset/plugins/animate-css/animate.css" rel="stylesheet" />
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

    /*.dropdown-menu.open {
      z-index: 999999 !important;
      overflow-y: scroll !important;}*/
  </style>
</head>
<body class="theme-blue">
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>asset/images/shield-2.png" style="width: auto; height: 100%;"></img></a>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <aside id="leftsidebar" class="sidebar" style="display: none;">
            <div class="menu">
                <ul class="list">
                    <li class="active">
                    </li>
                </ul>
            </div>
        </aside>
    </section>

    <section class="content" style="margin-left: 0px;">
        <div class="container-fluid">
            <!-- Advanced Form -->
            <div class="row clearfix">
                <div class="col-md-8 col-md-offset-2">
                    <?php if (!empty($error)) : ?>
                        <div class="<?php echo 'alert bg-red alert-dismissible'; ?>." role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <div class="card">
                        <div class="header">
                            <h2>PENDAFTARAN MENTOR</h2>
                        </div>
                        <div class="body">
                            <form id="wizard_form" autocomplete="off" action="<?php echo base_url(); ?>Registrasi/post" method="POST" enctype="multipart/form-data">
                                <h3>Biodata</h3>
                                <fieldset>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="nrp" name="nrp" required autofocus>
                                            <label class="form-label">NRP</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" class="form-control nama" name="nama" required readonly>
                                            <label class="form-label">Nama (Otomatis)</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" class="form-control jenis" name="jenis" required readonly>
                                            <label class="form-label">Jenis Kelamin (Otomatis)</label>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <div class="form-line">
                                            <select name="jenis" id="jenis" class="form-control show-tick" required>
                                                <option disabled selected style="display:none">Pilih Jenis Kelamin</option>
                                                <option value="L">Laki-Laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="telp" pattern="[0-9]{11,13}" required>
                                            <label class="form-label">No Telepon</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="email" class="form-control" name="email" required>
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="alamat" required>
                                            <label class="form-label">Alamat</label>
                                        </div>
                                    </div>
                                </fieldset>

                                <h3>Keterangan (1)</h3>
                                <fieldset>
                                    <label for="nilai">Nilai Mata Kuliah Agama</label>
                                    <br>
                                    <select name="nilai" required>
                                        <option disabled selected style="display:none">Pilih Nilai</option>
                                        <option value="A">A</option>
                                        <option value="AB">AB</option>
                                        <option value="B">B</option>
                                    </select>
                                    <br>
                                    <label for="pernah">Pernah Menjadi Mentor pada Semester</label>
                                    <br>
                                    <select name="pernah" required>
                                        <option value=0>Tidak Pernah</option>
                                        <?php for ($i = 1; $i <= 6; $i++): ?>
                                            <option value=<?php echo $i ?>>Semester <?php echo $i ?></option>
                                        <?php endfor ?>
                                    </select>
                                    <!-- <p>
                                        <label>Pernah menjadi mentor sebelumnya?</label>
                                        <div class="row clearfix">
                                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                <button class="btn btn-default btn-block waves-effect waves-float" id="yap">
                                                    Ya
                                                </button>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                <button class="btn btn-default btn-block waves-effect waves-float" id="tidak">
                                                    Tidak
                                                </button><br>
                                            </div>
                                        </div>
                                    </p>
                                    <div class="form-group form-float" id="pernah" style="display: none;">
                                        <div class="form-line">
                                            <input type="number" min="0" max="8" class="form-control" name="pernah">
                                            <label class="form-label">Telah menjadi mentor sebelumnya pada semester...</label>
                                        </div>
                                    </div>
                                    <p id="tidakpernah" style="display: none;">
                                        Silahkan klik Next!
                                    </p> -->
                                </fieldset>
                                <h3>Keterangan (2)</h3>
                                <fieldset>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" class="form-control" id="tahun" name="tahun" required>
                                            <label class="form-label">Pendaftaran Mentor untuk Tahun Ajaran ...</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="keterangan"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="semester" required>
                                                <option disabled selected style="display:none">Pendaftaran Mentor untuk Semester ...</option>
                                                <option value="1">Semester Gasal</option>
                                                <option value="2">Semester Genap</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <h3>Berkas</h3>
                                <fieldset>
                                    <p>
                                        1. Silahkan unduh template CV yang telah disediakan.<br>
                                        <div class="row clearfix">
                                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                                <a href="<?php echo base_url(); ?>asset/registration/cv.docx" class="btn btn-default btn-block waves-effect waves-float">
                                                    unduh template CV
                                                </a><br>
                                            </div>
                                        </div>
                                        2. Isi informasi dengan baik dan benar.<br>
                                        3. Lalu unggah kembali pada form berikut.
                                    </p>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input style="opacity: 0; cursor: pointer;" type="file" id="file" name="file" class="form-control" required>
                                            <label for="file" id="labelfile" style="cursor: pointer;">Pilih file CV . . .</label>
                                        </div>
                                        <small>Format file yang diperbolehkan adalah <strong>doc/docx</strong>.</small>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Advanced Form -->
        </div>
    </section>
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/node-waves/waves.js"></script>
    <!-- Multi Select Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/multi-select/js/jquery.multi-select.js"></script>
    <!-- JQuery Steps Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/jquery-steps/jquery.steps.js"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="<?php echo base_url(); ?>asset/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="<?php echo base_url(); ?>asset/plugins/jquery-validation/additional-methods.js"></script>

    <!-- SweetAlert Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/sweetalert/sweetalert.min.js"></script>

    <script>
        $(function () {
            var form = $('#wizard_form').show();
            form.steps({
                headerTag: 'h3',
                bodyTag: 'fieldset',
                transitionEffect: 'slideLeft',
                onInit: function (event, currentIndex) {
                    $.AdminBSB.input.activate();

                    //Set tab width
                    var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
                    var tabCount = $tab.length;
                    $tab.css('width', (100 / tabCount) + '%');

                    //set button waves effect
                    setButtonWavesEffect(event);
                },
                onStepChanging: function (event, currentIndex, newIndex) {
                    // if (newIndex == 1)
                    // {
                    //     $("#pernah").attr("style", "display: none;");
                    //     $("#tidakpernah").attr("style", "display: none;");
                    // }

                    if (currentIndex > newIndex) { return true; }

                    if (currentIndex < newIndex) {
                        form.find('.body:eq(' + newIndex + ') label.error').remove();
                        form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                    }

                    form.validate().settings.ignore = ':disabled,:hidden';
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex) {
                    setButtonWavesEffect(event);
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ':disabled';
                    return form.valid();
                },
                onFinished: function (event, currentIndex) {
                    var form_submit = $(this);
                    form_submit.submit();
                }
            });

            form.validate({
                highlight: function (input) {
                    $(input).parents('.form-line').addClass('error');
                },
                unhighlight: function (input) {
                    $(input).parents('.form-line').removeClass('error');
                },
                errorPlacement: function (error, element) {
                    $(element).parents('.form-group').append(error);
                },
                rules: {
                    'telp': {
                        pattern: /[0-9]{11,13}/
                    }
                }
            });

            $('#nrp').change(function()
            {
                var nrp = $('#nrp').val();
                $.ajax({
                    url : "<?php echo base_url(); ?>Registrasi/get_nama",
                    method : "POST",
                    data : {nrp: nrp},
                    async : false,
                    dataType : 'json',
                    success: function(data)
                    {
                        if (data.length == 0)
                        {
                            // alert("NRP anda belum terdaftar di integra.");
                            swal("NRP anda belum terdaftar di integra!");
                            $('input.nama').val("");
                            $('input.jenis').val("");
                        }

                        var html_nama = '';
                        var html_jenis = '';
                        var i;
                        for(i = 0; i < data.length; i++)
                        {
                           if (data[i].id == nrp || data[i].nrp == nrp) html_nama += data[i].nama_lengkap;
                           if (data[i].jenis_kelamin == 'L') html_jenis = 'Laki-Laki';
                            else html_jenis = 'Perempuan';
                        }
                        // $('input.nama').attr("value", html_nama);
                        // $('input.jenis').attr("value", html_jenis);
                        $('input.nama').val(html_nama);
                        $('input.jenis').val(html_jenis);
                    }
                });
            });

            // $("#yap").click(function()
            // {
            //     $("#pernah").attr("style", "display: block;");
            //     $("#tidakpernah").attr("style", "display: none;");
            // });
            // $("#tidak").click(function()
            // {
            //     $("#pernah").attr("style", "display: none;");
            //     $("#tidakpernah").attr("style", "display: block;");
            // });

            $("#file").change(function()
            {
                $("#labelfile").text("File CV telah terpilih, silahkan click Finish!");
            });

            $('#tahun').change(function()
            {
                var tahun = $('#tahun').val();
                var tahun = parseInt(tahun);
                $('div.keterangan').html('<small>(Tahun ajaran ' + tahun.toString() + '/' + (tahun + 1).toString() + ')</small>');
            });
        });

        $('.nilai-agama').select2();



        function setButtonWavesEffect(event) {
            $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
            $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
        }
    </script>
    <!-- Custom Js -->
    <script src="<?php echo base_url(); ?>asset/js/admin.js"></script>
    <!-- Demo Js -->
    <!-- <script src="<?php echo base_url(); ?>asset/js/demo.js"></script> -->
</body>
</html>