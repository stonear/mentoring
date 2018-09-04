<div class="row clearfix">
    <div class="col-xs-8">
            <a href="<?php echo base_url(); ?>Admin/download_pembina" class="btn bg-light-blue waves-effect" data-toggle="tooltip" data-placement="right" title="download data pembina"><i class="material-icons">file_download</i> Download Data Pembina (xlsx)</a>
        </div>
    <div class="col-xs-4 text-right tambah-pembina">
        <button type="button" class="btn bg-light-blue waves-effect" data-toggle="tooltip" data-placement="left" title="tambah dosen pembina"><i class="material-icons">add</i> Tambah Dosen Pembina</button>
        <br><br>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Dosen Pembina</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pembina as $p): ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $p->NIKdosenpembina ?></td>
                                    <td><?php echo $p->nama ?></td>
                                    <td class="text-right">
                                        <button type="button" class="pass-pembina-<?php echo $p->NIKdosenpembina ?> btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="ubah kata sandi dosen pembina"><i class="material-icons">vpn_key</i></button>
                                        <button type="button" class="hapus-pembina-<?php echo $p->NIKdosenpembina ?> btn btn-danger btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="hapus dosen pembina"><i class="material-icons">delete</i></button>
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
<div class="modal fade" id="tambah-pembina" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_pembina" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Tambah Dosen Pembina</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <select name="nik" id="nik" class="nik form-control show-tick" data-live-search="true" title="Masukkan minimal 5 karakter pada kotak pencarian..." required>
                                <!-- <option disabled selected style="display:none">Pilih Dosen</option> -->
                            </select>
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
<?php foreach ($pembina as $p): ?>
    <div class="modal fade" id="pass-pembina-<?php echo $p->NIKdosenpembina ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_pembina/<?php echo $p->NIKdosenpembina ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Update Password Dosen Pembina</h4>
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
    <div class="modal fade" id="hapus-pembina-<?php echo $p->NIKdosenpembina ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-col-red">
                <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_pembina/<?php echo $p->NIKdosenpembina ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Hapus Dosen Pembina</h4>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus <?php echo $p->nama ?>?
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
        $('.tambah-pembina .btn').on('click', function ()
        {
            $('#tambah-pembina').modal('show');
        });
        <?php foreach ($pembina as $p): ?>
            $('.pass-pembina-<?php echo $p->NIKdosenpembina ?>.btn').on('click', function ()
            {
                $('#pass-pembina-<?php echo $p->NIKdosenpembina ?>').modal('show');
            });
            $('.hapus-pembina-<?php echo $p->NIKdosenpembina ?>.btn').on('click', function ()
            {
                $('#hapus-pembina-<?php echo $p->NIKdosenpembina ?>').modal('show');
            });
        <?php endforeach; ?>
        //revisi
        $('.bs-searchbox > .form-control').on("change paste keyup", function() {
            var nama = $(this).val();
            if (nama.length >= 5)
            {
                $.ajax({
                    url : "<?php echo base_url(); ?>Admin/get_dosen",
                    method : "POST",
                    data : {nama: nama},
                    async : false,
                    dataType : 'json',
                    success: function(data)
                    {
                        var html = '';
                        var i;
                        html += '<option disabled selected style="display:none">Pilih Dosen</option>';
                        for(i = 0; i < data.length; i++)
                        {
                           html += '<option value=' + data[i].nip + '>' + data[i].namaLengkap + '</option>';
                       }
                       $('select.nik').html(html).selectpicker('refresh');
                   }
               });
            }
        });
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