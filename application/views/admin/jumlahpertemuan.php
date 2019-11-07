<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="body">
            	<blockquote class="m-b-25">
            		<p><b>[Informasi]</b> <b>Tambah Pertemuan</b> membutuhkan waktu beberapa saat. Dimohon untuk menunggu.</p>
                </blockquote>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 text-right tambah-pertemuan">
        <button type="button" class="btn bg-light-blue waves-effect" data-toggle="tooltip" data-placement="left" title="tambah pertemuan"><i class="material-icons">add</i> Tambah Pertemuan</button>
        <br><br>
    </div>
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>JUMLAH PERTEMUAN</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tahun</th>
                                <th>Semester</th>
                                <th>Jumlah Pertemuan</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pertemuan as $p): ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $p->tahun.'/'.($p->tahun + 1) ?></td>
                                    <td><?php echo ($p->semester == 1) ? 'Gasal' : 'Genap'; ?></td>
                                    <td><?php echo $p->jumlahpertemuan ?></td>
                                    <td class="text-right">
                                        <button type="button" class="update-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?> btn bg-light-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui jumlah pertemuan"><i class="material-icons">edit</i></button>
                                        <button type="button" class="hapus-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?> btn btn-danger btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="hapus jumlah pertemuan"><i class="material-icons">delete</i></button>
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
<div class="modal fade" id="tambah-pertemuan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_pertemuan" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Tambah Data Jumlah Pertemuan</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <div class="row clearfix">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" id="tahun" name="tahun" class="form-control" placeholder="Masukkan Tahun" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right keterangan"></div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="semester" required>
                                                <option disabled selected style="display:none">Pilih Semester</option>
                                                <option value="1">Gasal</option>
                                                <option value="2">Genap</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="jumlah" class="form-control" placeholder="Masukkan Jumlah Pertemuan" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<?php foreach ($pertemuan as $p): ?>
    <div class="modal fade" id="update-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_pertemuan/<?php echo $p->tahun.'/'.$p->semester ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Update Data Jumlah Pertemuan</h4>
                    </div>
                    <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <div class="row clearfix">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" id="tahun" name="tahun" class="form-control" placeholder="Masukkan Tahun" value="<?php echo $p->tahun ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right keterangan-kelas">
                                    (Tahun ajaran <?php echo $p->tahun.'/'.($p->tahun + 1) ?>)
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="semester" required>
                                                <option value="1" <?php if($p->semester == 1) echo 'selected' ?>>Gasal</option>
                                                <option value="2" <?php if($p->semester == 2) echo 'selected' ?>>Genap</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" name="jumlah" class="form-control" placeholder="Masukkan Jumlah Pertemuan" value="<?php echo $p->jumlahpertemuan ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn bg-light-blue waves-effect">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="hapus-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-col-red">
                <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_pertemuan/<?php echo $p->tahun.'/'.$p->semester ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Hapus Data Jumlah Pertemuan</h4>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus data jumlah pertemuan tahun ajaran <?php echo $p->tahun.'/'.($p->tahun + 1) ?> semester <?php echo ($p->semester == 1) ? 'Gasal' : 'Genap'; ?>?
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
<script>
    $(function()
    {
        $('.tambah-pertemuan .btn').on('click', function ()
        {
            $('#tambah-pertemuan').modal('show');
        });
        <?php foreach ($pertemuan as $p): ?>
            $('.update-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?>.btn').on('click', function ()
            {
                $('#update-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?>').modal('show');
            });
            $('.hapus-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?>.btn').on('click', function ()
            {
                $('#hapus-pertemuan-<?php echo $p->tahun.'-'.$p->semester ?>').modal('show');
            });
        <?php endforeach; ?>
       //Exportable table
        var t = $('.js-exportable').DataTable
        ({
            // dom: 'Bfrtip',
            responsive: true,
            // ,
            // buttons:
            // [
            //     {
            //         extend: 'copy',
            //         exportOptions:
            //         {
            //             columns: ':not(:last-child)',
            //         }
            //     },
            //     {
            //         extend: 'csv',
            //         exportOptions:
            //         {
            //             columns: ':not(:last-child)',
            //         }
            //     },
            //     {
            //         extend: 'excel',
            //         exportOptions:
            //         {
            //             columns: ':not(:last-child)',
            //         }
            //     },
            //     {
            //         extend: 'print',
            //         exportOptions:
            //         {
            //             columns: ':not(:last-child)',
            //         }
            //     },
            //     {
            //         extend: 'pdf',
            //         exportOptions:
            //         {
            //             columns: ':not(:last-child)',
            //         }
            //     }
            // ]
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]
        });
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
        $('#tahun').change(function()
        {
            var tahun = $('#tahun').val();
            var tahun = parseInt(tahun);
            $('div.keterangan').html('(Tahun ajaran ' + tahun.toString() + '/' + (tahun + 1).toString() + ')');
        });
    });
</script>