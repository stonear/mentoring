<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
	protected $data;
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->model('absen');
		$this->load->model('absenmentor');
		$this->load->model('Admin2');
		$this->load->model('api');
		$this->load->model('berita');
		$this->load->model('database');
		$this->load->model('dosen');
		$this->load->model('fileabsen');
		$this->load->model('fileabsenmentor');
		$this->load->model('jadwal');
		$this->load->model('jadwalmentor');
		$this->load->model('jadwalregistrasi');
		$this->load->model('kelas');
		$this->load->model('kelompok');
		$this->load->model('masternilai');
		$this->load->model('mentor_model');
		$this->load->model('nilai');
		$this->load->model('pertemuan');
		$this->load->model('peserta');
		$this->load->model('pembina');
		$this->load->model('smtmentor');
		$this->load->model('verification');

		if($this->session->userdata('status') != 'login' or $this->session->userdata('role') != 'Admin')
		{
      		redirect('Auth');
    	}

		$this->data['nama'] = $this->session->userdata('nama');
		$this->data['nrp'] = $this->session->userdata('nrp');
		$this->data['role'] = $this->session->userdata('role');
	}
	public function index()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Dashboard',
			'module' => 'dashboard',

			// 'berita' => $this->berita->select_berita(5),
			'berita' => $this->berita->select_berita_byTahun(date("Y")),

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function berita_lama($tahun = "")
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Dashboard',
			'module' => 'beritalama',

			'berita' => $this->berita->select_berita_byTahun($tahun),
			'tahun' => $this->berita->select_tahun(),
			'tahun_selected' => $tahun,

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function presensi()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$kelas = $this->input->post('kelas');
		$kelas = $this->security->xss_clean($kelas);
		$kelompok = $this->input->post('kelompok');
		$kelompok = $this->security->xss_clean($kelompok);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_kelas = $this->session->flashdata('kelas');
		$temp_kelompok = $this->session->flashdata('kelompok');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_kelas)) $kelas = $temp_kelas;
		if (isset($temp_kelompok)) $kelompok = $temp_kelompok;

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);
		if (!empty($jumlahpertemuan)) $jumlahpertemuan = $jumlahpertemuan[0]->jumlahpertemuan;
		else $jumlahpertemuan = 0;

		if ($kelompok == -1)
		{
			$peserta = $this->peserta->select_peserta_bykelas($kelas);

			$data = array
			(
				'nama' => $this->data['nama'],
				'nrp' => $this->data['nrp'],
				'role' => $this->data['role'],
				'title' => 'Presensi',
				'module' => 'presensi',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas($tahun, $semester),
				'kelompok' => $this->kelompok->select_kelompok($kelas),

				'tahun_selected' => $tahun,
				'semester_selected' => $semester,
				'kelas_selected' => $kelas,
				'kelompok_selected' => $kelompok,

				'peserta' => $peserta,
				'jumlahpertemuan' => $jumlahpertemuan,
				'presensi' => $this->absen->select_absen_bykelas($kelas),
				'jadwal' => -1,
				'file' => -1
			);
			$this->load->view('master-layout', $data);
		}
		else
		{
			$peserta = $this->peserta->select_peserta($kelas, $kelompok);
		
			$data = array
			(
				'nama' => $this->data['nama'],
				'nrp' => $this->data['nrp'],
				'role' => $this->data['role'],
				'title' => 'Presensi',
				'module' => 'presensi',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas($tahun, $semester),
				'kelompok' => $this->kelompok->select_kelompok($kelas),

				'tahun_selected' => $tahun,
				'semester_selected' => $semester,
				'kelas_selected' => $kelas,
				'kelompok_selected' => $kelompok,

				'peserta' => $peserta,
				'jumlahpertemuan' => $jumlahpertemuan,
				'presensi' => $this->absen->select_absen($kelompok),
				'jadwal' => $this->jadwal->select_jadwal($kelompok),
				'file' => $this->fileabsen->select_file($kelompok),
				'mentor' => $this->kelompok->select_mentor($kelompok)
			);
			$this->load->view('master-layout', $data);
		}
	}
	public function update_presensi($tahun, $semester, $kelas, $kelompok)
	{
		if ($kelompok == -1) $peserta = $this->peserta->select_peserta_bykelas($kelas);
		else $peserta = $this->peserta->select_peserta($kelas, $kelompok);

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);
		if (!empty($jumlahpertemuan)) $jumlahpertemuan = $jumlahpertemuan[0]->jumlahpertemuan;

		foreach ($peserta as $p)
		{
			for ($i = 1; $i <= $jumlahpertemuan; $i++)
			{
				// if ($this->input->post($p->NRPpeserta.'-'.$i) == 'on')
				// {
				// 	$this->absen->update_absen($p->NRPpeserta, $i, 1);
				// }
				// else
				// {
				// 	$this->absen->update_absen($p->NRPpeserta, $i, 0);
				// }
				$this->absen->update_absen($p->NRPpeserta, $i, $this->input->post($p->NRPpeserta.'-'.$i));
			}
		}
		if ($kelompok != -1)
		{
			for ($i = 1; $i <= $jumlahpertemuan; $i++)
			{
				$tanggal = $this->input->post($i);
				$this->jadwal->update_jadwal($kelompok, $i, $tanggal);
			}
		}

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('kelas', $kelas);
		$this->session->set_flashdata('kelompok', $kelompok);

		$this->session->set_flashdata('message', 'Berhasil memperbarui presensi');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/presensi');
	}
	public function tambah_filepresensi($tahun, $semester, $kelas, $kelompok)
	{
		$mingguke = $this->input->post('mingguke');
		$mingguke = $this->security->xss_clean($mingguke);

		$path = './uploads/'.$kelas.'/'.$kelompok.'/'.$mingguke.'/';
		if (!is_dir('uploads'))
		{
			mkdir('./uploads', 0777, true);
		}
		if (!is_dir('uploads/'.$kelas))
		{
			mkdir('./uploads/'.$kelas, 0777, true);
		}
		if (!is_dir('uploads/'.$kelas.'/'.$kelompok))
		{
			mkdir('./uploads/'.$kelas.'/'.$kelompok, 0777, true);
		}
		if (!is_dir('uploads/'.$kelas.'/'.$kelompok.'/'.$mingguke))
		{
			mkdir('./uploads/'.$kelas.'/'.$kelompok.'/'.$mingguke, 0777, true);
		}

		$config['upload_path']		= $path; 
		$config['allowed_types']	= 'gif|jpg|png';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 2048;
		$config['max_width']		= 0;
		$config['max_height']		= 0;
		$config['file_ext_tolower']	= TRUE;
		$config['remove_spaces'] = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			$this->session->set_flashdata('message', $this->upload->display_errors());
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		else
		{ 
			$data = array('upload_data' => $this->upload->data());
			$this->fileabsen->create_file($mingguke, $kelompok, base_url().'uploads/'.$kelas.'/'.$kelompok.'/'.$mingguke.'/'.$data['upload_data']['file_name']);
			$this->session->set_flashdata('message', 'Berhasil mengupdate file presensi');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('kelas', $kelas);
		$this->session->set_flashdata('kelompok', $kelompok);
		redirect('Admin/presensi');
	}
	public function hapus_filepresensi($tahun, $semester, $kelas, $kelompok, $mingguke)
	{
		$linkfile = $this->fileabsen->path2file($kelompok, $mingguke);
		foreach ($linkfile as $l)
		{
			$path = str_replace(base_url(), "", $l->linkfile);
			unlink($path);
		}
		$this->fileabsen->delete_file($kelompok, $mingguke);

		$this->session->set_flashdata('message', 'Berhasil menghapus file presensi');
		$this->session->set_flashdata('message_bg', 'bg-green');

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('kelas', $kelas);
		$this->session->set_flashdata('kelompok', $kelompok);
		redirect('Admin/presensi');
	}
	public function download_filepresensi($kelas, $kelompok)
	{
		$this->load->library('zip');
		$path = FCPATH.'/uploads/'.$kelas.'/'.$kelompok.'/';
		$this->zip->read_dir($path, FALSE);
		$this->zip->download('presensi.zip');
	}
	public function download_presensi($kelas, $kelompok)
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		$tahunsmt = $this->kelas->select_tahunsemester($kelas);
		if (!empty($tahunsmt[0]->semester))
		{
			if ($tahunsmt[0]->semester) $semester = 'GASAL';
			else $semester = 'GENAP';
		}
		else  $semester = '';
		$title = 'DAFTAR HADIR KEGIATAN MENTORING ITS - '.$semester.' '.$tahunsmt[0]->tahun.'/'.($tahunsmt[0]->tahun + 1);

		$objPHPExcel->getActiveSheet()->setTitle('Presensi');

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahunsmt[0]->tahun, $tahunsmt[0]->semester);

		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		for ($i = ord('A'); $i <= (ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Nama Mentor:');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dosen Agama:');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Dosen Pembina:');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($bold);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($bold);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($bold);

		$mentor = $this->kelompok->select_mentor($kelompok);
		$dosen = $this->kelompok->select_dosen($kelompok);
		$pembina = $this->kelompok->select_pembina($kelompok);
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $mentor[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', $dosen[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B4', $pembina[0]->nama);

		$objPHPExcel->getActiveSheet()->setCellValue('A5', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('B5', 'NAMA');
		$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Pertemuan ke-');
		$objPHPExcel->getActiveSheet()->mergeCells('C5:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'5');
		$objPHPExcel->getActiveSheet()->mergeCells('A5:A7');
		$objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
		$objPHPExcel->getActiveSheet()->getStyle('A5:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'7')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A5:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'7')->applyFromArray($styleArray);

		$k = 1;
		$jadwal = $this->jadwal->select_jadwal($kelompok);
		for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'6', $k);
			$detected = false;
			foreach ($jadwal as $j)
			{
				if ($j->mingguke == $k)
				{
					if (!empty($j->tanggal) and $j->tanggal != ' ')
					{
						$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'7', $j->tanggal);
						$detected = true;
					}
				}
			}
			if ($detected == false)
			{
				$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'7', '          ');
			}
			$k++;
		}

		$peserta = $this->peserta->select_peserta($kelas, $kelompok);
		$presensi = $this->absen->select_absen($kelompok);
		$k = 8;
		foreach ($peserta as $p)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, $p->NRPpeserta);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$k, $p->nama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':B'.$k)->applyFromArray($styleArray);
			for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
			{
				foreach ($presensi as $j)
				{
					if ($j->nrp == $p->NRPpeserta and $j->mingguke == ($i - ord('B')))
					{
						if ($j->status == 1) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'hadir');
						else if ($j->status == 2) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'sakit');
						else if ($j->status == 3) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'izin');
						else if ($j->status == 4) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'alpha');
					}
				}
				$objPHPExcel->getActiveSheet()->getStyle(chr($i).$k)->applyFromArray($styleArray);
			}
			$k++;
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="presensi.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function download_templatepresensi($kelas, $kelompok)
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		$tahunsmt = $this->kelas->select_tahunsemester($kelas);
		if (!empty($tahunsmt[0]->semester))
		{
			if ($tahunsmt[0]->semester) $semester = 'GASAL';
			else $semester = 'GENAP';
		}
		else  $semester = '';
		$title = 'DAFTAR HADIR KEGIATAN MENTORING ITS - '.$semester.' '.$tahunsmt[0]->tahun.'/'.($tahunsmt[0]->tahun + 1);

		$objPHPExcel->getActiveSheet()->setTitle('Presensi');

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahunsmt[0]->tahun, $tahunsmt[0]->semester);

		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		for ($i = ord('A'); $i <= (ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Nama Mentor:');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dosen Agama:');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Dosen Pembina:');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($bold);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($bold);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($bold);

		$mentor = $this->kelompok->select_mentor($kelompok);
		$dosen = $this->kelompok->select_dosen($kelompok);
		$pembina = $this->kelompok->select_pembina($kelompok);
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $mentor[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', $dosen[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B4', $pembina[0]->nama);

		$objPHPExcel->getActiveSheet()->setCellValue('A5', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('B5', 'NAMA');
		$objPHPExcel->getActiveSheet()->setCellValue('C5', 'Pertemuan ke-');
		$objPHPExcel->getActiveSheet()->mergeCells('C5:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'5');
		$objPHPExcel->getActiveSheet()->mergeCells('A5:A7');
		$objPHPExcel->getActiveSheet()->mergeCells('B5:B7');
		$objPHPExcel->getActiveSheet()->getStyle('A5:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'7')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A5:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'7')->applyFromArray($styleArray);

		$k = 1;
		for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'6', $k);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'7', '          ');
			$k++;
		}

		$peserta = $this->peserta->select_peserta($kelas, $kelompok);
		$k = 8;
		foreach ($peserta as $p)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, $p->NRPpeserta);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$k, $p->nama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':B'.$k)->applyFromArray($styleArray);
			for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
			{
				$objPHPExcel->getActiveSheet()->getStyle(chr($i).$k)->applyFromArray($styleArray);
			}
			$k++;
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, 'PARAF');
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$k.':B'.($k + 2));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':B'.($k + 2))->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).($k + 2))->applyFromArray($styleArray);
		for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->mergeCells(chr($i).$k.':'.chr($i).($k + 2));
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="template-presensi.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function presensimentor()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$pembina = $this->input->post('pembina');
		$pembina = $this->security->xss_clean($pembina);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_pembina = $this->session->flashdata('pembina');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_pembina)) $pembina = $temp_pembina;

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);
		if (!empty($jumlahpertemuan)) $jumlahpertemuan = $jumlahpertemuan[0]->jumlahpertemuan;
		else $jumlahpertemuan = 0;

		if ($pembina == -1)
		{
			$mentor = $this->mentor_model->select_mentor2($tahun, $semester);

			$data = array
			(
				'nama' => $this->data['nama'],
				'nrp' => $this->data['nrp'],
				'role' => $this->data['role'],
				'title' => 'Presensi Mentor',
				'module' => 'presensimentor',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'pembina' => $this->pembina->select_pembina_bytahunsemester($tahun, $semester),

				'tahun_selected' => $tahun,
				'semester_selected' => $semester,
				'pembina_selected' => $pembina,

				'mentor' => $mentor,
				'jumlahpertemuan' => $jumlahpertemuan,
				'presensi' => $this->absenmentor->select_absen2($tahun, $semester),
				'jadwal' => -1,
				'file' => -1
			);
			$this->load->view('master-layout', $data);
		}
		else
		{
			$mentor = $this->mentor_model->select_mentor_bypembina($tahun, $semester, $pembina);

			$data = array
			(
				'nama' => $this->data['nama'],
				'nrp' => $this->data['nrp'],
				'role' => $this->data['role'],
				'title' => 'Presensi Mentor',
				'module' => 'presensimentor',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'pembina' => $this->pembina->select_pembina_bytahunsemester($tahun, $semester),

				'tahun_selected' => $tahun,
				'semester_selected' => $semester,
				'pembina_selected' => $pembina,

				'mentor' => $mentor,
				'jumlahpertemuan' => $jumlahpertemuan,
				'presensi' => $this->absenmentor->select_absen($tahun, $semester, $pembina),
				'jadwal' => $this->jadwalmentor->select_jadwal($tahun, $semester, $pembina),
				'file' => $this->fileabsenmentor->select_file($tahun, $semester, $pembina)
			);
			$this->load->view('master-layout', $data);
		}
	}
	public function update_presensimentor($tahun, $semester, $pembina)
	{
		if ($pembina == -1) $mentor = $this->mentor_model->select_mentor2($tahun, $semester);
		else $mentor = $this->mentor_model->select_mentor_bypembina($tahun, $semester, $pembina);

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);
		if (!empty($jumlahpertemuan)) $jumlahpertemuan = $jumlahpertemuan[0]->jumlahpertemuan;

		foreach ($mentor as $m)
		{
			for ($i = 1; $i <= $jumlahpertemuan; $i++)
			{
				// if ($this->input->post($m->nrp.'-'.$i) == 'on')
				// {
				// 	$this->absenmentor->update_absen($tahun, $semester, $i, $m->nrp, 1);
				// }
				// else
				// {
				// 	$this->absenmentor->update_absen($tahun, $semester, $i, $m->nrp, 0);
				// }
				$this->absenmentor->update_absen($tahun, $semester, $i, $m->nrp, $this->input->post($m->nrp.'-'.$i));
			}
		}
		if ($pembina != -1)
		{
			for ($i = 1; $i <= $jumlahpertemuan; $i++)
			{
				$tanggal = $this->input->post($i);
				$this->jadwalmentor->update_jadwal($tahun, $semester, $i, $pembina, $tanggal);
			}
		}

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('pembina', $pembina);

		$this->session->set_flashdata('message', 'Berhasil memperbarui presensi');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/presensimentor');
	}
	public function hapus_filepresensimentor($tahun, $semester, $pembina, $mingguke)
	{
		$linkfile = $this->fileabsenmentor->path2file($pembina, $mingguke);
		foreach ($linkfile as $l)
		{
			$path = str_replace(base_url(), "", $l->link);
			unlink($path);
		}
		$this->fileabsenmentor->delete_file($pembina, $mingguke);

		$this->session->set_flashdata('message', 'Berhasil menghapus file presensi');
		$this->session->set_flashdata('message_bg', 'bg-green');

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('pembina', $pembina);
		redirect('Admin/presensimentor');
	}
	public function tambah_filepresensimentor($tahun, $semester, $pembina)
	{
		$mingguke = $this->input->post('mingguke');
		$mingguke = $this->security->xss_clean($mingguke);

		$path = './uploads2/'.$tahun.'/'.$semester.'/'.$pembina.'/'.$mingguke.'/';
		if (!is_dir('uploads2'))
		{
			mkdir('./uploads2', 0777, true);
		}
		if (!is_dir('uploads2/'.$tahun))
		{
			mkdir('./uploads2/'.$tahun, 0777, true);
		}
		if (!is_dir('uploads2/'.$tahun.'/'.$semester))
		{
			mkdir('./uploads2/'.$tahun.'/'.$semester, 0777, true);
		}
		if (!is_dir('uploads2/'.$tahun.'/'.$semester.'/'.$pembina))
		{
			mkdir('./uploads2/'.$tahun.'/'.$semester.'/'.$pembina, 0777, true);
		}
		if (!is_dir('uploads2/'.$tahun.'/'.$semester.'/'.$pembina))
		{
			mkdir('./uploads2/'.$tahun.'/'.$semester.'/'.$pembina, 0777, true);
		}
		if (!is_dir('uploads2/'.$tahun.'/'.$semester.'/'.$pembina.'/'.$mingguke))
		{
			mkdir('./uploads2/'.$tahun.'/'.$semester.'/'.$pembina.'/'.$mingguke, 0777, true);
		}

		$config['upload_path']		= $path; 
		$config['allowed_types']	= 'gif|jpg|png';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 2048;
		$config['max_width']		= 0;
		$config['max_height']		= 0;
		$config['file_ext_tolower']	= TRUE;
		$config['remove_spaces'] = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			$this->session->set_flashdata('message', $this->upload->display_errors());
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		else
		{ 
			$data = array('upload_data' => $this->upload->data());
			$this->fileabsenmentor->create_file($tahun, $semester, $mingguke, $pembina, base_url().'uploads2/'.$tahun.'/'.$semester.'/'.$pembina.'/'.$mingguke.'/'.$data['upload_data']['file_name']);
			$this->session->set_flashdata('message', 'Berhasil mengupdate file presensi');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('pembina', $pembina);
		redirect('Admin/presensimentor');
	}
	public function download_filepresensimentor($tahun, $semester, $pembina)
	{
		$this->load->library('zip');
		$path = FCPATH.'/uploads2/'.$tahun.'/'.$semester.'/'.$pembina.'/';
		$this->zip->read_dir($path, FALSE);
		$this->zip->download('presensi.zip');
	}
	public function download_presensimentor($tahun, $semester, $pembina)
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		if ($semester == 1) $text_semester = 'GASAL';
		else $text_semester = 'GENAP';
		$title = 'DAFTAR HADIR KEGIATAN PEMBINAAN MENTOR ITS - '.$text_semester.' '.$tahun.'/'.($tahun + 1);

		$objPHPExcel->getActiveSheet()->setTitle('Presensi');

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);

		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		for ($i = ord('A'); $i <= (ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Dosen Pembina:');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($bold);

		$namapembina = $this->pembina->select_pembina_bynik($pembina);
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $namapembina[0]->nama);

		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('B3', 'NAMA');
		$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Pertemuan ke-');
		$objPHPExcel->getActiveSheet()->mergeCells('C3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'3');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:A5');
		$objPHPExcel->getActiveSheet()->mergeCells('B3:B5');
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'5')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'5')->applyFromArray($styleArray);

		$k = 1;
		$jadwal = $this->jadwalmentor->select_jadwal($tahun, $semester, $pembina);
		for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'4', $k);
			$detected = false;
			foreach ($jadwal as $j)
			{
				if ($j->mingguke == $k)
				{
					if (!empty($j->tanggal) and $j->tanggal != ' ')
					{
						$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'5', $j->tanggal);
						$detected = true;
					}
				}
			}
			if ($detected == false)
			{
				$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'5', '          ');
			}
			$k++;
		}

		$mentor = $this->mentor_model->select_mentor_bypembina($tahun, $semester, $pembina);
		$presensi = $this->absenmentor->select_absen($tahun, $semester, $pembina);
		$k = 6;
		foreach ($mentor as $m)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, $m->nrp);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$k, $m->nama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':B'.$k)->applyFromArray($styleArray);
			for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
			{
				foreach ($presensi as $j)
				{
					if ($j->nrp == $m->nrp and $j->mingguke == ($i - ord('B')))
					{
						if ($j->status == 1) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'hadir');
						else if ($j->status == 2) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'sakit');
						else if ($j->status == 3) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'izin');
						else if ($j->status == 4) $objPHPExcel->getActiveSheet()->setCellValue(chr($i).$k, 'alpha');
					}
					$objPHPExcel->getActiveSheet()->getStyle(chr($i).$k)->applyFromArray($styleArray);
				}
			}
			$k++;
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="presensi.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function download_templatepresensimentor($tahun, $semester, $pembina)
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		if ($semester == 1) $text_semester = 'GASAL';
		else $text_semester = 'GENAP';
		$title = 'DAFTAR HADIR KEGIATAN PEMBINAAN MENTOR ITS - '.$text_semester.' '.$tahun.'/'.($tahun + 1);

		$objPHPExcel->getActiveSheet()->setTitle('Presensi');

		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);

		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		for ($i = ord('A'); $i <= (ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Dosen Pembina:');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($bold);

		$namapembina = $this->pembina->select_pembina_bynik($pembina);
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $namapembina[0]->nama);

		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('B3', 'NAMA');
		$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Pertemuan ke-');
		$objPHPExcel->getActiveSheet()->mergeCells('C3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'3');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:A5');
		$objPHPExcel->getActiveSheet()->mergeCells('B3:B5');
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'5')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'5')->applyFromArray($styleArray);

		$k = 1;
		for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'4', $k);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'5', '          ');
			$k++;
		}

		$mentor = $this->mentor_model->select_mentor_bypembina($tahun, $semester, $pembina);
		$k = 6;
		foreach ($mentor as $m)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, $m->nrp);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$k, $m->nama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':B'.$k)->applyFromArray($styleArray);
			for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
			{
				$objPHPExcel->getActiveSheet()->getStyle(chr($i).$k)->applyFromArray($styleArray);
			}
			$k++;
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, 'PARAF');
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$k.':B'.($k + 2));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':B'.($k + 2))->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$k.':'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).($k + 2))->applyFromArray($styleArray);
		for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->mergeCells(chr($i).$k.':'.chr($i).($k + 2));
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="template-presensi.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function penilaian()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$kelas = $this->input->post('kelas');
		$kelas = $this->security->xss_clean($kelas);
		$kelompok = $this->input->post('kelompok');
		$kelompok = $this->security->xss_clean($kelompok);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_kelas = $this->session->flashdata('kelas');
		$temp_kelompok = $this->session->flashdata('kelompok');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_kelas)) $kelas = $temp_kelas;
		if (isset($temp_kelompok)) $kelompok = $temp_kelompok;

		$aspekpenilaian = $this->masternilai->select_masternilai();

		if ($kelompok == -1)
		{
			$peserta = $this->peserta->select_peserta_bykelas($kelas);

			$data = array
			(
				'nama' => $this->data['nama'],
				'nrp' => $this->data['nrp'],
				'role' => $this->data['role'],
				'title' => 'Penilaian',
				'module' => 'penilaian',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas($tahun, $semester),
				'kelompok' => $this->kelompok->select_kelompok($kelas),

				'tahun_selected' => $tahun,
				'semester_selected' => $semester,
				'kelas_selected' => $kelas,
				'kelompok_selected' => $kelompok,

				'peserta' => $peserta,
				'aspekpenilaian' => $aspekpenilaian,
				'nilai' => $this->nilai->select_nilai_bykelas($kelas)
			);
			$this->load->view('master-layout', $data);
		}
		else
		{
			$peserta = $this->peserta->select_peserta($kelas, $kelompok);
		
			$data = array
			(
				'nama' => $this->data['nama'],
				'nrp' => $this->data['nrp'],
				'role' => $this->data['role'],
				'title' => 'Penilaian',
				'module' => 'penilaian',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas($tahun, $semester),
				'kelompok' => $this->kelompok->select_kelompok($kelas),

				'tahun_selected' => $tahun,
				'semester_selected' => $semester,
				'kelas_selected' => $kelas,
				'kelompok_selected' => $kelompok,

				'peserta' => $peserta,
				'aspekpenilaian' => $aspekpenilaian,
				'nilai' => $this->nilai->select_nilai($kelompok),
				'mentor' => $this->kelompok->select_mentor($kelompok)
			);
			$this->load->view('master-layout', $data);
		}
	}
	public function update_penilaian($tahun, $semester, $kelas, $kelompok)
	{
		if ($kelompok == -1) $peserta = $this->peserta->select_peserta_bykelas($kelas);
		else $peserta = $this->peserta->select_peserta($kelas, $kelompok);

		$aspekpenilaian = $this->masternilai->select_masternilai();

		foreach ($peserta as $p)
		{
			foreach ($aspekpenilaian as $a)
			{
				$nilai = $this->input->post($p->NRPpeserta.'-'.$a->IDnilai);
				$this->nilai->update_nilai($p->NRPpeserta, $a->IDnilai, $nilai);
			}
		}

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('kelas', $kelas);
		$this->session->set_flashdata('kelompok', $kelompok);

		$this->session->set_flashdata('message', 'Berhasil memperbarui nilai');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/penilaian');
	}
	public function download_penilaian($kelas, $kelompok)
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		$tahunsmt = $this->kelas->select_tahunsemester($kelas);
		if (!empty($tahunsmt[0]->semester))
		{
			if ($tahunsmt[0]->semester) $semester = 'GASAL';
			else $semester = 'GENAP';
		}
		else  $semester = '';
		$title = 'REKAPITULASI PENILAIAN KEGIATAN MENTORING ITS - '.$semester.' '.$tahunsmt[0]->tahun.'/'.($tahunsmt[0]->tahun + 1);

		$objPHPExcel->getActiveSheet()->setTitle('Penilaian');

		$aspekpenilaian = $this->masternilai->select_masternilai();

		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr(ord('A') + 1 + count($aspekpenilaian) + 1).'1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		for ($i = ord('A'); $i <= (ord('A') + 1 + count($aspekpenilaian) + 1); $i++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);
		}

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Nama Mentor:');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dosen Agama:');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Dosen Pembina:');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($bold);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($bold);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($bold);

		$mentor = $this->kelompok->select_mentor($kelompok);
		$dosen = $this->kelompok->select_dosen($kelompok);
		$pembina = $this->kelompok->select_pembina($kelompok);
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $mentor[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', $dosen[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B4', $pembina[0]->nama);

		$objPHPExcel->getActiveSheet()->setCellValue('A5', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('B5', 'NAMA');
		$k = ord('C');
		foreach ($aspekpenilaian as $a)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(chr($k).'5', $a->namanilai);
			$k++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue(chr($k).'5', 'Rata-rata');
		$objPHPExcel->getActiveSheet()->getStyle('A5:'.chr(ord('A') + 1 + count($aspekpenilaian) + 1).'5')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A5:'.chr(ord('A') + 1 + count($aspekpenilaian) + 1).'5')->applyFromArray($styleArray);

		$peserta = $this->peserta->select_peserta($kelas, $kelompok);
		$nilai = $this->nilai->select_nilai($kelompok);
		$b = 6;
		foreach ($peserta as $p)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$b, $p->NRPpeserta);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$b, $p->nama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$b.':B'.$b)->applyFromArray($styleArray);
			$total = 0;
			$k = ord('C');
			foreach ($aspekpenilaian as $a)
			{
				foreach ($nilai as $n)
				{
					if ($n->nrp == $p->NRPpeserta and $n->IDnilai == $a->IDnilai)
					{
						$objPHPExcel->getActiveSheet()->setCellValue(chr($k).$b, $n->nilai);
						$total += $n->nilai;
					}
				}
				$objPHPExcel->getActiveSheet()->getStyle(chr($k).$b)->applyFromArray($styleArray);
				$k++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue(chr($k).$b, $total/count($aspekpenilaian));
			$objPHPExcel->getActiveSheet()->getStyle(chr($k).$b)->applyFromArray($styleArray);
			$b++;
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="penilaian.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function kelompok()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$kelas = $this->input->post('kelas');
		$kelas = $this->security->xss_clean($kelas);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_kelas = $this->session->flashdata('kelas');
		// var_dump($temp_tahun);
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_kelas)) $kelas = $temp_kelas;

		if ($kelas == -1)
		{
			$kelompok = $this->kelompok->select_kelompok2($tahun, $semester);
		}
		else
		{
			$kelompok = $this->kelompok->select_kelompok($kelas);
		}
		
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Kelompok',
			'module' => 'kelompok',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'tahun' => $this->kelas->select_tahun(),
			'kelas' => $this->kelas->select_kelas($tahun, $semester),
			'kelompok' => $kelompok,

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,
			'kelas_selected' => $kelas
		);
		$this->load->view('master-layout', $data);
	}
	public function tambah_kelompok()
	{
		$kelas_selected = $this->input->post('kelas_selected');
		$kelas_selected = $this->security->xss_clean($kelas_selected);

		$kelas = $this->kelas->select_tahunsemester($kelas_selected);
		$tahun = $kelas[0]->tahun;
		$semester = $kelas[0]->semester;

		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Kelompok',
			'module' => 'tambahkelompok',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'kelas_selected' => $kelas_selected,
			// 'mentor' => $this->mentor_model->select_mentor(),
			'mentor' => $this->mentor_model->select_mentor3($tahun, $semester),
			'pembina' => $this->pembina->select_pembina(),
			'peserta' => $this->peserta->select_peserta($kelas_selected, -1)
		);
		$this->load->view('master-layout', $data);
	}
	public function tambah_kelompok2()
	{
		$kelas_selected = $this->input->post('kelas_selected');
		$kelas_selected = $this->security->xss_clean($kelas_selected);
		$jenis = $this->input->post('jenis');
		$jenis = $this->security->xss_clean($jenis);
		$no = $this->input->post('no');
		$no = $this->security->xss_clean($no);
		$mentor = $this->input->post('mentor');
		$mentor = $this->security->xss_clean($mentor);
		$pembina = $this->input->post('pembina');
		$pembina = $this->security->xss_clean($pembina);
		$jadwal = $this->input->post('jadwal');
		$jadwal = $this->security->xss_clean($jadwal);
		$peserta = $this->input->post('peserta');
		$peserta = $this->security->xss_clean($peserta);

		// print_r($peserta);
		$this->kelompok->create_kelompok($no, $mentor, $kelas_selected, $pembina, $jadwal, $jenis);
		$IDkelompok = $this->kelompok->select_IDkelompok($no, $mentor, $kelas_selected, $pembina, $jadwal);

		foreach ($peserta as $p)
		{
			$this->peserta->update_kelompok($p, $IDkelompok[0]->IDkelompok);
		}

		$kelas = $this->kelas->select_tahunsemester($kelas_selected);
		$this->session->set_flashdata('tahun', $kelas[0]->tahun);
		$this->session->set_flashdata('semester', $kelas[0]->semester);
		$this->session->set_flashdata('kelas', $kelas_selected);

		$this->session->set_flashdata('message', 'Berhasil menambah data kelompok');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/kelompok');
	}
	public function update_kelompok($kelas_selected = '', $IDkelompok = '')
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Kelompok',
			'module' => 'updatekelompok',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'kelas_selected' => $kelas_selected,
			'mentor' => $this->mentor_model->select_mentor(),
			'pembina' => $this->pembina->select_pembina(),
			'peserta_selected' => $this->peserta->select_peserta($kelas_selected, $IDkelompok),
			'peserta' => $this->peserta->select_peserta($kelas_selected, -1),
			'kelompok' => $this->kelompok->select_kelompok_byID($IDkelompok)
		);
		$this->load->view('master-layout', $data);
	}
	public function update_kelompok2($kelas_selected = '', $IDkelompok = '')
	{
		$jenis = $this->input->post('jenis');
		$jenis = $this->security->xss_clean($jenis);
		$no = $this->input->post('no');
		$no = $this->security->xss_clean($no);
		$mentor = $this->input->post('mentor');
		$mentor = $this->security->xss_clean($mentor);
		$pembina = $this->input->post('pembina');
		$pembina = $this->security->xss_clean($pembina);
		$jadwal = $this->input->post('jadwal');
		$jadwal = $this->security->xss_clean($jadwal);
		$peserta = $this->input->post('peserta');
		$peserta = $this->security->xss_clean($peserta);

		$this->kelompok->update_kelompok($IDkelompok, $no, $mentor, $pembina, $jadwal, $jenis);

		$temp = $this->peserta->select_peserta($kelas_selected, $IDkelompok);
		foreach ($temp as $t)
		{
			$this->peserta->update_kelompok($t->NRPpeserta, -1);
		}
		foreach ($peserta as $p)
		{
			$this->peserta->update_kelompok($p, $IDkelompok);
		}

		$kelas = $this->kelas->select_tahunsemester($kelas_selected);
		$this->session->set_flashdata('tahun', $kelas[0]->tahun);
		$this->session->set_flashdata('semester', $kelas[0]->semester);
		$this->session->set_flashdata('kelas', $kelas_selected);

		$this->session->set_flashdata('message', 'Berhasil memperbarui data kelompok');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/kelompok');
	}
	public function cancel_kelompok($kelas_selected = '')
	{
		$kelas = $this->kelas->select_tahunsemester($kelas_selected);
		$this->session->set_flashdata('tahun', $kelas[0]->tahun);
		$this->session->set_flashdata('semester', $kelas[0]->semester);
		$this->session->set_flashdata('kelas', $kelas_selected);
		redirect('Admin/kelompok');
	}
	public function detail_kelompok($kelas_selected = '', $IDkelompok = '')
	{
		if ($kelas_selected != -1)
		{
			$kelas = $this->kelas->select_tahunsemester($kelas_selected);
			$tahun = $this->session->set_flashdata('tahun', $kelas[0]->tahun);
			$semester = $this->session->set_flashdata('semester', $kelas[0]->semester);
			$kelompok = $this->kelompok->select_kelompok_byID($IDkelompok);
			$peserta = $this->peserta->select_peserta($kelas_selected, $IDkelompok);
		}
		else
		{
			$kelas = $this->kelompok->select_kelas($IDkelompok);
			$kelas = $this->kelas->select_tahunsemester($kelas[0]->IDkelas);
			$tahun = $this->session->set_flashdata('tahun', $kelas[0]->tahun);
			$semester = $this->session->set_flashdata('semester', $kelas[0]->semester);
			$kelompok = $this->kelompok->select_kelompok_byID($IDkelompok);
			$peserta = $this->peserta->select_peserta_byNO($kelompok[0]->NOkelompok, $kelompok[0]->jeniskelamin, $kelas[0]->tahun, $kelas[0]->semester);
		}
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Kelompok',
			'module' => 'detailkelompok',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'kelompok' => $kelompok,
			'peserta' => $peserta,

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,
			'kelas_selected' => $kelas_selected
		);
		$this->load->view('master-layout', $data);
	}
	public function hapus_kelompok($kelas_selected = '', $IDkelompok = '')
	{
		$this->kelompok->delete_kelompok($IDkelompok);

		$kelas = $this->kelas->select_tahunsemester($kelas_selected);
		$this->session->set_flashdata('tahun', $kelas[0]->tahun);
		$this->session->set_flashdata('semester', $kelas[0]->semester);
		$this->session->set_flashdata('kelas', $kelas_selected);

		$this->session->set_flashdata('message', 'Berhasil menghapus data kelompok');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/kelompok');
	}
	public function download_kelompok($tahun = '', $semester = '')
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$fontCenter = array
		(
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		if ($semester == 1) $smt = 'GASAL';
		else $smt = 'GENAP';
		$title = 'DAFTAR KELOMPOK MENTORING ITS - '.$smt.' '.$tahun.'/'.($tahun + 1);
		
		$objPHPExcel->getActiveSheet()->setTitle('Kelompok');

		$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No.');
		$objPHPExcel->getActiveSheet()->setCellValue('B2', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Nama');
		$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Kelas');
		$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Jenis Kelamin');
		$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Kelompok');
		$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Nama Mentor');
		$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Dosen Pembina Mentor');
		$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Jadwal Pelaksanaan');

		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($styleArray);

		$no = 1;
		$row = 3;

		$kelompok = $this->kelompok->select_kelompok2($tahun, $semester);

		foreach ($kelompok as $k)
		{
			$row2 = $row;
			$peserta = $this->peserta->select_peserta_bykelompok($k->id);
			
			//error handling, jika ada kelompok yg tidak ada pesertanya
			if (count($peserta))
			{
				foreach ($peserta as $p)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $no++);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $p->NRPpeserta);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $p->nama);
					$row++;
				}
			}
			else
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, '');
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, '');
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, '');
				$row++;
			}

			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row2, 'TPB-'.$k->kelas);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row2, $k->jenis);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row2, $k->no);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row2, $k->mentor);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row2, $k->pembina);
			if ($k->hari == 1) $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, 'SENIN');
			else if ($k->hari == 2) $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, 'SELASA');
			else if ($k->hari == 3) $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, 'RABU');
			else if ($k->hari == 4) $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, 'KAMIS');
			else if ($k->hari == 5) $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, "JUM'AT");
			else if ($k->hari == 6) $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, 'SABTU');
			else $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, 'AHAD');
			
			$objPHPExcel->getActiveSheet()->mergeCells('D'.$row2.':D'.($row - 1));
			$objPHPExcel->getActiveSheet()->mergeCells('E'.$row2.':E'.($row - 1));
			$objPHPExcel->getActiveSheet()->mergeCells('F'.$row2.':F'.($row - 1));
			$objPHPExcel->getActiveSheet()->mergeCells('G'.$row2.':G'.($row - 1));
			$objPHPExcel->getActiveSheet()->mergeCells('H'.$row2.':H'.($row - 1));
			$objPHPExcel->getActiveSheet()->mergeCells('I'.$row2.':I'.($row - 1));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row2.':I'.($row - 1))->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':I'.($row - 1))->applyFromArray($fontCenter);
		}

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="kelompok.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function admin()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Admin',
			'module' => 'admin',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'admin' => $this->Admin2->select_admin()
		);
		$this->load->view('master-layout', $data);
	}
	public function tambah_admin()
	{
		$username = $this->input->post('username');
		$username = $this->security->xss_clean($username);
		$password = $this->input->post('password');
		$password = $this->security->xss_clean($password);
		$this->Admin2->create_admin($username, $password);
		$this->session->set_flashdata('message', 'Berhasil menambah administrator');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/admin');
	}
	public function update_admin($username = '')
	{
		$username = $this->security->xss_clean($username);
		$password = $this->input->post('password');
		$password = $this->security->xss_clean($password);
		$this->Admin2->update_admin($username, $password);
		$this->session->set_flashdata('message', 'Berhasil memperbarui password administrator');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/admin');
	}
	public function hapus_admin($username = '')
	{	
		$this->Admin2->delete_admin($username);
		$this->session->set_flashdata('message', 'Berhasil menghapus administrator');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/admin');
	}
	public function mentor()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;

		$mentor = $this->mentor_model->select_mentor3($tahun, $semester);

		$updated = false;
		foreach ($mentor as $m)
		{
			if (empty($m->nama))
			{
				$nama = $this->api->get_data_mhs($m->NRPmentor);
				$nama = $nama[0]->nama_lengkap;
				$this->mentor_model->update_namamentor($m->NRPmentor, $nama);
				$updated = true;
			}
			if (empty($m->jenis_kelamin))
			{
				$jenis_kelamin = $this->api->get_data_mhs($m->NRPmentor);
				$jenis_kelamin = $jenis_kelamin[0]->jenis_kelamin;
				$this->mentor_model->update_jkmentor($m->NRPmentor, $jenis_kelamin);
				$updated = true;
			}
		}
		if ($updated)
		{
			$mentor = $this->mentor_model->select_mentor3($tahun, $semester);
		}

		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Mentor',
			'module' => 'mentor',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'tahun' => $this->kelas->select_tahun(),

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,

			// 'mentor' => $this->mentor_model->select_mentor()
			'mentor' => $mentor
		);
		$this->load->view('master-layout', $data);
	}
	public function download_mentor($tahun = "", $semester = "")
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		if ($semester == 1) $smt = 'GASAL';
		else $smt = 'GENAP';
		$title = 'DAFTAR MENTOR MENTORING ITS - '.$smt.' '.$tahun.'/'.($tahun + 1);

		$objPHPExcel->getActiveSheet()->setTitle('Mentor');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'NO');
		$objPHPExcel->getActiveSheet()->setCellValue('B2', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('C2', 'NAMA');
		$objPHPExcel->getActiveSheet()->setCellValue('D2', 'JENIS KELAMIN');
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($styleArray);

		$mentor = $this->mentor_model->select_mentor3($tahun, $semester);

		$no = 1;
		$row = 3;
		foreach ($mentor as $m)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $no++);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $m->NRPmentor);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $m->nama);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $m->jenis_kelamin);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':D'.$row)->applyFromArray($styleArray);
			$row++;
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="mentor.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function profil_mentor($nrp = "", $tahun = "", $semester = "")
	{
		$mentor = $this->mentor_model->select_mentor_byNRP($nrp);
		if (count($mentor) == 0) redirect('Admin/mentor');

		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Mentor',
			'module' => 'profilmentor',

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'mentor' => $mentor
		);
		$this->load->view('master-layout', $data);
	}
	public function update_profil_mentor($nrp = "", $tahun = "", $semester = "")
	{
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$jenis_kelamin = $this->security->xss_clean($jenis_kelamin);
		$no = $this->input->post('no');
		$no = $this->security->xss_clean($no);
		$email = $this->input->post('email');
		$email = $this->security->xss_clean($email);
		$alamat = $this->input->post('alamat');
		$alamat = $this->security->xss_clean($alamat);
		$pernah = $this->input->post('pernah');
		$pernah = $this->security->xss_clean($pernah);
		$nilai = $this->input->post('nilai');
		$nilai = $this->security->xss_clean($nilai);

		$this->mentor_model->update_profil($nrp, $jenis_kelamin, $no, $email, $alamat, $pernah, $nilai);
		$this->session->set_flashdata('message', 'Berhasil mengupdate profil');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/profil_mentor/'.$nrp.'/'.$tahun.'/'.$semester);
	}
	public function update_foto_mentor($nrp = "", $tahun = "", $semester = "")
	{
		$path = './photo/';
		if (!is_dir('photo'))
		{
			mkdir('./photo', 0777, true);
		}
		$config['upload_path']		= $path; 
		$config['allowed_types']	= 'gif|jpg|png';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 1024;
		$config['max_width']		= 0;
		$config['max_height']		= 0;
		$config['file_ext_tolower']	= TRUE;
		$config['remove_spaces'] = TRUE;
		$config['file_name'] = $nrp.'.jpg';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('img'))
		{
			$this->session->set_flashdata('message', $this->upload->display_errors());
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$this->mentor_model->update_foto($nrp, base_url().'photo/'.$data['upload_data']['file_name']);
			$this->session->set_flashdata('message', 'Berhasil mengupdate foto profil<br>Apabila foto belum berubah, silahkan refresh browser anda');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}
		redirect('Admin/profil_mentor/'.$nrp.'/'.$tahun.'/'.$semester);
	}
	public function update_cv_mentor($nrp = "", $tahun = "", $semester = "")
	{
		$path = './cv/';
		if (!is_dir('cv'))
		{
			mkdir('./cv', 0777, true);
		}
		$config['upload_path']		= $path; 
		$config['allowed_types']	= 'word|doc|docx|html';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 2048;
		$config['max_width']		= 0;
		$config['max_height']		= 0;
		$config['file_ext_tolower']	= TRUE;
		$config['remove_spaces'] = TRUE;
		$config['file_name'] = $nrp.'.docx';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('cv'))
		{
			$this->session->set_flashdata('message', $this->upload->display_errors());
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$this->mentor_model->update_cv($nrp, base_url().'cv/'.$data['upload_data']['file_name']);
			$this->session->set_flashdata('message', 'Berhasil mengupdate CV');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}
		redirect('Admin/profil_mentor/'.$nrp.'/'.$tahun.'/'.$semester);
	}
	public function tambah_mentor($tahun = "", $semester = "")
	{
		$nrp = $this->input->post('nrp');
		$nrp = $this->security->xss_clean($nrp);

		$mentor = $this->api->get_data_mhs($nrp);
		
		if ($mentor != NULL)
		{
			// if ($this->mentor_model->exist_mentor($nrp))
			// {
			// 	$this->session->set_flashdata('message', 'Data mentor telah ada sebelumnya');
			// 	$this->session->set_flashdata('message_bg', 'bg-red');
			// }
			// else
			// {
			// 	$this->mentor_model->create_mentor($nrp, $mentor[0]->nama);
			// 	$this->session->set_flashdata('message', 'Berhasil menambah mentor');
			// 	$this->session->set_flashdata('message_bg', 'bg-green');
			// }

			if ($this->mentor_model->exist_mentor($nrp) == false) $this->mentor_model->create_mentor($nrp, $mentor[0]->nama, $mentor[0]->jenis_kelamin);

			$this->verification->verify($nrp);
			$this->smtmentor->registrasi($nrp, $tahun, $semester);

			$this->session->set_flashdata('message', 'Berhasil menambah mentor');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}
		else
		{
			$this->session->set_flashdata('message', 'Data tidak ditemukan');
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		redirect('Admin/mentor');
	}
	public function update_mentor($nrp = '', $tahun = "", $semester = "")
	{
		$nrp = $this->security->xss_clean($nrp);
		$password = $this->input->post('password');
		$password = $this->security->xss_clean($password);
		$this->mentor_model->update_passwordmentor($nrp, $password);

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('message', 'Berhasil memperbarui kata sandi mentor');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/mentor');
	}
	public function hapus_mentor($nrp = '', $tahun = "", $semester = "")
	{
		$nrp = $this->security->xss_clean($nrp);
		//$this->mentor_model->delete_mentor($nrp);
		$this->smtmentor->delete($nrp, $tahun, $semester);

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('message', 'Berhasil menghapus mentor');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/mentor');
	}
	public function pembina()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Pembina',
			'module' => 'pembina',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'pembina' => $this->pembina->select_pembina()
		);
		$this->load->view('master-layout', $data);
	}
	public function download_pembina()
	{
		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		if ($semester == 1) $smt = 'GASAL';
		else $smt = 'GENAP';
		$title = 'DAFTAR DOSEN PEMBINA MENTORING ITS';

		$objPHPExcel->getActiveSheet()->setTitle('Dosen Pembina');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($bold);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'NO');
		$objPHPExcel->getActiveSheet()->setCellValue('B2', 'NIP');
		$objPHPExcel->getActiveSheet()->setCellValue('C2', 'NAMA');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($styleArray);

		$pembina = $this->pembina->select_pembina();

		$no = 1;
		$row = 3;
		foreach ($pembina as $p)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $no++);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $p->NIKdosenpembina);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $p->nama);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':C'.$row)->applyFromArray($styleArray);
			$row++;
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="pembina.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
	}
	public function tambah_pembina()
	{
		$nik = $this->input->post('nik');
		$nik = $this->security->xss_clean($nik);

		$pembina = $this->api->get_data_dosen($nik);
		
		if ($pembina != NULL)
		{
			if ($this->pembina->exist_pembina($nik))
			{
				$this->session->set_flashdata('message', 'Data dosen pembina telah ada sebelumnya');
				$this->session->set_flashdata('message_bg', 'bg-red');
			}
			else
			{
				$this->pembina->create_pembina($nik, $pembina->namaLengkap);
				$this->session->set_flashdata('message', 'Berhasil menambah dosen pembina');
				$this->session->set_flashdata('message_bg', 'bg-green');
			}
		}
		else
		{
			$this->session->set_flashdata('message', 'Data tidak ditemukan');
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		redirect('Admin/pembina');
	}
	public function update_pembina($nik = '')
	{
		$nik = $this->security->xss_clean($nik);
		$password = $this->input->post('password');
		$password = $this->security->xss_clean($password);
		$this->pembina->update_passwordpembina($nik, $password);
		$this->dosen->update_password($nik, $password);
		$this->session->set_flashdata('message', 'Berhasil memperbarui kata sandi dosen pembina');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/pembina');
	}
	public function hapus_pembina($nik = '')
	{
		$nik = $this->security->xss_clean($nik);
		$this->pembina->delete_pembina($nik);
		$this->session->set_flashdata('message', 'Berhasil menghapus dosen pembina');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/pembina');
	}
	public function jumlahpertemuan()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Jumlah Pertemuan',
			'module' => 'jumlahpertemuan',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'pertemuan' => $this->pertemuan->select_pertemuan()
		);
		$this->load->view('master-layout', $data);
	}
	public function tambah_pertemuan()
	{
		set_time_limit(0);

		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$jumlah = $this->input->post('jumlah');
		$jumlah = $this->security->xss_clean($jumlah);

		if ($this->pertemuan->exist_pertemuan($tahun, $semester))
		{
			$this->session->set_flashdata('message', 'Data jumlah pertemuan tahun '.$tahun.' semester '.$semester.' sudah ada');
			$this->session->set_flashdata('message_bg', 'bg-red');
			redirect('Admin/jumlahpertemuan');
		}

		$this->pertemuan->create_pertemuan($tahun, $semester, $jumlah);

		$peserta = $this->peserta->select_by_tahunsemester($tahun, $semester);
		foreach ($peserta as $p)
		{
			for ($i = 1; $i <= $jumlah; $i++)
			{
				$this->absen->create_absen($i, $p->nrp);
			}
		}
		$kelompok = $this->kelompok->select_kelompok_bytahunsemester($tahun, $semester);
		foreach ($kelompok as $k)
		{
			for ($i = 1; $i <= $jumlah; $i++)
			{
				$this->jadwal->create_jadwal($i, $k->id);
			}
		}

		$this->session->set_flashdata('message', 'Berhasil menambah data jumlah pertemuan');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/jumlahpertemuan');
	}
	public function update_pertemuan($tahun = '', $semester = '')
	{
		set_time_limit(0);

		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->security->xss_clean($semester);
		$tahun_baru = $this->input->post('tahun');
		$tahun_baru = $this->security->xss_clean($tahun_baru);
		$semester_baru = $this->input->post('semester');
		$semester_baru = $this->security->xss_clean($semester_baru);
		$jumlah_baru = $this->input->post('jumlah');
		$jumlah_baru = $this->security->xss_clean($jumlah_baru);
		$this->pertemuan->update_pertemuan($tahun, $semester, $tahun_baru, $semester_baru, $jumlah_baru);

		$peserta = $this->peserta->select_by_tahunsemester($tahun, $semester);
		foreach ($peserta as $p)
		{
			for ($i = 1; $i <= $jumlah_baru; $i++)
			{
				$this->absen->create_absen($i, $p->nrp);
			}
		}
		$kelompok = $this->kelompok->select_kelompok_bytahunsemester($tahun, $semester);
		foreach ($kelompok as $k)
		{
			for ($i = 1; $i <= $jumlah_baru; $i++)
			{
				$this->jadwal->create_jadwal($i, $k->id);
			}
		}

		$this->session->set_flashdata('message', 'Berhasil memperbarui data jumlah pertemuan');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/jumlahpertemuan');
	}
	public function hapus_pertemuan($tahun = '', $semester = '')
	{	
		$this->pertemuan->delete_pertemuan($tahun, $semester);
		$this->session->set_flashdata('message', 'Berhasil menghapus data jumlah pertemuan');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/jumlahpertemuan');
	}
	public function aspekpenilaian()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Aspek Penilaian',
			'module' => 'aspekpenilaian',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg'),

			'masternilai' => $this->masternilai->select_masternilai()
		);
		$this->load->view('master-layout', $data);
	}
	public function tambah_aspek()
	{
		$namanilai = $this->input->post('namanilai');
		$namanilai = $this->security->xss_clean($namanilai);
		$namanilai = ucwords($namanilai);
		$this->masternilai->create_masternilai($namanilai);
		$this->session->set_flashdata('message', 'Berhasil menambah aspek penilaian');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/aspekpenilaian');
	}
	public function update_aspek($IDnilai = '')
	{
		$IDnilai = $this->security->xss_clean($IDnilai);
		$namanilai_baru = $this->input->post('namanilai');
		$namanilai_baru = $this->security->xss_clean($namanilai_baru);
		$namanilai_baru = ucwords($namanilai_baru);
		$this->masternilai->update_masternilai($IDnilai, $namanilai_baru);
		$this->session->set_flashdata('message', 'Berhasil memperbarui aspek penilaian');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/aspekpenilaian');
	}
	public function hapus_aspek($IDnilai = '')
	{	
		$this->masternilai->delete_masternilai($IDnilai);
		$this->session->set_flashdata('message', 'Berhasil menghapus aspek penilaian');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/aspekpenilaian');
	}
	public function up()
	{
		$this->database->up();
		echo 'success';
	}
	public function sync()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Sync Data',
			'module' => 'sync',

			'tahun' => $this->peserta->select_tahun(),

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function sync2()
	{
		set_time_limit(0);

		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);

		$idUPMB = '__TPB';

		// $listKelas = array('__TPB', '13030', '21030', '21039', '22030', '22039', '23030', '24031', '31030', '31041', '21038', '22038', '22040', '31031', '31032', '31040');
		// $kelas = $this->api->get_daftar_kelas($idUPMB, $tahun, $semester);

		$listKelas = array('__TPB', '13030', '21030', '21038', '21039', '22030', '22038', '22039', '23030', '23039', '24030', '24031', '31030', '31031', '31032', '31033', '31038', '31039', '61030', '62030', '63030', '64030', '71030', '72030', '73030', '74030', '31040', '31041', '33040', '65040', '71040', '72040', '73040', '74040');

		$IDagama = 'IG1101';
		$IDagama_baru = array('UG0901', 'UG1901', 'UG4901');
		
		// foreach($kelas as $k)
		// {
  //   		if ($k->mata_kuliah->id == $IDagama)
  //   		{
  //   			$this->kelas->create_kelas($k->kelas, $k->nip_dosen, $tahun, $semester);
  //   			$IDkelas = $this->kelas->select_IDkelas($tahun, $semester, $k->kelas);

  //   			$this->dosen->create_dosen($k->nip_dosen, $k->dosen);

  //   			$mahasiswa = $this->api->get_daftar_mhs($idUPMB, $IDagama, $k->kelas, $tahun, $semester);
  //   			foreach($mahasiswa as $m)
  //   			{
  //   				$biodata = $this->api->get_data_mhs($m->nrp);
  //   				$this->peserta->create_peserta($m->nrp, $m->nama, $IDkelas[0]->IDkelas, -1, $biodata[0]->jenis_kelamin);
  //   			}
  //   		}
  // 		}

		foreach ($listKelas as $lk)
		{
			$kelas = $this->api->get_daftar_kelas($lk, $tahun, $semester);
			foreach($kelas as $k)
			{
				if ($k->mata_kuliah->id == $IDagama)
				{
					$this->kelas->create_kelas($k->kelas, $k->nip_dosen, $tahun, $semester);
					$IDkelas = $this->kelas->select_IDkelas($tahun, $semester, $k->kelas);

					$this->dosen->create_dosen($k->nip_dosen, $k->dosen);

					$mahasiswa = $this->api->get_daftar_mhs($lk, $IDagama, $k->kelas, $tahun, $semester);
					foreach($mahasiswa as $m)
					{
						$biodata = $this->api->get_data_mhs($m->nrp);
						$this->peserta->create_peserta($m->nrp, $m->nama, $IDkelas[0]->IDkelas, -1, $biodata[0]->jenis_kelamin);
					}
				}
				foreach ($IDagama_baru as $ida)
				{
					if ($k->mata_kuliah->id == $ida)
					{
						$this->kelas->create_kelas($k->kelas, $k->nip_dosen, $tahun, $semester);
						$IDkelas = $this->kelas->select_IDkelas($tahun, $semester, $k->kelas);

						$this->dosen->create_dosen($k->nip_dosen, $k->dosen);

						$mahasiswa = $this->api->get_daftar_mhs($lk, $ida, $k->kelas, $tahun, $semester);
						foreach($mahasiswa as $m)
						{
							$biodata = $this->api->get_data_mhs($m->nrp);
							if (count($biodata))
							{
								$this->peserta->create_peserta($m->nrp, $m->nama, $IDkelas[0]->IDkelas, -1, $biodata[0]->jenis_kelamin);
							}
							else
							{
								$this->peserta->create_peserta($m->nrp, $m->nama, $IDkelas[0]->IDkelas, -1, '-');
							}
						}
					}
				}
			}
  		}

		$this->session->set_flashdata('message', 'Update <i>database</i> selesai');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/sync');
	}
	public function get_kelas()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		
		$kelas = $this->kelas->select_kelas($tahun, $semester);
        echo json_encode($kelas);
	}
	public function get_kelompok()
	{
		$kelas = $this->input->post('kelas');
		$kelas = $this->security->xss_clean($kelas);
		
		$kelompok = $this->kelompok->select_kelompok($kelas);
        echo json_encode($kelompok);
	}
	public function get_pembina()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		
		$pembina = $this->pembina->select_pembina_bytahunsemester($tahun, $semester);
        echo json_encode($pembina);
	}
	public function get_dosen()
	{
		$nama = $this->input->post('nama');
		$nama = $this->security->xss_clean($nama);
		
		$dosen = $this->api->get_daftar_dosen($nama);
        echo json_encode($dosen);
	}
	public function check_kelas()
	{
		$jenis = $this->input->post('jenis');
		$jenis = $this->security->xss_clean($jenis);
		$no = $this->input->post('no');
		$no = $this->security->xss_clean($no);
		$mentor = $this->input->post('mentor');
		$mentor = $this->security->xss_clean($mentor);
		$pembina = $this->input->post('pembina');
		$pembina = $this->security->xss_clean($pembina);
		$jadwal = $this->input->post('jadwal');
		$jadwal = $this->security->xss_clean($jadwal);

		$kelompok = $this->kelompok->exist_kelompok($no, $mentor, $pembina, $jadwal, $jenis);
        echo json_encode($kelompok);
	}
	public function berita()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Berita',
			'module' => 'berita',

			'berita' => $this->berita->select_berita(0),

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function tambah_berita()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Berita',
			'module' => 'tambahberita',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function tambah_berita2()
	{
		$judul = $this->input->post('judul');
		$konten = $this->input->post('konten');

		$this->berita->create_berita($this->data['nama'], $judul, $konten);

		$this->session->set_flashdata('message', 'Berhasil menambah berita');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/berita');
	}
	public function update_berita($id = '')
	{
		$berita = $this->berita->select_berita_byID($id);
		if (!count($berita)) redirect('Admin/berita');

		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Berita',
			'module' => 'updateberita',

			'berita' => $berita,

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function update_berita2()
	{
		$id = $this->input->post('id');
		$judul = $this->input->post('judul');
		$konten = $this->input->post('konten');

		$this->berita->update_berita($id, $judul, $konten);

		$this->session->set_flashdata('message', 'Berhasil memperbarui berita');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/berita');
	}
	public function hapus_berita($id)
	{
		$this->berita->delete_berita($id);

		$this->session->set_flashdata('message', 'Berhasil menghapus berita');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Admin/berita');
	}
	public function tinymce_upload()
	{
        $path = './berita/';
		if (!is_dir('berita'))
		{
			mkdir('./berita', 0777, true);
		}
		$config['upload_path']		= $path; 
		$config['allowed_types']	= 'jpg|png|jpeg';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 0;
		$config['max_width']		= 0;
		$config['max_height']		= 0;
		$config['file_ext_tolower']	= TRUE;
		$config['remove_spaces'] = TRUE;
		$this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file'))
        {
            $this->output->set_header('HTTP/1.0 500 Server Error');
            exit;
        }
        else
        {
            $file = $this->upload->data();
            $this->output
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['location' => base_url().'berita/'.$file['file_name']]))
                ->_display();
            exit;
        }
    }
    public function sync_download()
    {
    	$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);

		$this->load->library('excel');
		$styleArray = array
		(
			'borders' => array
			(
				'allborders' => array
				(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array
					(
						'argb' => '00000000'
					), 
				), 
			), 
		);
		$fontHeader = array
		( 
			'font' => array
			(
				'bold' => true
			),
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			),
			'fill' => array
			(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '6CCECB')
			)
		);
		$bold = array
		(
			'font' => array
			(
				'bold' => true
			)
		);
		$fontCenter = array
		(
			'alignment' => array
			(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation'   => 0,
			)
		);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("SIMITS")->setDescription("sistem informasi mentoring ITS")->setCreator("ITS")->setLastModifiedBy("ITS");

		if ($semester == 1) $smt = 'GASAL';
		else $smt = 'GENAP';
		$title = 'DAFTAR PESERTA MENTORING ITS - '.$smt.' '.$tahun.'/'.($tahun + 1);
		
		$kelas = $this->kelas->select_kelas($tahun, $semester);

		$i = 0;
		foreach ($kelas as $k)
		{
			if ($i != 0) $objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($i);
			$objPHPExcel->getActiveSheet()->setTitle('TPB-'.$k->NOkelas);

			$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
			$objPHPExcel->getActiveSheet()->setCellValue('A1', $title);
			$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Kelas: TPB-'.$k->NOkelas);
			$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
			$dosen = $this->kelas->select_dosen($k->IDkelas);
			$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dosen: '.$dosen[0]->nama);
			$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->applyFromArray($bold);

			$objPHPExcel->getActiveSheet()->setCellValue('A4', 'No.');
			$objPHPExcel->getActiveSheet()->setCellValue('B4', 'NRP');
			$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Nama');
			$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Jenis Kelamin');

			$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray($fontHeader);
			$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->applyFromArray($styleArray);

			$no = 1;
			$row = 5;

			$peserta = $this->peserta->select_peserta_bykelas($k->IDkelas);

			//error handling, jika ada kelompok yg tidak ada pesertanya
			if (count($peserta))
			{
				foreach ($peserta as $p)
				{
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $no++);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $p->NRPpeserta);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $p->nama);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $p->jeniskelamin);
					$row++;
				}
			}
			else
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, '');
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, '');
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, '');
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, '');
				$row++;
			}

			$objPHPExcel->getActiveSheet()->getStyle('A5:D'.($row - 1))->applyFromArray($styleArray);

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

			$i++;
		}

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="list_mahasiswa.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');
    }
    public function manual()
    {
    	$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Manual Pengguna',
			'module' => 'manual',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
    }
    public function update_manual($role)
    {
    	$path = './asset/userguide/';
		
		$config['upload_path']		= $path; 
		$config['allowed_types']	= 'pdf';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 0;
		$config['max_width']		= 0;
		$config['max_height']		= 0;
		$config['file_ext_tolower']	= TRUE;
		$config['remove_spaces'] = TRUE;

		if ($role == 'mentor')
		{
			$config['file_name'] = 'user_guide_mentor.pdf';
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('mentor'))
			{
				$this->session->set_flashdata('message', $this->upload->display_errors());
				$this->session->set_flashdata('message_bg', 'bg-red');
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$this->session->set_flashdata('message', 'Berhasil mengupdate manual pengguna mentor');
				$this->session->set_flashdata('message_bg', 'bg-green');
			}
		}
		else if ($role == 'pembina')
		{
			$config['file_name'] = 'user_guide_pembina.pdf';
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('pembina'))
			{
				$this->session->set_flashdata('message', $this->upload->display_errors());
				$this->session->set_flashdata('message_bg', 'bg-red');
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$this->session->set_flashdata('message', 'Berhasil mengupdate manual pengguna dosen pembina');
				$this->session->set_flashdata('message_bg', 'bg-green');
			}
		}
		else if ($role == 'dosen')
		{
			$config['file_name'] = 'user_guide_dosen.pdf';
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('dosen'))
			{
				$this->session->set_flashdata('message', $this->upload->display_errors());
				$this->session->set_flashdata('message_bg', 'bg-red');
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$this->session->set_flashdata('message', 'Berhasil mengupdate manual pengguna dosen kelas');
				$this->session->set_flashdata('message_bg', 'bg-green');
			}
		}
		else if ($role == 'admin')
		{
			$config['file_name'] = 'user_guide_admin.pdf';
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('admin'))
			{
				$this->session->set_flashdata('message', $this->upload->display_errors());
				$this->session->set_flashdata('message_bg', 'bg-red');
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$this->session->set_flashdata('message', 'Berhasil mengupdate manual pengguna administrator');
				$this->session->set_flashdata('message_bg', 'bg-green');
			}
		}
				
		redirect('Admin/manual');
    }
    public function cvmentor()
    {
    	$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Template CV Mentor',
			'module' => 'cvmentor',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
    }
    public function cvmentor2()
    {
    	$path = './asset/registration/';
		
		$config['upload_path']		= $path; 
		$config['allowed_types']	= 'word|doc|docx|html';
		$config['overwrite']		= TRUE;
		$config['max_size']			= 0;
		$config['max_width']		= 0;
		$config['max_height']		= 0;
		$config['file_ext_tolower']	= TRUE;
		$config['remove_spaces'] = TRUE;

		$config['file_name'] = 'cv.docx';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('cv'))
		{
			$this->session->set_flashdata('message', $this->upload->display_errors());
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$this->session->set_flashdata('message', 'Berhasil mengupdate template CV mentor');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}
		redirect('Admin/cvmentor');
    }
    public function jadwalregistrasi()
    {
    	$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Jadwal Registrasi Mentor',
			'module' => 'jadwalregistrasi',

			'jadwalregistrasi' => $this->jadwalregistrasi->select(),

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
    }
    public function jadwalregistrasi2()
    {
    	$start = $this->input->post('start');
		$start = $this->security->xss_clean($start);
		$end = $this->input->post('end');
		$end = $this->security->xss_clean($end);

		$this->jadwalregistrasi->update('start', $start);
		$this->jadwalregistrasi->update('end', $end);

		$this->session->set_flashdata('message', 'Berhasil mengupdate jadwal registrasi mentor');
		$this->session->set_flashdata('message_bg', 'bg-green');

		redirect('Admin/jadwalregistrasi');
    }
}