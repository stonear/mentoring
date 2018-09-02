<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="body">
                <blockquote class="m-b-25">
                    <!-- <p>Berita yang akan ditampilkan pada pengguna adalah 5 berita terbaru.</p> -->
                    <p>Berita yang akan ditampilkan pada pengguna adalah berita pada tahun yang sama.</p>
                </blockquote>
            </div>
        </div>
    </div>
    <div class="col-xs-12 text-right tambah-mentor">
        <a href="<?php echo base_url(); ?>Admin/tambah_berita" class="btn bg-light-blue waves-effect" data-toggle="tooltip" data-placement="left" title="tambah berita"><i class="material-icons">add</i> Tambah Berita</a>
        <br><br>
    </div>
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>BERITA</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Tanggal Post</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($berita as $b): ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $b->judul ?></td>
                                    <td><?php echo $b->admin ?></td>
                                    <td><?php echo $b->tanggal ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url(); ?>Admin/update_berita/<?php echo $b->id ?>" class="btn bg-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui berita"><i class="material-icons">edit</i></a>
                                        <button type="button" class="hapus-berita-<?php echo $b->id ?> btn btn-danger btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="hapus berita"><i class="material-icons">delete</i></button>
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
<?php foreach ($berita as $b): ?>
    <div class="modal fade" id="hapus-berita-<?php echo $b->id ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-col-red">
                <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_berita/<?php echo $b->id ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Hapus Berita</h4>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus <?php echo $b->judul ?>?
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
        <?php foreach ($berita as $b): ?>
            $('.hapus-berita-<?php echo $b->id ?>.btn').on('click', function ()
            {
                $('#hapus-berita-<?php echo $b->id ?>').modal('show');
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