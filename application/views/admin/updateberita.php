<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>EDIT BERITA</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_berita2/" method="post">
                    <input type="hidden" name="id" value="<?php echo $berita[0]->id ?>">
                    <label for="judul">Judul</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="judul" class="form-control" placeholder="Masukkan judul" value="<?php echo $berita[0]->judul ?>" required>
                        </div>
                    </div>
                    <textarea id="tinymce" name="konten">
                        <?php echo $berita[0]->konten ?>
                        </textarea>
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"><i class="material-icons">edit</i> EDIT</button>
                    <a href="<?php echo base_url(); ?>Admin/berita" class="btn bg-red m-t-15 waves-effect"><i class="material-icons">cancel</i> CANCEL</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>asset/plugins/tinymce/tinymce.js"></script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        //TinyMCE
        tinymce.init({
            selector: "textarea#tinymce",
            theme: "modern",
            height: 300,
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            images_upload_url: "<?php echo base_url("Admin/tinymce_upload")?>",
            file_picker_types: 'image', 
            paste_data_images:true,
            relative_urls: false,
            remove_script_host: false,
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        var id = 'post-image-' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var blobInfo = blobCache.create(id, file, reader.result);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                };
                input.click();
            }
        });
        tinymce.suffix = ".min";
        tinyMCE.baseURL = '<?php echo base_url(); ?>asset/plugins/tinymce';
    });
</script>