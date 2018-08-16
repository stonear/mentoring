<div class="row clearfix">
	<div class="col-sm-3 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Foto Profil</h2>
				<ul class="header-dropdown m-r--5">
					<li class="dropdown">
						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="material-icons">more_vert</i>
						</a>
						<ul class="dropdown-menu pull-right">
							<li><a data-toggle="modal" data-target="#foto">Update</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="body">
				<?php $path = FCPATH."photo/".$mentor[0]->NRPmentor.".jpg"; ?>
				<?php if (file_exists($path)) : ?>
					<img class="img-responsive" src="<?php echo base_url().'photo/'.$mentor[0]->NRPmentor.'.jpg' ?>" />
				<?php else : ?>
					<img class="img-responsive" src="<?php echo base_url(); ?>asset/images/user.png" />
				<?php endif; ?>
			</div>
		</div>
		<div class="card">
			<div class="header">
				<h2>CV</h2>
				<ul class="header-dropdown m-r--5">
					<li class="dropdown">
						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="material-icons">more_vert</i>
						</a>
						<ul class="dropdown-menu pull-right">
							<li><a data-toggle="modal" data-target="#cv">Update</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="body">
				<div class="list-group">
					<div class="list-group-item">
						<?php $name = explode("/", $mentor[0]->cv) ?>
						<a href="<?php echo $mentor[0]->cv ?>" target="_blank">
                            <?php echo end($name) ?>
                        </a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-9 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Biodata</h2>
			</div>
			<div class="body">
				<form class="form-horizontal" autocomplete="off" role="form" action="<?php echo base_url(); ?>Mentor/update_profil" method="post">
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="nrp">NRP<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<div class="form-line">
									<input type="number" id="nrp" name="nrp" class="form-control" placeholder="masukkan nrp" value="<?php echo $mentor[0]->NRPmentor ?>" disabled required>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="nama">Nama Lengkap<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<div class="form-line">
									<input type="text" id="nama" name="nama" class="form-control" placeholder="masukkan nama lengkap" value="<?php echo $mentor[0]->nama ?>" disabled required>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="jenis_kelamin">Jenis Kelamin<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
									<option disabled value style="display:none">pilih jenis kelamin</option>
									<option value="L" <?php if ('L' == $mentor[0]->jenis_kelamin) echo  "selected" ?>>Laki-laki</option>
									<option value="P" <?php if ('P' == $mentor[0]->jenis_kelamin) echo  "selected" ?>>Perempuan</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="no">No. Telp<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<div class="form-line">
									<input type="text" id="no" name="no" class="form-control" placeholder="masukkan no. telp" pattern="[0-9]+" value="<?php echo $mentor[0]->no_telp ?>" required>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="email">e-Mail<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<div class="form-line">
									<input type="email" id="email" name="email" class="form-control" placeholder="masukkan email" value="<?php echo $mentor[0]->email ?>" required>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="alamat">Alamat<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<div class="form-line">
									<textarea rows="3" id="alamat" name="alamat" class="form-control no-resize auto-growth" placeholder="Masukkan alamat lengkap" required><?php echo $mentor[0]->alamat ?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="pernah">Pernah Menjadi Mentor Pada Semester<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<select id="pernah" name="pernah" class="form-control" required>
									<option value="0" <?php if (0 == $mentor[0]->pernah_jadi_mentor) echo  "selected" ?>>Tidak Pernah</option>
									<?php for($i = 1; $i <= 6; $i++): ?>
										<option value="<?php echo $i ?>" <?php if ($i == $mentor[0]->pernah_jadi_mentor) echo  "selected" ?>>Semester <?php echo $i ?></option>
									<?php endfor ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
							<label for="nilai">Nilai Mata Kuliah Agama<span class="col-red"> *</span></label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
							<div class="form-group">
								<select id="nilai" name="nilai" class="form-control" required>
									<option disabled selected style="display:none">Pilih Nilai</option>
									<option value="A" <?php if ('A' == $mentor[0]->nilai) echo  "selected" ?>>A</option>
									<option value="AB" <?php if ('AB' == $mentor[0]->nilai) echo  "selected" ?>>AB</option>
									<option value="B" <?php if ('B' == $mentor[0]->nilai) echo  "selected" ?>>B</option>
								</select>
							</div>
						</div>
					</div>

					<div class="row clearfix">
						<div class="col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-5">
							<button type="submit" class="btn btn-primary m-t-15 waves-effect">UPDATE</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="foto" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url(); ?>Mentor/update_foto" method="post" enctype="multipart/form-data">
				<div class="modal-header">
					<h4 class="modal-title" id="defaultModalLabel">Update Foto</h4>
				</div>
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-xs-12">
							<div class="form-group">
								<div class="form-line">
									<input style="opacity: 0; cursor: pointer;" type="file" id="file" name="img" class="form-control" accept="image/jpeg, image/pjpeg, image/png', image/x-png" required>
									<label for="file" id="labelfile" style="cursor: pointer;">Pilih foto . . .</label>
								</div>
							</div>
							<small>Ukuran maksimal foto yang diperbolehkan adalah <strong>1 MB</strong>.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
					<button type="submit" class="btn btn-link waves-effect">UPDATE</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="cv" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="<?php echo base_url(); ?>Mentor/update_cv" method="post" enctype="multipart/form-data">
				<div class="modal-header">
					<h4 class="modal-title" id="defaultModalLabel">Update CV</h4>
				</div>
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-xs-12">
							<div class="form-group">
								<div class="form-line">
									<input style="opacity: 0; cursor: pointer;" type="file" id="file2" name="cv" class="form-control" required>
									<label for="file2" id="labelfile2" style="cursor: pointer;">Pilih file CV . . .</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CANCEL</button>
					<button type="submit" class="btn btn-link waves-effect">UPDATE</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$(function ()
	{
		$("#file").change(function()
		{
			$("#labelfile").text("Foto telah terpilih, silahkan click UPDATE!");
		});
		$("#file2").change(function()
		{
			$("#labelfile2").text("CV telah terpilih, silahkan click UPDATE!");
		});
	});
</script>