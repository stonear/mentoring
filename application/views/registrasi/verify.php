<?php $this->load->view('template/head'); ?>

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
            <div class="row clearfix">
                <div class="col-md-8 col-md-offset-2">
                    <div class="card">
                        <div class="header">
                            <h2>PENDAFTARAN MENTOR</h2>
                        </div>
                        <div class="body">
                            <p>
                            	Email anda belum terverifikasi. Silahkan cek kotak masuk pada email anda.<br><br>
                                <a href="<?php echo base_url(); ?>Registrasi/resend/<?php echo $this->session->userdata('nrp') ?>" class="btn btn-default waves-effect waves-float">
                                    Kirim ulang kode verifikasi
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>