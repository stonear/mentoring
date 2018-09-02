<div class="row clearfix">
    <div class="col-xs-12">
        <?php foreach($tahun as $t): ?>
            <a class="btn bg-cyan waves-effect" href="<?php echo base_url(); ?>Admin/berita_lama/<?php echo $t->tahun ?>"><?php echo $t->tahun ?></a>
        <?php endforeach ?>
        <br><br>
    </div>
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Berita Tahun <?php echo $tahun_selected ?></h2>
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
            </div>
        </div>
    </div>
</div>