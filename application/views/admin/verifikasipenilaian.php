<?php
    function cariKelas($list, $kode) {
        foreach ($list as $key) {
            if ($key->IDkelas == $kode) {
                return $key->NOkelas;
            }
        }
    }
?>
<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>VERIFIKASI PENILAIAN</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/verifikasipenilaian" method="post">
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
                            <button type="submit" class="btn btn-primary btn-block waves-effect" data-toggle="tooltip" data-placement="bottom" title="cari penilaian"><i class="material-icons">search</i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-xs-12">
        <div class="body">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/verifikasipenilaian" method="post">
                <div class="row clearfix">
                    <?php if ($kelas_selected != null): ?>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Upload Penilaian (xlsx)</h2>
                                </div>
                                <div class="body">
                                    <button type="button" class="btn btn-primary btn-block waves-effect" data-toggle="modal" data-target="#tambah-file"><i class="material-icons">file_upload</i> UNGGAH</button>
                                </div>
                            </div>
                        </div>
                    <?php endif ?> 
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="tambah-file" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_fileverifikasipenilaian/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$kelas_selected ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Tambah File Penilaian</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div>
                                    <label  style="cursor: pointer;">Tgl Upload</label>
                                    <input id="tglupload" name="tglupload" class="form-control" value="<?php echo date("Y-m-d H:m:s") ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label  style="cursor: pointer;">Keterangan</label>                                    
                                </div>
                                <textarea name="keterangan" id="keterangan" required></textarea>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                    <input style="opacity: 0; cursor: pointer;" type="file" id="file" name="file" class="form-control" accept="file/xlsx" required>
                                    <label for="file" id="labelfile" style="cursor: pointer;">Pilih file penilaian . . .</label>
                                </div>
                            </div>
                            <small>Jenis file yang diperbolehkan adalah <strong>(.xlsx)</strong>.</small>
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
<?php if ($tahun_selected != null && $semester_selected != null): ?>
<?php if(!empty($filenilai)): ?>
<div class="row clearfix">
        <div class="col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>DATA PENILAIAN</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kelas</th>
                                    <th>Link File</th>
                                    <th>Tgl Upload</th>
                                    <th>Keterangan</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1 ?>
                                <?php foreach ($filenilai as $f): ?>
                                    <tr>                                                                                
                                        <td style="vertical-align: middle;"><?php echo $no++ ?></td>                                        
                                        <td style="vertical-align: middle;">TPB-<?php echo cariKelas($kelas, $f->IDkelas);?></td>                                         
                                        <td style="vertical-align: middle;">
                                            <?php $name = explode("/", $f->linknilai) ?>
                                            <a href="<?php echo $f->linknilai ?>" target="_blank">
                                               <?php echo end($name) ?>
                                            </a>
                                        </td>
                                        <td style="vertical-align: middle;"><?php echo $f->tglupload ?></td>
                                        <td style="vertical-align: middle;"><?php echo $f->keterangan ?></td>
                                        <td class="text-right">   
                                            <button type="button" class="btn bg-light-blue btn-xs waves-effect" data-toggle="modal" data-target="#edit-file" title="edit penilaian <?php echo $f->tahun ?>-<?php echo $f->semester ?>-<?php echo $f->IDkelas ?>"><i class="material-icons">edit</i></button>
                                            <button type="button" class="btn btn-danger btn-xs waves-effect" data-toggle="modal" data-target="#hapus-penilaian-<?php echo $f->tahun ?>-<?php echo $f->semester ?>-<?php echo $f->IDkelas ?>" data-placement="top" title="hapus penilaian <?php echo $f->tahun ?>-<?php echo $f->semester ?>-<?php echo $f->IDkelas ?>"><i class="material-icons">delete</i></button>
                                        </td>
                                    </tr>                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-file" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_fileverifikasipenilaian/<?php echo $f->tahun.'/'.$f->semester.'/'.$f->IDkelas ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Edit File Penilaian</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div>
                                        <label  style="cursor: pointer;">Tahun</label>
                                        <input id="tahun" name="tahun" class="form-control" value="<?php echo $f->tahun ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label  style="cursor: pointer;">Semester</label>
                                        <input id="semester" name="semester" class="form-control" 
                                        value="<?php if ($f->semester == 1) echo 'Gasal'; else echo 'Genap' ; ?>" 
                                        readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label  style="cursor: pointer;">Kelas</label>
                                        <input id="semester" name="semester" class="form-control" value="TPB-<?php echo cariKelas($kelas, $f->IDkelas);?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div>
                                        <label  style="cursor: pointer;">Tgl Upload</label>
                                        <input id="tglupload" name="tglupload" class="form-control" value="<?php echo date("Y-m-d H:m:s") ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label  style="cursor: pointer;">Keterangan</label>                                    
                                    </div>
                                    <textarea name="keterangan" id="keterangan" required><?php echo $f->keterangan ?></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input style="opacity: 0; cursor: pointer;" type="file" id="file1" name="file" class="form-control" accept="file/xlsx" required>
                                        <label for="file1" id="labelfile1" style="cursor: pointer;">Pilih file penilaian terbaru. . .</label>
                                    </div>
                                </div>
                                <small>Jenis file yang diperbolehkan adalah <strong>(.xlsx)</strong>.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn bg-light-blue waves-effect">EDIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="hapus-penilaian-<?php echo $f->tahun ?>-<?php echo $f->semester ?>-<?php echo $f->IDkelas ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-col-red">
                    <form autocomplete="off" class="form-horizontal" role="form" action="<?php echo base_url(); ?>Admin/hapus_fileverifikasipenilaian/<?php echo $f->tahun.'/'.$f->semester.'/'.$f->IDkelas ?>" method="post">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Hapus Data Penilaian</h4>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus data penilaian Tahun <?php echo $f->tahun ?>, Semester <?php if ($f->semester == 1) echo 'Gasal'; else echo 'Genap' ; ?>, Kelas TPB-<?php echo cariKelas($kelas, $f->IDkelas);?> ?
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
<?php endif ?>
<?php endif ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tahun, #semester').change(function(){
            var tahun=$('#tahun').val();
            var semester=$('#semester').val();
            $.ajax({
                url : "<?php echo base_url(); ?>Admin/get_kelas",
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
        $("#file").change(function()
        {
            $("#labelfile").text("File penilaian telah terpilih, silahkan click ADD!");
        });
        $("#file1").change(function()
        {
            $("#labelfile1").text("File penilaian telah diperbaharui, silahkan click ADD!");
        });
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY - HH:mm',
            clearButton: true,
            weekStart: 1
        });
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });
</script>