<div class="row clearfix">
    <div class="col-md-6 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>TEMPLATE CV MENTOR</h2>
            </div>
            <div class="body">
                <div class="list-group">
                    <a href="<?php echo base_url(); ?>asset/registration/cv.docx" class="btn btn-default btn-block waves-effect waves-float">
                        Unduh template CV mentor
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-md-6 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>UPDATE TEMPLATE CV MENTOR</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/cvmentor2" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="form-line">
                            <input style="opacity: 0; cursor: pointer;" type="file" id="file" name="cv" class="form-control" accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                            <label for="file" id="labelfile" style="cursor: pointer;">Pilih file . . .</label>
                        </div>
                    </div>
                    <small>Format file yang diperbolehkan adalah <strong>doc/docx</strong>.</small><br><br>
                    <button type="submit" class="btn bg-light-blue waves-effect">UPDATE</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function()
    {
        $("#file").change(function()
        {
            $("#labelfile").text("File telah terpilih, silahkan click UPDATE!");
        });
    });
</script>