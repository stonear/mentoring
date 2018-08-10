<div class="row clearfix">
	<div class="col-sm-offset-3 col-sm-6 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>
					Ganti Password
				</h2>
			</div>
			<div class="body">
				<form autocomplete="off" role="form" action="<?php echo base_url(); ?>Mentor/password2" method="post">
					<label for="password1">Password Lama</label>
					<div class="form-group">
                    	<div class="form-line">
							<input type="password" id="password1" name="password1" class="form-control" placeholder="Masukkan Password Lama" required>
						</div>
					</div>
					<label for="password2">Password Baru</label>
					<div class="form-group">
                    	<div class="form-line">
							<input type="password" id="password2" name="password2" class="form-control" placeholder="Masukkan Password Baru" required>
						</div>
					</div>
					<label for="password3">Konfirmasi Password Baru</label>
					<div class="form-group">
                    	<div class="form-line">
							<input type="password" id="password3" name="password3" class="form-control" placeholder="Masukkan Password Baru" required>
						</div>
					</div>
					<button type="submit" class="btn btn-primary m-t-15 waves-effect">GANTI</button>
				</form>
			</div>
		</div>
	</div>
</div>