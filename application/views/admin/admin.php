<div class="row clearfix">
    <div class="col-xs-12 text-right tambah-admin">
        <button type="button" class="btn bg-light-blue waves-effect" data-toggle="tooltip" data-placement="left" title="tambah administrator"><i class="material-icons">add</i> Tambah Administrator</button>
        <br><br>
    </div>
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Administrator</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Username</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admin as $a): ?>
                                
                                <tr>
                                    <td></td>
                                    <td><?php echo $a->username ?></td>
                                    <td class="text-right">
                                        <button type="button" class="pass-admin-<?php echo $a->username ?> btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="ubah kata sandi administrator"><i class="material-icons">vpn_key</i></button>
                                        <button type="button" class="hapus-admin-<?php echo $a->username ?> btn btn-danger btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="hapus administrator"><i class="material-icons">delete</i></button>
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
<div class="modal fade" id="tambah-admin" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_admin" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Tambah Administrator</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
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
<?php foreach ($admin as $a): ?>
    <div class="modal fade" id="pass-admin-<?php echo $a->username ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_admin/<?php echo $a->username ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Update Password Administrator</h4>
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
    <div class="modal fade" id="hapus-admin-<?php echo $a->username ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-col-red">
                <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_admin/<?php echo $a->username ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Hapus Administrator</h4>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus <?php echo $a->username ?>?
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
    $(document).ready(function()
    {
        $('.tambah-admin .btn').on('click', function ()
        {
            $('#tambah-admin').modal('show');
        });
        <?php foreach ($admin as $a): ?>
            $('.pass-admin-<?php echo $a->username ?>.btn').on('click', function ()
            {
                $('#pass-admin-<?php echo $a->username ?>').modal('show');
            });
            $('.hapus-admin-<?php echo $a->username ?>.btn').on('click', function ()
            {
                $('#hapus-admin-<?php echo $a->username ?>').modal('show');
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