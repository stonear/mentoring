<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>EDIT KELOMPOK</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/update_kelompok2/<?php echo $kelas_selected.'/'.$kelompok[0]->IDkelompok ?>" method="post">
                    <input type="hidden" name="kelas_selected" value="<?php echo $kelompok[0]->IDkelompok ?>">
                    <div class="row clearfix">
                        <div class="col-xs-6">
                            <label for="jenis">Jenis Kelamin</label>
                            <div class="form-group">
                                <select name="jenis" id="jenis" class="form-control show-tick" required>
                                    <option disabled selected style="display:none">Pilih Jenis Kelamin</option>
                                    <option value="L" <?php if ($kelompok[0]->jeniskelamin == 'L') echo 'selected' ?>>Laki-Laki</option>
                                    <option value="P" <?php if ($kelompok[0]->jeniskelamin == 'P') echo 'selected' ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <label for="no">Nomor Kelompok</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="no" id="no" class="form-control" placeholder="Masukkan nomor kelompok" value="<?php echo $kelompok[0]->NOkelompok ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label for="mentor">Mentor</label>
                    <div class="form-group">
                        <select name="mentor" id="mentor" class="form-control show-tick" data-live-search="true" required>
                            <option disabled selected style="display:none">Pilih Mentor</option>
                            <?php foreach ($mentor as $m): ?>
                                <option value="<?php echo $m->NRPmentor ?>" <?php if ($m->NRPmentor == $kelompok[0]->NRPmentor) echo 'selected' ?>>
                                    <?php echo $m->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label for="pembina">Dosen Pembina</label>
                    <div class="form-group">
                        <select name="pembina" id="pembina" class="form-control show-tick" data-live-search="true" required>
                            <option disabled selected style="display:none">Pilih Dosen Pembina</option>
                            <?php foreach ($pembina as $p): ?>
                                <option value="<?php echo $p->NIKdosenpembina ?>" <?php if ($p->NIKdosenpembina == $kelompok[0]->NIKdosenpembina) echo 'selected' ?>>
                                    <?php echo $p->nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label for="jadwal">Jadwal</label>
                    <div class="form-group">
                        <select name="jadwal" id="jadwal" class="form-control show-tick" required>
                            <option disabled selected style="display:none">Pilih Jadwal</option>
                            <option value="1" <?php if (1 == $kelompok[0]->hari) echo 'selected' ?>>Senin</option>
                            <option value="2" <?php if (2 == $kelompok[0]->hari) echo 'selected' ?>>Selasa</option>
                            <option value="3" <?php if (3 == $kelompok[0]->hari) echo 'selected' ?>>Rabu</option>
                            <option value="4" <?php if (4 == $kelompok[0]->hari) echo 'selected' ?>>Kamis</option>
                            <option value="5" <?php if (5 == $kelompok[0]->hari) echo 'selected' ?>>Jum'at</option>
                            <option value="6" <?php if (6 == $kelompok[0]->hari) echo 'selected' ?>>Sabtu</option>
                            <option value="7" <?php if (7 == $kelompok[0]->hari) echo 'selected' ?>>Ahad</option>
                        </select>
                    </div>
                    <label for="peserta">Peserta</label>
                    <div class="form-group">
                        <select id="optgroup" name="peserta[]" id="peserta" class="ms" multiple="multiple">
                            <?php foreach ($peserta_selected as $p): ?>
                                <option value="<?php echo $p->NRPpeserta ?>" selected>(<?php echo $p->jeniskelamin ?>) (<?php echo $p->NRPpeserta ?>) <?php echo $p->nama ?></option>
                            <?php endforeach; ?>
                            <?php foreach ($peserta as $p): ?>
                                <option value="<?php echo $p->NRPpeserta ?>">(<?php echo $p->jeniskelamin ?>) (<?php echo $p->NRPpeserta ?>) <?php echo $p->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"><i class="material-icons">edit</i> EDIT</button>
                    <a href="<?php echo base_url(); ?>Admin/cancel_kelompok/<?php echo $kelas_selected ?>" class="btn bg-red m-t-15 waves-effect"><i class="material-icons">cancel</i> CANCEL</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>asset/plugins/jquery.quicksearch.js"></script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#optgroup').multiSelect({
            selectableHeader: "<input type='text' class='form-control' placeholder='Cari mahasiswa' autocomplete='off'>",
            selectionHeader: "<input type='text' class='form-control' placeholder='Cari mahasiswa' autocomplete='off'>",
            afterInit: function(ms){
                var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                  if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
            });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                  if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
            });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    });
</script>