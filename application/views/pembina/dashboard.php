<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Unduh Manual Pengguna</h2>
            </div>
            <div class="body">
                Bingung mau ngapain? unduh manual pengguna <a href="<?php echo base_url(); ?>asset/userguide/user_guide_pembina.pdf?" target="_blank">di sini</a>.
            </div>
        </div>
    </div>
    <?php foreach ($berita as $b): ?>
        <div class="col-xs-12">
            <div class="card">
                <div class="header">
                    <h2><?php echo $b->judul ?></h2>
                    <small><?php echo $b->tanggal ?> by <a href="#"><?php echo $b->admin ?></a></small>
                </div>
                <div class="body">
                    <?php echo $b->konten ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="col-xs-12">
        <div class="card">
            <div class="body">
                <img style="width: 100%; object-fit: contain" src="<?php echo base_url(); ?>asset/images/dashboard/dosenpembina.png" alt="Diagram"/> 
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });
</script>