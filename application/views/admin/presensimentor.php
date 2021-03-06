<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>CARI PRESENSI MENTOR</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/presensimentor" method="post">
                    <div class="row clearfix">
                        <div class="col-md-3">
                            <select name="tahun" id="tahun" class="form-control show-tick" required>
                                <option disabled selected style="display:none">Pilih Tahun</option>
                                <?php foreach ($tahun as $t): ?>
                                    <option value="<?php echo $t->tahun ?>" <?php if ($t->tahun == $tahun_selected) echo 'selected' ?>>
                                        <?php echo $t->tahun ?>-<?php echo $t->tahun + 1 ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="semester" id="semester" class="form-control show-tick" required>
                                <option disabled selected style="display:none">Pilih Semester</option>
                                <option value="1" <?php if ($semester_selected == 1) echo 'selected' ?>>Gasal</option>
                                <option value="2" <?php if ($semester_selected == 2) echo 'selected' ?>>Genap</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="pembina" id="pembina" class="pembina form-control show-tick" required>
                                <!-- <option disabled selected style="display:none">Pilih Dosen Pembina</option> -->
                                <option value="-1" selected>Pilih Dosen Pembina</option>
                                <?php foreach ($pembina as $p): ?>
                                    <option value="<?php echo $p->nik ?>" <?php if ($p->nik == $pembina_selected) echo 'selected' ?>>
                                        <?php echo $p->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block waves-effect" data-toggle="tooltip" data-placement="bottom" title="cari presensi"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if(!empty($mentor)): ?>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="body">
                    <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_presensimentor/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$pembina_selected ?>" method="post">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table table-bordered table-striped table-condensed table-hover text-nowrap">
                                <thead>
                                    <?php if ($jadwal != -1): ?>
                                        <tr>
                                            <th style="vertical-align: middle;">No.</th>
                                            <th colspan="2" style="vertical-align: middle;">Absen\Jadwal</th>
                                            <?php for ($i = 1; $i <= $jumlahpertemuan; $i++): ?>
                                                <th style="vertical-align: middle;">
                                                    <label for="email_address"><small>Jadwal Pertemuan <?php echo $i ?></small></label>
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" name="<?php echo $i ?>" id="<?php echo $i ?>" class="datepicker form-control" placeholder="--/--/----"
                                                            <?php
                                                            foreach ($jadwal as $j)
                                                            {
                                                                if ($j->mingguke == $i and strlen($j->tanggal) == 10) echo 'value="'.$j->tanggal.'"';
                                                            }
                                                            ?>
                                                            />
                                                        </div>
                                                    </div>
                                                </th>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endif ?>
                                    <tr class="bg-cyan">
                                        <th><?php if ($jadwal == -1) echo 'No.' ?></th>
                                        <th>NRP</th>
                                        <th>Nama</th>
                                        <?php for ($i = 1; $i <= $jumlahpertemuan; $i++)
                                        echo '<th>Pertemuan '.$i.'</th>';
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1 ?>
                                    <?php foreach ($mentor as $m): ?>
                                        <tr>
                                            <td style="vertical-align: middle;"><?php echo $no++ ?></td>
                                            <td style="vertical-align: middle;"><?php echo $m->nrp ?></td>
                                            <td style="vertical-align: middle;"><?php echo $m->nama ?></td>
                                            <?php for ($i = 1; $i <= $jumlahpertemuan; $i++): ?>
                                                <td style="vertical-align: middle;">
                                                    <!-- <input
                                                    type="checkbox"
                                                    name="<?php echo $m->nrp.'-'.$i ?>"
                                                    id="<?php echo $m->nrp.'-'.$i ?>"
                                                    <?php
                                                    foreach ($presensi as $a)
                                                    {
                                                        if ($a->nrp == $m->nrp and $a->mingguke == $i)
                                                        {
                                                            if ($a->status == 1) echo 'checked';
                                                        }
                                                    }
                                                    ?>
                                                    />
                                                    <label for="<?php echo $m->nrp.'-'.$i ?>"></label> -->
                                                    <select name="<?php echo $m->nrp.'-'.$i ?>" id="<?php echo $m->nrp.'-'.$i ?>" class="form-control show-tick">
                                                        <option value="0" disabled selected style="display:none">---</option>
                                                        <?php $status = -1 ?>
                                                        <?php foreach ($presensi as $a)
                                                        {
                                                            if ($a->nrp == $m->nrp and $a->mingguke == $i)
                                                                $status = $a->status;
                                                        }
                                                        ?>
                                                        <option value="1" <?php if ($status == 1) echo 'selected' ?>>
                                                            Hadir
                                                        </option>
                                                        <option value="2" <?php if ($status == 2) echo 'selected' ?>>
                                                            Sakit
                                                        </option>
                                                        <option value="3" <?php if ($status == 3) echo 'selected' ?>>
                                                            Izin
                                                        </option>
                                                        <option value="4" <?php if ($status == 4) echo 'selected' ?>>
                                                            Alpha
                                                        </option>
                                                    </select>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-block waves-effect">SIMPAN</button>
                    </form>
                </div>
            </div>
        </div>
        <?php if ($pembina_selected != -1): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="header">
                        <h2>Rekapitulasi Presensi (xlsx)</h2>
                    </div>
                    <div class="body">
                        <a href="<?php echo base_url(); ?>Admin/download_presensimentor/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$pembina_selected ?>" class="btn btn-primary btn-block waves-effect"><i class="material-icons">file_download</i> UNDUH</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="header">
                        <h2>Template Presensi (xlsx)</h2>
                    </div>
                    <div class="body">
                        <a href="<?php echo base_url(); ?>Admin/download_templatepresensimentor/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$pembina_selected ?>" class="btn btn-primary btn-block waves-effect"><i class="material-icons">file_download</i> UNDUH</a>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="header">
                        <h2>File Presensi</h2>
                    </div>
                    <div class="body">
                        <div class="list-group">
                            <?php foreach ($file as $f): ?>
                                <!-- <a href="<?php echo $f->link ?>" class="list-group-item" target="_blank">
                                    <?php
                                        $name = explode("/", $f->link);
                                        echo '(Minggu-ke '.$f->mingguke.') '.end($name);
                                    ?>
                                </a> -->
                                <div class="list-group-item">
                                    <?php $name = explode("/", $f->link) ?>
                                    <a href="<?php echo $f->link ?>" target="_blank">
                                        (Pertemuan ke-<?php echo $f->mingguke ?>) <?php echo end($name) ?>
                                    </a>
                                    <a href="<?php echo base_url(); ?>Admin/hapus_filepresensimentor/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$pembina_selected.'/'.$f->mingguke ?>" class="badge bg-red">HAPUS</a>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="row clearfix">
                            <div class="col-xs-6">
                                <button type="button" class="btn btn-primary btn-block waves-effect" data-toggle="modal" data-target="#tambah-file"><i class="material-icons">file_upload</i> UNGGAH</button>
                            </div>
                            <div class="col-xs-6">
                                <a href="<?php echo base_url(); ?>Admin/download_filepresensimentor/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$pembina_selected ?>" class="btn btn-primary btn-block waves-effect"><i class="material-icons">file_download</i> UNDUH</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
<?php endif; ?>
<?php if ($pembina_selected != -1): ?>
    <div class="modal fade" id="tambah-file" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_filepresensimentor/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$pembina_selected ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Tambah File Presensi</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <select name="mingguke" id="mingguke" class="form-control show-tick" required>
                                        <option disabled selected style="display:none">Pilih Pertemuan-ke</option>
                                        <?php for ($i = 1; $i <= $jumlahpertemuan; $i++): ?>
                                            <option value="<?php echo $i ?>">Pertemuan ke-<?php echo $i ?></option>
                                        <?php endfor ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input style="opacity: 0; cursor: pointer;" type="file" id="file" name="file" class="form-control" accept="image/jpeg, image/pjpeg, image/png', image/x-png" required>
                                        <label for="file" id="labelfile" style="cursor: pointer;">Pilih foto presensi . . .</label>
                                    </div>
                                </div>
                                <small>Ukuran maksimal foto yang diperbolehkan adalah <strong>2 MB</strong>. (.jpg, .png)</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn bg-light-blue waves-effect">ADD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#file").change(function()
        {
            $("#labelfile").text("Foto presensi telah terpilih, silahkan click ADD!");
        });
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD/MM/YYYY',
            clearButton: true,
            weekStart: 1,
            time: false
        });
        $('#tahun, #semester').change(function(){
            var tahun=$('#tahun').val();
            var semester=$('#semester').val();
            $.ajax({
                url : "<?php echo base_url();?>Admin/get_pembina",
                method : "POST",
                data : {tahun: tahun, semester: semester},
                async : false,
                dataType : 'json',
                success: function(data)
                {
                    var html = '';
                    var i;
                    // html += '<option disabled selected style="display:none">Pilih Dosen Pembina</option>';
                    html += '<option value="-1" selected>Pilih Dosen Pembina</option>';
                    for(i = 0; i < data.length; i++)
                    {
                       html += '<option value=' + data[i].nik + '>' + data[i].nama + '</option>';
                    }
                    $('select.pembina').html(html).selectpicker('refresh');
                }
            });
        });
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });
</script>