<div class="row clearfix">
	<div class="col-sm-6 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Terdaftar Sebagai Mentor pada</h2>
			</div>
			<div class="body">
				<div class="list-group">
					<?php foreach($smtmentor as $sm): ?>
						<div class="list-group-item">
							<?php echo $sm->tahun.'/'.($sm->tahun + 1).' Semester '; ?>
							<?php if($sm->semester == 1) echo 'Gasal'; ?>
							<?php if($sm->semester == 2) echo 'Genap'; ?>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Registrasi Ulang</h2>
			</div>
			<div class="body">
				<form autocomplete="off" action="<?php echo base_url(); ?>Mentor/reg2" method="POST">
					<div class="form-group form-float">
	                    <div class="form-line">
	                        <input type="number" class="form-control" id="tahun" name="tahun" value="<?php echo $year; ?>" required>
	                        <label class="form-label">Registrasi Ulang Mentor untuk Tahun Ajaran ...</label>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="form-line">
	                        <div class="keterangan"></div>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="form-line">
	                        <select class="form-control show-tick" name="semester" value="1" required>
	                            <option disabled selected style="display:none" >Registrasi Ulang Mentor untuk Semester ...</option>
								<?php
								if($semester==1){?>
									<option value="<?php echo $semester; ?>" selected>Semester Gasal</option>
	                            	<option value="<?php echo $semester; ?>">Semester Genap</option>
								<?php } else {?>
									<option value="<?php echo $semester; ?>">Semester Gasal</option>
	                            	<option value="<?php echo $semester; ?>" selected>Semester Genap</option>
								<?php }
								?>
	                        </select>
	                    </div>
	                </div>
	                <button type="submit" class="btn btn-primary waves-effect">OK!</button>
	            </form>
			</div>
		</div>
	</div>
</div>
<script>
    $(function () {
        $('#tahun').change(function()
        {
            var tahun = $('#tahun').val();
            var tahun = parseInt(tahun);
            $('div.keterangan').html('<small>(Tahun ajaran ' + tahun.toString() + '/' + (tahun + 1).toString() + ')</small>');
        });
    });
</script>