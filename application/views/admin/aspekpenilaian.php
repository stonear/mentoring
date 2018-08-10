<div class="row clearfix">
    <div class="col-xs-12 text-right tambah-masternilai">
        <button type="button" class="btn bg-light-blue waves-effect" data-toggle="tooltip" data-placement="left" title="tambah aspek penilaian"><i class="material-icons">add</i> Tambah Aspek Penilaian</button>
        <br><br>
    </div>
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>ASPEK PENILAIAN</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Aspek</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($masternilai as $m): ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $m->namanilai ?></td>
                                    <td class="text-right">
                                        <button type="button" class="update-masternilai-<?php echo $m->IDnilai ?> btn bg-light-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui aspek penilaian"><i class="material-icons">edit</i></button>
                                        <button type="button" class="hapus-masternilai-<?php echo $m->IDnilai ?> btn btn-danger btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="hapus aspek penilaian"><i class="material-icons">delete</i></button>
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
<div class="modal fade" id="tambah-masternilai" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_aspek" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Tambah Aspek Penilaian</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="namanilai" class="form-control" placeholder="Masukkan Aspek Penilaian" required>
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
<?php foreach ($masternilai as $m): ?>
    <div class="modal fade" id="update-masternilai-<?php echo $m->IDnilai ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_aspek/<?php echo $m->IDnilai ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Update Aspek Penilaian</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="namanilai" class="form-control" placeholder="Masukkan Aspek Penilaian" value="<?php echo $m->namanilai ?>" required>
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
    <div class="modal fade" id="hapus-masternilai-<?php echo $m->IDnilai ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-col-red">
                <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_aspek/<?php echo $m->IDnilai ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Hapus Aspek Penilaian</h4>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus aspek <?php echo $m->namanilai ?>?
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
        $('.tambah-masternilai .btn').on('click', function ()
        {
            $('#tambah-masternilai').modal('show');
        });
        <?php foreach ($masternilai as $m): ?>
            $('.update-masternilai-<?php echo $m->IDnilai ?>.btn').on('click', function ()
            {
                $('#update-masternilai-<?php echo $m->IDnilai ?>').modal('show');
            });
            $('.hapus-masternilai-<?php echo $m->IDnilai ?>.btn').on('click', function ()
            {
                $('#hapus-masternilai-<?php echo $m->IDnilai ?>').modal('show');
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
    });
</script>