<div class="row clearfix">
    <div class="col-xs-12">
        <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/kelompok" method="post">
            <input type="hidden" name="tahun" value="<?php echo $tahun_selected ?>">
            <input type="hidden" name="semester" value="<?php echo $semester_selected ?>">
            <input type="hidden" name="kelas" value="<?php echo $kelas_selected ?>">
            <button type="submit" class="btn btn-primary waves-effect"><i class="material-icons">arrow_back</i></button>
        </form>
        <br><br>
    </div>
</div>
<?php if(!empty($kelompok)): ?>
<div class="row clearfix">
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    LIST PESERTA Kelompok <?php echo $kelompok[0]->jeniskelamin.$kelompok[0]->NOkelompok ?>
                </h2>
            </div>
            <div class="body">
                <ul class="list-group">
                    <?php foreach ($peserta as $p): ?>
                        <li class="list-group-item"><?php echo $p->NRPpeserta.' - '.$p->nama ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>