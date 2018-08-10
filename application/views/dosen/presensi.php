<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>CARI PRESENSI</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Dosen/presensi" method="post">
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
                        <div class="col-md-3">
                            <select name="kelas" id="kelas" class="kelas form-control show-tick" required>
                                <option disabled selected style="display:none">Pilih Kelas</option>
                                <?php foreach ($kelas as $k): ?>
                                    <option value="<?php echo $k->IDkelas ?>" <?php if ($k->IDkelas == $kelas_selected) echo 'selected' ?>>
                                        TPB-<?php echo $k->NOkelas ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block waves-effect" data-toggle="tooltip" data-placement="bottom" title="cari presensi"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if(!empty($peserta)): ?>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="body">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table table-bordered table-striped table-condensed table-hover text-nowrap dataTable js-exportable">
                                <thead>
                                    <tr class="bg-cyan">
                                        <th>No.</th>
                                        <th>NRP</th>
                                        <th>Nama</th>
                                        <?php for ($i = 1; $i <= $jumlahpertemuan; $i++)
                                        echo '<th>'.$i.'</th>';
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1 ?>
                                    <?php foreach ($peserta as $p): ?>
                                        <tr>
                                            <td style="vertical-align: middle;"><?php echo $no++ ?></td>
                                            <td style="vertical-align: middle;"><?php echo $p->NRPpeserta ?></td>
                                            <td style="vertical-align: middle;"><?php echo $p->nama ?></td>
                                            <?php for ($i = 1; $i <= $jumlahpertemuan; $i++): ?>
                                                <td style="vertical-align: middle;">
                                                    <?php $present = false; ?>
                                                    <?php foreach ($presensi as $a): ?>
                                                    <?php if ($a->nrp == $p->NRPpeserta and $a->mingguke == $i): ?>
                                                    <?php if ($a->status == 1): ?>
                                                        <i class="material-icons">done</i>
                                                        <?php $present = true; ?>
                                                    <?php elseif ($a->status == 2): ?>
                                                        <b>S</b>
                                                        <?php $present = true; ?>
                                                    <?php elseif ($a->status == 3): ?>
                                                        <b>I</b>
                                                        <?php $present = true; ?>
                                                    <?php elseif ($a->status == 4): ?>
                                                        <b>A</b>
                                                        <?php $present = true; ?>
                                                    <?php endif ?>
                                                    <?php endif ?>
                                                    <?php endforeach ?>
                                                    <?php if ($present == false): ?>
                                                        <b>---</b>
                                                    <?php endif ?>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h2>Rekapitulasi Presensi (xlsx)</h2>
                </div>
                <div class="body">
                    <a href="<?php echo base_url(); ?>Dosen/download_presensi/<?php echo $kelas_selected ?>" class="btn btn-primary btn-block waves-effect"><i class="material-icons">file_download</i> UNDUH</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
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
                url : "<?php echo base_url(); ?>Dosen/get_kelas",
                method : "POST",
                data : {tahun: tahun, semester: semester},
                async : false,
                dataType : 'json',
                success: function(data)
                {
                    var html = '';
                    var i;
                    html += '<option disabled selected style="display:none">Pilih Kelas</option>';
                    for(i = 0; i < data.length; i++)
                    {
                       html += '<option value=' + data[i].IDkelas + '>TPB-' + data[i].NOkelas + '</option>';
                    }
                    $('select.kelas').html(html).selectpicker('refresh');
                }
            });
        });
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
        //Exportable table
        var t = $('.js-exportable').DataTable
        ({
            responsive: true,
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]
        });
    });
</script>