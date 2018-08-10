<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>CARI KELOMPOK</h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/kelompok" method="post">
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
                                <option value="-1" selected>Pilih Kelas</option>
                                <?php foreach ($kelas as $k): ?>
                                    <option value="<?php echo $k->IDkelas ?>" <?php if ($k->IDkelas == $kelas_selected) echo 'selected' ?>>
                                        TPB-<?php echo $k->NOkelas ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block waves-effect" data-toggle="tooltip" data-placement="bottom" title="cari kelompok"><i class="material-icons">search</i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-4 text-right">
        <?php if($tahun_selected != NULL and $semester_selected != NULL): ?>
            <a href="<?php echo base_url(); ?>Admin/download_kelompok/<?php echo $tahun_selected.'/'.$semester_selected ?>" class="btn btn-primary btn-block waves-effect"><i class="material-icons">file_download</i> Download Data Seluruh Kelas (xlsx)</a>
        <?php endif ?>
    </div>
    <div class="col-xs-4 text-right">
       <!--  <?php if(!empty($kelompok)): ?>
            <a href="#" class="btn bg-light-green btn-block waves-effect"><i class="material-icons">file_download</i> Kelas Ini</a>
        <?php else: ?>
            <a href="#" class="btn btn-block" disabled><i class="material-icons">file_download</i> Kelas Ini</a>
        <?php endif; ?> -->
    </div>
    <div class="col-xs-4 text-right">
        <?php if($kelas_selected != -1 and $kelas_selected != NULL): ?>
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_kelompok" method="post">
                <input type="hidden" name="kelas_selected" value="<?php echo $kelas_selected ?>">
                <button type="submit" class="btn bg-light-blue btn-block waves-effect" data-toggle="tooltip" data-placement="left" title="tambah kelompok"><i class="material-icons">add</i> Tambah Kelompok</button>
            </form>
        <?php endif ?>
    </div>
</div>
<br>
<?php if(!empty($kelompok)): ?>
    <div class="row clearfix">
        <div class="col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>DATA KELOMPOK</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kelas</th>
                                    <th>No Kelompok</th>
                                    <th>Mentor</th>
                                    <th>Pembina</th>
                                    <th>Jadwal</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kelompok as $k): ?>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <?php
                                                if ($kelas_selected == -1)
                                                {
                                                    echo 'TPB-'.$k->kelas;
                                                }
                                                else
                                                {
                                                    foreach ($kelas as $kls)
                                                    {
                                                        if ($kls->IDkelas == $kelas_selected)
                                                        {
                                                            echo 'TPB-'.$kls->NOkelas;
                                                        }
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td>Kelompok <?php echo $k->jenis.$k->no ?></td>
                                        <td><?php echo $k->mentor ?></td>
                                        <td><?php echo $k->pembina ?></td>
                                        <td>
                                            <?php
                                            if($k->hari == 1) echo "Senin";
                                            elseif($k->hari == 2) echo "Selasa";
                                            elseif($k->hari == 3) echo "Rabu";
                                            elseif($k->hari == 4) echo "Kamis";
                                            elseif($k->hari == 5) echo "Jum'at";
                                            elseif($k->hari == 6) echo "Sabtu";
                                            elseif($k->hari == 7) echo "Ahad";
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <a href="<?php echo base_url(); ?>Admin/detail_kelompok/<?php echo $kelas_selected.'/'.$k->id ?>" class="update-kelompok-<?php echo $k->id ?> btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="detail kelompok <?php echo $k->jenis.$k->no ?>"><i class="material-icons">person</i></a>
                                            <?php if($kelas_selected != -1): ?>
                                                <a href="<?php echo base_url(); ?>Admin/update_kelompok/<?php echo $kelas_selected.'/'.$k->id ?>" class="btn bg-light-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui kelompok <?php echo $k->jenis.$k->no ?>"><i class="material-icons">edit</i></a>
                                            <?php else: ?>
                                                <a href="#" class="btn bg-light-blue btn-xs waves-effect"  data-toggle="tooltip" data-placement="top" title="untuk memperbarui kelompok <?php echo $k->jenis.$k->no ?>, silahkan pilih kelas terlebih dahulu" disabled><i class="material-icons">edit</i></a>
                                            <?php endif ?>
                                            <button type="button" class="hapus-kelompok-<?php echo $k->id ?> btn btn-danger btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="hapus kelompok <?php echo $k->jenis.$k->no ?>"><i class="material-icons">delete</i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php foreach ($kelompok as $k): ?>
        <div class="modal fade" id="hapus-kelompok-<?php echo $k->id ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-col-red">
                    <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_kelompok/<?php echo $kelas_selected.'/'.$k->id ?>" method="post">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Hapus Data Kelompok</h4>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus data kelompok <?php echo $k->no ?>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn btn-link waves-effect">HAPUS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php elseif(!empty($tahun_selected)): ?>
    <div class="alert bg-red alert-dismissible'; ?>." role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        Data Kelompok tidak ditemukan
    </div>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function()
    {
        <?php foreach ($kelompok as $k): ?>
            $('.hapus-kelompok-<?php echo $k->id ?>.btn').on('click', function ()
            {
                $('#hapus-kelompok-<?php echo $k->id ?>').modal('show');
            });
        <?php endforeach; ?>
        $('#tahun, #semester').change(function(){
            var tahun=$('#tahun').val();
            var semester=$('#semester').val();
            $.ajax({
                url : "<?php echo base_url() ?>admin/get_kelas",
                method : "POST",
                data : {tahun: tahun, semester: semester},
                async : false,
                dataType : 'json',
                success: function(data)
                {
                    var html = '';
                    var i;
                    html += '<option value="-1" selected>Pilih Kelas</option>';
                    for(i = 0; i < data.length; i++)
                    {
                        html += '<option value=' + data[i].IDkelas + '>TPB-' + data[i].NOkelas + '</option>';
                    }
                    $('select.kelas').html(html).selectpicker('refresh');
                }
            });
        });
        //Exportable table
        var t = $('.js-exportable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]
        } );
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });
</script>