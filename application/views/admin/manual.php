<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>MANUAL PENGGUNA</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mentor</td>
                                <td class="text-right">
                                    <a href="<?php echo base_url(); ?>asset/userguide/user_guide_mentor.pdf?" class="btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="unduh manual pengguna"><i class="material-icons">file_download</i></a>
                                    <button type="button" class="update-mentor btn bg-light-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui manual pengguna"><i class="material-icons">edit</i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dosen Pembina</td>
                                <td class="text-right">
                                    <a href="<?php echo base_url(); ?>asset/userguide/user_guide_pembina.pdf?" class="btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="unduh manual pengguna"><i class="material-icons">file_download</i></a>
                                    <button type="button" class="update-pembina btn bg-light-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui manual pengguna"><i class="material-icons">edit</i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dosen Kelas</td>
                                <td class="text-right">
                                    <a href="<?php echo base_url(); ?>asset/userguide/user_guide_dosen.pdf?" class="btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="unduh manual pengguna"><i class="material-icons">file_download</i></a>
                                    <button type="button" class="update-dosen btn bg-light-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui manual pengguna"><i class="material-icons">edit</i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Administrator</td>
                                <td class="text-right">
                                    <a href="<?php echo base_url(); ?>asset/userguide/user_guide_admin.pdf?" class="btn bg-green btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="unduh manual pengguna"><i class="material-icons">file_download</i></a>
                                    <button type="button" class="update-admin btn bg-light-blue btn-xs waves-effect" data-toggle="tooltip" data-placement="top" title="perbarui manual pengguna"><i class="material-icons">edit</i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="update-mentor" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_manual/mentor" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Update Manual Penguna Mentor</h4>
                </div>
                <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input style="opacity: 0; cursor: pointer;" type="file" id="file" name="mentor" class="form-control" accept="application/pdf" required>
                                        <label for="file" id="labelfile" style="cursor: pointer;">Pilih file . . .</label>
                                    </div>
                                </div>
                                <small>Format file yang diperbolehkan adalah <strong>pdf</strong>.</small>
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
<div class="modal fade" id="update-pembina" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_manual/pembina" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Update Manual Penguna DOsen Pembina</h4>
                </div>
                <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input style="opacity: 0; cursor: pointer;" type="file" id="file2" name="pembina" class="form-control" accept="application/pdf" required>
                                        <label for="file2" id="labelfile2" style="cursor: pointer;">Pilih file . . .</label>
                                    </div>
                                </div>
                                <small>Format file yang diperbolehkan adalah <strong>pdf</strong>.</small>
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
<div class="modal fade" id="update-dosen" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_manual/dosen" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Update Manual Penguna Dosen Kelas</h4>
                </div>
                <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input style="opacity: 0; cursor: pointer;" type="file" id="file3" name="dosen" class="form-control" accept="application/pdf" required>
                                        <label for="file3" id="labelfile3" style="cursor: pointer;">Pilih file . . .</label>
                                    </div>
                                </div>
                                <small>Format file yang diperbolehkan adalah <strong>pdf</strong>.</small>
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
<div class="modal fade" id="update-admin" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_manual/admin" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Update Manual Penguna Administrator</h4>
                </div>
                <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-xs-12">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input style="opacity: 0; cursor: pointer;" type="file" id="file4" name="admin" class="form-control" accept="application/pdf" required>
                                        <label for="file4" id="labelfile4" style="cursor: pointer;">Pilih file . . .</label>
                                    </div>
                                </div>
                                <small>Format file yang diperbolehkan adalah <strong>pdf</strong>.</small>
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
<script>
    $(function()
    {
         $('.update-mentor.btn').on('click', function ()
        {
            $('#update-mentor').modal('show');
        });
        $('.update-pembina.btn').on('click', function ()
        {
            $('#update-pembina').modal('show');
        });
        $('.update-dosen.btn').on('click', function ()
        {
            $('#update-dosen').modal('show');
        });
        $('.update-admin.btn').on('click', function ()
        {
            $('#update-admin').modal('show');
        });
        $("#file").change(function()
        {
            $("#labelfile").text("File telah terpilih, silahkan click UPDATE!");
        });
        $("#file2").change(function()
        {
            $("#labelfile2").text("File telah terpilih, silahkan click UPDATE!");
        });
        $("#file3").change(function()
        {
            $("#labelfile3").text("File telah terpilih, silahkan click UPDATE!");
        });
        $("#file4").change(function()
        {
            $("#labelfile4").text("File telah terpilih, silahkan click UPDATE!");
        });
        
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });
</script>