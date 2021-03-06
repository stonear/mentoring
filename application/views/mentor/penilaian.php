<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>CARI PENILAIAN</h2>
            </div>
            <div class="body">
                <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Mentor/penilaian" method="post">
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
                            <select name="kelompok" id="kelompok" class="kelompok form-control show-tick" required>
                                <option disabled selected style="display:none">Pilih Kelompok</option>
                                <?php foreach ($kelompok as $k): ?>
                                    <option value="<?php echo $k->id ?>" <?php if ($k->id == $kelompok_selected) echo 'selected' ?>>
                                        Kelompok <?php echo $k->no ?> TPB-<?php echo $k->kelas ?>
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
</div>
<?php if(!empty($peserta)): ?>
	<div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="body">
                    <form autocomplete="off" role="form" action="<?php echo base_url(); ?>Mentor/update_penilaian/<?php echo $tahun_selected.'/'.$semester_selected.'/'.$kelompok_selected ?>" method="post">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table table-bordered table-striped table-condensed table-hover text-nowrap" id="table">
                                <thead>
                                    <tr class="bg-cyan">
                                        <th>No.</th>
                                        <th>NRP</th>
                                        <th>Nama</th>
                                        <?php
                                        foreach($aspekpenilaian as $a)
                                        {
                                        	if (strlen($a->namanilai) < 20)
                                        	{
                                        		$space = '';
                                        		for ($i = 0; $i < 20 - strlen($a->namanilai); $i++)
                                        		{
                                        			$space = $space.'&nbsp;';
                                        		}
                                        		echo '<th>'.$a->namanilai.$space.'</th>';
                                        	}
                                        	else
                                        	{
                                        		echo '<th>'.$a->namanilai.'</th>';
                                        	}
                                        }
                                        ?>
                                        <th>Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1 ?>
                                    <?php foreach ($peserta as $p): ?>
                                        <tr>
                                            <td style="vertical-align: middle;"><?php echo $no++ ?></td>
                                            <td style="vertical-align: middle;"><?php echo $p->NRPpeserta ?></td>
                                            <td style="vertical-align: middle;"><?php echo $p->nama ?></td>
                                            <?php $total = 0 ?>
                                            <?php foreach($aspekpenilaian as $a): ?>
                                            	<td style="vertical-align: middle;">
                                            		<input
                                            		type = "number"
                                            		name = "<?php echo $p->NRPpeserta.'-'.$a->IDnilai ?>"
                                            		id = "<?php echo $p->NRPpeserta.'-'.$a->IDnilai ?>"
                                            		class="form-control"
                                            		min = "0"
                                            		max = "100"
                                            		<?php
                                            		foreach($nilai as $n)
                                            		{
                                            			if ($n->nrp == $p->NRPpeserta and $n->IDnilai == $a->IDnilai)
                                            			{
                                            				echo 'value="'.$n->nilai.'"';
                                            				$total += $n->nilai;
                                            			}
                                            		}
                                            		?>
                                            		/>
                                            	</td>
                                            <?php endforeach ?>
                                            <td style="vertical-align: middle;">
                                            	<?php echo $total/count($aspekpenilaian) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-block waves-effect">SIMPAN</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h2>Download Penilaian (xlsx)</h2>
                </div>
                <div class="body">
                    <a href="<?php echo base_url(); ?>Mentor/download_penilaian/<?php echo $kelompok_selected ?>" class="btn btn-primary btn-block waves-effect"><i class="material-icons">file_download</i> UNDUH</a>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tahun, #semester').change(function(){
            var tahun=$('#tahun').val();
            var semester=$('#semester').val();
            $.ajax({
                url : "<?php echo base_url();?>mentor/get_kelompok",
                method : "POST",
                data : {tahun: tahun, semester: semester},
                async : false,
                dataType : 'json',
                success: function(data)
                {
                    var html = '';
                    var i;
                    html += '<option disabled selected style="display:none">Pilih Kelompok</option>';
                    for(i = 0; i < data.length; i++)
                    {
                       html += '<option value=' + data[i].id + '>Kelompok ' + data[i].no + ' TPB-' + data[i].kelas + '</option>';
                   }
                   $('select.kelompok').html(html).selectpicker('refresh');
               }
           });
        });
        //tooltip
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    });
</script>