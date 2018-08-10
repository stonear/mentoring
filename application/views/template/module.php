<li class="header">MAIN NAVIGATION</li>

<?php if ($role == 'Admin') : ?>
	<li class="<?php if($title == 'Dashboard'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin">
			<i class="material-icons <?php if($title == 'Dashboard'){echo 'col-light-blue';} ?>">home</i>
			<span>Home</span>
		</a>
	</li>
	<li class="<?php if($title == 'Presensi'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/presensi">
			<i class="material-icons <?php if($title == 'Presensi'){echo 'col-light-blue';} ?>">group</i>
			<span>Presensi</span>
		</a>
	</li>
	<li class="<?php if($title == 'Penilaian'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/penilaian">
			<i class="material-icons <?php if($title == 'Penilaian'){echo 'col-light-blue';} ?>">assignment</i>
			<span>Penilaian</span>
		</a>
	</li>
	<li class="<?php if($title == 'Presensi Mentor'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/presensimentor">
			<i class="material-icons <?php if($title == 'Presensi Mentor'){echo 'col-light-blue';} ?>">group</i>
			<span>Presensi Mentor</span>
		</a>
	</li>
	<li class="header">DATABASE</li>
	<li class="<?php if($title == 'Kelompok'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/kelompok">
			<i class="material-icons <?php if($title == 'Kelompok'){echo 'col-light-blue';} ?>">group_add</i>
			<span>Kelompok</span>
		</a>
	</li>
	<li class="<?php if($title == 'Aspek Penilaian'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/aspekpenilaian">
			<i class="material-icons <?php if($title == 'Aspek Penilaian'){echo 'col-light-blue';} ?>">assessment</i>
			<span>Aspek Penilaian</span>
		</a>
	</li>
	<li class="<?php if($title == 'Jumlah Pertemuan'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/jumlahpertemuan">
			<i class="material-icons <?php if($title == 'Jumlah Pertemuan'){echo 'col-light-blue';} ?>">spellcheck</i>
			<span>Jumlah Pertemuan</span>
		</a>
	</li>
	<li class="<?php if($title == 'Mentor'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/mentor">
			<i class="material-icons <?php if($title == 'Mentor'){echo 'col-light-blue';} ?>">class</i>
			<span>Mentor</span>
		</a>
	</li>
	<li class="<?php if($title == 'Pembina'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/pembina">
			<i class="material-icons <?php if($title == 'Pembina'){echo 'col-light-blue';} ?>">border_color</i>
			<span>Dosen Pembina</span>
		</a>
	</li>
	<li class="<?php if($title == 'Admin'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/admin">
			<i class="material-icons <?php if($title == 'Admin'){echo 'col-light-blue';} ?>">code</i>
			<span>Admin</span>
		</a>
	</li>
	<li class="header">CORE</li>
	<li class="<?php if($title == 'Sync Data'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Admin/sync">
			<i class="material-icons <?php if($title == 'Sync Data'){echo 'col-light-blue';} ?>">system_update</i>
			<span>Sync Data</span>
		</a>
	</li>
<?php elseif ($role == 'Mentor') : ?>
	<li class="<?php if($title == 'Dashboard' || $title == 'Profil'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Mentor">
			<i class="material-icons <?php if($title == 'Dashboard' || $title == 'Profil'){echo 'col-light-blue';} ?>">home</i>
			<span>Home</span>
		</a>
	</li>
	<li class="<?php if($title == 'Presensi'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Mentor/presensi">
			<i class="material-icons <?php if($title == 'Presensi'){echo 'col-light-blue';} ?>">group</i>
			<span>Presensi</span>
		</a>
	</li>
	<li class="<?php if($title == 'Penilaian'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Mentor/penilaian">
			<i class="material-icons <?php if($title == 'Penilaian'){echo 'col-light-blue';} ?>">assignment</i>
			<span>Penilaian</span>
		</a>
	</li>
	<li class="header">OTHER</li>
	<li class="<?php if($title == 'Password'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Mentor/password">
			<i class="material-icons <?php if($title == 'Password'){echo 'col-light-blue';} ?>">vpn_key</i>
			<span>Password</span>
		</a>
	</li>

<?php elseif ($role == 'Dosen') : ?>
	<li class="<?php if($title == 'Dashboard'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Dosen">
			<i class="material-icons <?php if($title == 'Dashboard'){echo 'col-light-blue';} ?>">home</i>
			<span>Home</span>
		</a>
	</li>
	<li class="<?php if($title == 'Presensi'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Dosen/presensi">
			<i class="material-icons <?php if($title == 'Presensi'){echo 'col-light-blue';} ?>">group</i>
			<span>Presensi</span>
		</a>
	</li>
	<li class="<?php if($title == 'Penilaian'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Dosen/penilaian">
			<i class="material-icons <?php if($title == 'Penilaian'){echo 'col-light-blue';} ?>">assignment</i>
			<span>Penilaian</span>
		</a>
	</li>
	<li class="header">OTHER</li>
	<li class="<?php if($title == 'Password'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Dosen/password">
			<i class="material-icons <?php if($title == 'Password'){echo 'col-light-blue';} ?>">vpn_key</i>
			<span>Password</span>
		</a>
	</li>

<?php elseif ($role == 'Pembina') : ?>
	<li class="<?php if($title == 'Dashboard'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Pembina">
			<i class="material-icons <?php if($title == 'Dashboard'){echo 'col-light-blue';} ?>">home</i>
			<span>Home</span>
		</a>
	</li>
	<li class="<?php if($title == 'Presensi Mentor'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Pembina/presensimentor">
			<i class="material-icons <?php if($title == 'Presensi Mentor'){echo 'col-light-blue';} ?>">group</i>
			<span>Presensi Mentor</span>
		</a>
	</li>
	<li class="<?php if($title == 'Presensi Peserta'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Pembina/presensipeserta">
			<i class="material-icons <?php if($title == 'Presensi Mentee'){echo 'col-light-blue';} ?>">group</i>
			<span>Presensi Peserta</span>
		</a>
	</li>
	<li class="<?php if($title == 'Penilaian Peserta'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Pembina/penilaianpeserta">
			<i class="material-icons <?php if($title == 'Penilaian Peserta'){echo 'col-light-blue';} ?>">assignment</i>
			<span>Penilaian Peserta</span>
		</a>
	</li>
	<li class="header">OTHER</li>
	<li class="<?php if($title == 'Password'){echo 'active';} ?>">
		<a href="<?php echo base_url(); ?>Pembina/password">
			<i class="material-icons <?php if($title == 'Password'){echo 'col-light-blue';} ?>">vpn_key</i>
			<span>Password</span>
		</a>
	</li>
<?php endif; ?>