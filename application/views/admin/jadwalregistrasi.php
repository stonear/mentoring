<?php
	foreach ($jadwalregistrasi as $j)
	{
		if ($j->setting == 'start') $start = $j->tanggal;
		if ($j->setting == 'end') $end = $j->tanggal;
	}
?>
<div class="row clearfix">
	<div class="col-md-6 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Jadwal Registrasi Mentor</h2>
			</div>
			<div class="body">
				<form autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/jadwalregistrasi2" method="post">
					<label for="start">Mulai Registrasi</label>
					<div class="form-group">
						<div class="form-line">
							<input type="text" id="start" name="start" class="datetimepicker form-control" placeholder="Please choose date & time..." value="<?php echo $start ?>">
						</div>
					</div>
					<label for="end"><i>Deadline</i> Registrasi</label>
					<div class="form-group">
						<div class="form-line">
							<input type="text" id="end" name="end" class="datetimepicker form-control" placeholder="Please choose date & time..." value="<?php echo $end ?>">
						</div>
					</div>
					<button type="submit" class="btn btn-primary m-t-15 waves-effect">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(function()
	{
		$('.datetimepicker').bootstrapMaterialDatePicker({
			format: 'DD MMMM YYYY - HH:mm',
			clearButton: true,
			weekStart: 1
		});
	})
	</script>
