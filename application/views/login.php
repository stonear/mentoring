<?php Include "template/head.php"; ?>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>asset/images/shield-1.png" style="width: 100%; height: auto;"></img></a>
            <small>Sistem Informasi Mentoring <b>Institut Teknologi Sepuluh Nopember</b></small>
        </div>
        <div class="card">
            <div class="body">
                <form autocomplete="off" id="sign_in" action="<?php echo base_url(); ?>Auth/auth" method="POST">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="NRP/NIP" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-8 col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/node-waves/waves.js"></script>
    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url(); ?>asset/plugins/jquery-validation/jquery.validate.js"></script>
    <!-- Custom Js -->
    <script src="<?php echo base_url(); ?>asset/js/admin.js"></script>
    <script>
        $(function ()
        {
            $('#sign_in').validate(
            {
                highlight: function (input)
                {
                    console.log(input);
                    $(input).parents('.form-line').addClass('error');
                },
                unhighlight: function (input)
                {
                    $(input).parents('.form-line').removeClass('error');
                },
                errorPlacement: function (error, element)
                {
                    $(element).parents('.input-group').append(error);
                }
            });
            $('[data-toggle="popover"]').popover();
        });
    </script>
</body>
</html>