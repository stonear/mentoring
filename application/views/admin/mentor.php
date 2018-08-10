<div class="row clearfix">
    <div class="col-xs-12 text-right tambah-mentor">
        <button type="button" class="btn bg-light-blue waves-effect" data-toggle="tooltip" data-placement="left" title="tambah mentor"><i class="material-icons">add</i> Tambah Mentor</button>
        <br><br>
    </div>
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>MENTOR</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mentor as $m): ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $m->NRPmentor ?></td>
                                    <td><?php echo $m->nama ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url(); ?>Admin/profil_mentor/<?php echo $m->NRPmentor ?>" class="btn bg-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="profil mentor"><i class="material-icons">search</i></a>
                                        <button type="button" class="pass-mentor-<?php echo $m->NRPmentor ?> btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="ubah kata sandi mentor"><i class="material-icons">vpn_key</i></button>
                                        <button type="button" class="hapus-mentor-<?php echo $m->NRPmentor ?> btn btn-danger btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="hapus mentor"><i class="material-icons">delete</i></button>
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
<div class="modal fade" id="tambah-mentor" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_mentor" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Tambah Mentor</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="nrp" class="form-control" placeholder="Masukkan NRP mentor" required>
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
<?php foreach ($mentor as $m): ?>
    <div class="modal fade" id="pass-mentor-<?php echo $m->NRPmentor ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_mentor/<?php echo $m->NRPmentor ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Update Password Mentor</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" name="password" class="form-control" required>
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
    <div class="modal fade" id="hapus-mentor-<?php echo $m->NRPmentor ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-col-red">
                <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_mentor/<?php echo $m->NRPmentor ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Hapus Mentor</h4>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus <?php echo $m->nama ?>?
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
        $('.tambah-mentor .btn').on('click', function ()
        {
            $('#tambah-mentor').modal('show');
        });
        <?php foreach ($mentor as $m): ?>
            $('.pass-mentor-<?php echo $m->NRPmentor ?>.btn').on('click', function ()
            {
                $('#pass-mentor-<?php echo $m->NRPmentor ?>').modal('show');
            });
            $('.hapus-mentor-<?php echo $m->NRPmentor ?>.btn').on('click', function ()
            {
                $('#hapus-mentor-<?php echo $m->NRPmentor ?>').modal('show');
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