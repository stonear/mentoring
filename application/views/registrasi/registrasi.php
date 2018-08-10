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
                                            <input type="text" class="form-control" id="nrp" name="nrp" required>
                                            <label class="form-label">NRP</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" class="form-control nama" name="nama" readonly>
                                            <label class="form-label">Nama (Otomatis)</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" class="form-control jenis" name="jenis" readonly>
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
                                            <input type="text" class="form-control" name="telp" pattern="[0-9]+" required>
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

                                <h3>Keterangan</h3>
                                <fieldset>
                                     <p>
                                        Pernah menjadi mentor sebelumnya?
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
                                    </p>
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

                onFinished: function (event, currentIndex) {
                    var form_submit = $(this);
                    form_submit.submit();
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
                        var html_nama = '';
                        var html_jenis = '';
                        var i;
                        for(i = 0; i < data.length; i++)
                        {
                           if (data[i].id == nrp || data[i].nrp == nrp) html_nama += data[i].nama_lengkap;
                           if (data[i].jenis_kelamin == 'L') html_jenis = 'Laki-Laki';
                            else html_jenis = 'Perempuan';
                        }
                        $('input.nama').attr("value", html_nama);
                        $('input.jenis').attr("value", html_jenis);
                   }
               });
            });

            $("#yap").click(function()
            {
                $("#pernah").attr("style", "display: block;");
                $("#tidakpernah").attr("style", "display: none;");
            });
            $("#tidak").click(function()
            {
                $("#pernah").attr("style", "display: none;");
                $("#tidakpernah").attr("style", "display: block;");
            });

            $("#file").change(function()
            {
                $("#labelfile").text("File CV telah terpilih, silahkan click Finish!");
            });
        });

        function setButtonWavesEffect(event) {
            $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
            $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
        }
    </script>
</body>
</html>