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
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Berita</h2>
            </div>
            <div class="body">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php foreach ($berita as $b): ?>
                        <div class="panel panel-col-cyan">
                            <div class="panel-heading" role="tab" id="heading<?php echo $b->id ?>">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $b->id ?>" aria-expanded="false" aria-controls="collapse<?php echo $b->id ?>">
                                        <?php echo $b->judul ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse<?php echo $b->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $b->id ?>">
                                <div class="panel-body">
                                    <small><?php echo $b->tanggal ?> by <a href="#"><?php echo $b->admin ?></a></small>
                                    <div class="well">
                                        <?php echo $b->konten ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="align-right">
                    <a class="btn bg-cyan waves-effect" href="<?php echo base_url(); ?>Pembina/berita_lama/<?php echo date('Y') ?>">Berita Lama</a>
                </div>
            </div>
        </div>
    </div>
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