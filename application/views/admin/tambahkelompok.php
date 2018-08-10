<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>TAMBAH KELOMPOK</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/tambah_kelompok2" method="post">
                    <input type="hidden" name="kelas_selected" value="<?php echo $kelas_selected ?>">
                    <div class="row clearfix">
                        <div class="col-xs-6">
                            <label for="jenis">Jenis Kelamin</label>
                            <div class="form-group">
                                <select name="jenis" id="jenis" class="form-control show-tick" required>
                                    <option disabled selected style="display:none">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <label for="no">Nomor Kelompok</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="no" id="no" class="form-control" placeholder="Masukkan nomor kelompok" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label for="mentor">Mentor</label>
                    <div class="form-group">
                        <select name="mentor" id="mentor" class="form-control show-tick" data-live-search="true" required>
                            <option disabled selected style="display:none">Pilih Mentor</option>
                            <?php foreach ($mentor as $m): ?>
                                <option value="<?php echo $m->NRPmentor ?>"><?php echo $m->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label for="pembina">Dosen Pembina</label>
                    <div class="form-group">
                        <select name="pembina" id="pembina" class="form-control show-tick" data-live-search="true" required>
                            <option disabled selected style="display:none">Pilih Dosen Pembina</option>
                            <?php foreach ($pembina as $p): ?>
                                <option value="<?php echo $p->NIKdosenpembina ?>"><?php echo $p->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label for="jadwal">Jadwal</label>
                    <div class="form-group">
                        <select name="jadwal" id="jadwal" class="form-control show-tick" required>
                            <option disabled selected style="display:none">Pilih Jadwal</option>
                            <option value="1">Senin</option>
                            <option value="2">Selasa</option>
                            <option value="3">Rabu</option>
                            <option value="4">Kamis</option>
                            <option value="5">Jum'at</option>
                            <option value="6">Sabtu</option>
                            <option value="7">Ahad</option>
                        </select>
                    </div>
                    <label for="peserta">Peserta</label>
                    <div class="form-group">
                        <select id="optgroup" name="peserta[]" id="peserta" class="ms" multiple="multiple">
                            <?php foreach ($peserta as $p): ?>
                                <option value="<?php echo $p->NRPpeserta ?>">(<?php echo $p->jeniskelamin ?>) (<?php echo $p->NRPpeserta ?>) <?php echo $p->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect"><i class="material-icons">add</i> TAMBAH</button>
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
        $('#jenis, #no, #mentor, #pembina, #jadwal').on('change', function () {
            var jenis = $('#jenis').val();
            var no = $('#no').val();
            var mentor = $('#mentor').val();
            var pembina = $('#pembina').val();
            var jadwal = $('#jadwal').val();
            $.ajax({
                url : "<?php echo base_url(); ?>Admin/check_kelas",
                method : "POST",
                data : {jenis: jenis, no: no, mentor : mentor, pembina : pembina, jadwal : jadwal},
                async : false,
                dataType : 'json',
                success: function(data)
                {
                    if (data)
                    {   
                        swal({
                            title: "Apakah anda yakin?",
                            text: "Sudah terdapat kelompok yang sama sebelumnya. Apakah anda ingin melanjutkan?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ya, lanjutkan!",
                            closeOnConfirm: false
                        }, function () {
                            swal("Ok!", "Lanjutkan pekerjaan anda", "success");
                        });
                    }
                }
            });
        });
    });
</script>