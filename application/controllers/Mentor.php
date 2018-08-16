<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mentor extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->model('absen');
		$this->load->model('berita');
		$this->load->model('database');
		$this->load->model('fileabsen');
		$this->load->model('jadwal');
		$this->load->model('kelas');
		$this->load->model('kelompok');
		$this->load->model('masternilai');
		$this->load->model('mentor_model');
		$this->load->model('nilai');
		$this->load->model('pertemuan');
		$this->load->model('peserta');

		if($this->session->userdata('status') != 'login' or $this->session->userdata('role') != 'Mentor')
		{
      		redirect('Auth');
    	}
    	
		$this->data['nama'] = $this->session->userdata('nama');
		$this->data['nrp'] = $this->session->userdata('nrp');
		$this->data['role'] = $this->session->userdata('role');

		if($this->session->userdata('verified') != 1)
		{
      		redirect('Registrasi/verify');
    	}
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

			'berita' => $this->berita->select_berita(5),

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
		$kelompok = $this->input->post('kelompok');
		$kelompok = $this->security->xss_clean($kelompok);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_kelompok = $this->session->flashdata('kelompok');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_kelompok)) $kelompok = $temp_kelompok;

		$peserta = $this->peserta->select_peserta_bykelompok($kelompok);
		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);
		if (!empty($jumlahpertemuan)) $jumlahpertemuan = $jumlahpertemuan[0]->jumlahpertemuan;
		else $jumlahpertemuan = 0;

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
			'kelompok' => $this->kelompok->select_IDkelompok_bymentor($tahun, $semester, $this->data['nrp']),

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,
			'kelompok_selected' => $kelompok,

			'peserta' => $peserta,
			'jumlahpertemuan' => $jumlahpertemuan,
			'presensi' => $this->absen->select_absen($kelompok),
			'jadwal' => $this->jadwal->select_jadwal($kelompok),
			'file' => $this->fileabsen->select_file($kelompok)
		);
		$this->load->view('master-layout', $data);
	}
	public function update_presensi($tahun, $semester, $kelompok)
	{
		$peserta = $this->peserta->select_peserta_bykelompok($kelompok);
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
		for ($i = 1; $i <= $jumlahpertemuan; $i++)
		{
			$tanggal = $this->input->post($i);
			$this->jadwal->update_jadwal($kelompok, $i, $tanggal);
		}

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);
		$this->session->set_flashdata('kelompok', $kelompok);

		$this->session->set_flashdata('message', 'Berhasil memperbarui presensi');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Mentor/presensi');
	}
	public function tambah_filepresensi($tahun, $semester, $kelompok)
	{
		$mingguke = $this->input->post('mingguke');
		$mingguke = $this->security->xss_clean($mingguke);

		$kelas = $this->kelompok->select_kelas($kelompok);
		$kelas = $kelas[0]->IDkelas;

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
		$this->session->set_flashdata('kelompok', $kelompok);
		redirect('Mentor/presensi');
	}
	public function hapus_filepresensi($tahun, $semester, $kelompok, $mingguke)
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
		$this->session->set_flashdata('kelompok', $kelompok);
		redirect('Mentor/presensi');
	}
	public function download_filepresensi($kelompok)
	{
		$kelas = $this->kelompok->select_kelas($kelompok);
		$kelas = $kelas[0]->IDkelas;

		$this->load->library('zip');
		$path = FCPATH.'/uploads/'.$kelas.'/'.$kelompok.'/';
		$this->zip->read_dir($path, FALSE);
		$this->zip->download('presensi.zip');
	}
	public function download_presensi($kelompok)
	{
		$kelas = $this->kelompok->select_kelas($kelompok);
		$kelas = $kelas[0]->IDkelas;

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
	public function download_templatepresensi($kelompok)
	{
		$kelas = $this->kelompok->select_kelas($kelompok);
		$kelas = $kelas[0]->IDkelas;

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
	public function penilaian()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$kelompok = $this->input->post('kelompok');
		$kelompok = $this->security->xss_clean($kelompok);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_kelompok = $this->session->flashdata('kelompok');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_kelompok)) $kelompok = $temp_kelompok;

		$peserta = $this->peserta->select_peserta_bykelompok($kelompok);
		$aspekpenilaian = $this->masternilai->select_masternilai();

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
			'kelompok' => $this->kelompok->select_IDkelompok_bymentor($tahun, $semester, $this->data['nrp']),

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,
			'kelompok_selected' => $kelompok,

			'peserta' => $peserta,
			'aspekpenilaian' => $aspekpenilaian,
			'nilai' => $this->nilai->select_nilai($kelompok)
		);
		$this->load->view('master-layout', $data);
	}
	public function update_penilaian($tahun, $semester, $kelompok)
	{
		$peserta = $this->peserta->select_peserta_bykelompok($kelompok);
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
		$this->session->set_flashdata('kelompok', $kelompok);

		$this->session->set_flashdata('message', 'Berhasil memperbarui nilai');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Mentor/penilaian');
	}
	public function download_penilaian($kelompok)
	{
		$kelas = $this->kelompok->select_kelas($kelompok);
		$kelas = $kelas[0]->IDkelas;
		
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
	public function password()
	{
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Password',
			'module' => 'password',

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function password2()
	{
		$password1 = $this->input->post('password1');
		$password1 = $this->security->xss_clean($password1);
		$password2 = $this->input->post('password2');
		$password2 = $this->security->xss_clean($password2);
		$password3 = $this->input->post('password3');
		$password3 = $this->security->xss_clean($password3);

		$password_DB = $this->database->select_password_mentor($this->data['nrp']);
		if (password_verify($password1, $password_DB[0]->password))
		{
			if ($password2 == $password3)
			{
				$this->database->update_password_mentor($this->data['nrp'], $password2);
				$this->session->set_flashdata('message', 'Password berhasil diperbarui');
				$this->session->set_flashdata('message_bg', 'bg-green');
			}
			else
			{
				$this->session->set_flashdata('message', 'Password baru tidak sesuai');
				$this->session->set_flashdata('message_bg', 'bg-red');
			}
		}
		else
		{
			$this->session->set_flashdata('message', 'Password lama tidak sesuai');
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		redirect('Mentor/password');
	}
	public function get_kelompok()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		
		$kelompok = $this->kelompok->select_IDkelompok_bymentor($tahun, $semester, $this->data['nrp']);
        echo json_encode($kelompok);
	}
	public function profil()
	{
		$mentor = $this->mentor_model->select_mentor_byNRP($this->data['nrp']);
		$data = array
		(
			'nama' => $this->data['nama'],
			'nrp' => $this->data['nrp'],
			'role' => $this->data['role'],
			'title' => 'Profil',
			'module' => 'profil',
			'mentor' => $mentor,

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function update_foto()
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
		$config['file_name'] = $this->data['nrp'].'.jpg';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('img'))
		{
			$this->session->set_flashdata('message', $this->upload->display_errors());
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$this->mentor_model->update_foto($this->data['nrp'], base_url().'photo/'.$data['upload_data']['file_name']);
			$this->session->set_flashdata('message', 'Berhasil mengupdate foto profil<br>Apabila foto belum berubah, silahkan refresh browser anda');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}
		redirect('Mentor/profil');
	}
	public function update_cv()
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
		$config['file_name'] = $this->data['nrp'].'.docx';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('cv'))
		{
			$this->session->set_flashdata('message', $this->upload->display_errors());
			$this->session->set_flashdata('message_bg', 'bg-red');
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$this->mentor_model->update_cv($this->data['nrp'], base_url().'cv/'.$data['upload_data']['file_name']);
			$this->session->set_flashdata('message', 'Berhasil mengupdate CV');
			$this->session->set_flashdata('message_bg', 'bg-green');
		}
		redirect('Mentor/profil');
	}
	public function update_profil()
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

		$this->mentor_model->update_profil($this->data['nrp'], $jenis_kelamin, $no, $email, $alamat, $pernah, $nilai);
		$this->session->set_flashdata('message', 'Berhasil mengupdate profil');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Mentor/profil');
	}
}
