<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembina extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->model('absen');
		$this->load->model('absenmentor');
		$this->load->model('berita');
		$this->load->model('database');
		$this->load->model('fileabsen');
		$this->load->model('fileabsenmentor');
		$this->load->model('jadwal');
		$this->load->model('jadwalmentor');
		$this->load->model('kelas');
		$this->load->model('kelompok');
		$this->load->model('masternilai');
		$this->load->model('mentor_model');
		$this->load->model('nilai');
		$this->load->model('pertemuan');
		$this->load->model('peserta');
		
		if($this->session->userdata('status') != 'login' or $this->session->userdata('role') != 'Pembina')
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

			'berita' => $this->berita->select_berita(5),

			'message' => $this->session->flashdata('message'),
			'message_bg' => $this->session->flashdata('message_bg')
		);
		$this->load->view('master-layout', $data);
	}
	public function presensimentor()
	{
		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);

		$pembina =$this->session->userdata('nrp');

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;

		$mentor = $this->mentor_model->select_mentor_bypembina($tahun, $semester, $pembina);
		$jumlahpertemuan = $this->pertemuan->select_jumlahpertemuan($tahun, $semester);
		if (!empty($jumlahpertemuan)) $jumlahpertemuan = $jumlahpertemuan[0]->jumlahpertemuan;
		else $jumlahpertemuan = 0;

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
	public function update_presensimentor($tahun, $semester)
	{
		$pembina =$this->session->userdata('nrp');

		$mentor = $this->mentor_model->select_mentor_bypembina($tahun, $semester, $pembina);
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
		for ($i = 1; $i <= $jumlahpertemuan; $i++)
		{
			$tanggal = $this->input->post($i);
			$this->jadwalmentor->update_jadwal($tahun, $semester, $i, $pembina, $tanggal);
		}

		$this->session->set_flashdata('tahun', $tahun);
		$this->session->set_flashdata('semester', $semester);

		$this->session->set_flashdata('message', 'Berhasil memperbarui presensi');
		$this->session->set_flashdata('message_bg', 'bg-green');
		redirect('Pembina/presensimentor');
	}
	public function hapus_filepresensimentor($tahun, $semester, $mingguke)
	{
		$pembina =$this->session->userdata('nrp');

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
		redirect('Pembina/presensimentor');
	}
	public function tambah_filepresensimentor($tahun, $semester)
	{
		$pembina =$this->session->userdata('nrp');

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
		redirect('Pembina/presensimentor');
	}
	public function download_filepresensimentor($tahun, $semester)
	{
		$pembina =$this->session->userdata('nrp');

		$this->load->library('zip');
		$path = FCPATH.'/uploads2/'.$tahun.'/'.$semester.'/'.$pembina.'/';
		$this->zip->read_dir($path, FALSE);
		$this->zip->download('presensi.zip');
	}
	public function download_presensimentor($tahun, $semester)
	{
		$pembina =$this->session->userdata('nrp');

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

		// $namapembina = $this->pembina->select_pembina_bynik($pembina);
		$namapembina = $this->session->userdata('nama');
		// $objPHPExcel->getActiveSheet()->setCellValue('B2', $namapembina[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $namapembina);

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
	public function download_templatepresensimentor($tahun, $semester)
	{
		$pembina =$this->session->userdata('nrp');

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

		// $namapembina = $this->pembina->select_pembina_bynik($pembina);
		$namapembina = $this->session->userdata('nama');
		// $objPHPExcel->getActiveSheet()->setCellValue('B2', $namapembina[0]->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('B2', $namapembina);

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
	public function presensipeserta()
	{
		$pembina = $this->session->userdata('nrp');

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
				'title' => 'Presensi Peserta',
				'module' => 'presensi',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas_pembina($tahun, $semester, $pembina),
				'kelompok' => $this->kelompok->select_kelompok_pembina($kelas, $pembina),

				'tahun_selected' => $tahun,
				'semester_selected' => $semester,
				'kelas_selected' => $kelas,
				'kelompok_selected' => $kelompok,

				'peserta' => $peserta,
				'jumlahpertemuan' => $jumlahpertemuan,
				'presensi' => $this->absen->select_absen_bykelas($kelas),
				'jadwal' => -1,
				'file' => -1,
				'mentor' => $this->kelompok->select_mentor($kelompok)
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
				'title' => 'Presensi Peserta',
				'module' => 'presensi',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas_pembina($tahun, $semester, $pembina),
				'kelompok' => $this->kelompok->select_kelompok_pembina($kelas, $pembina),

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
	public function update_presensipeserta($tahun, $semester, $kelas, $kelompok)
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
		redirect('Pembina/presensipeserta');
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
		redirect('Pembina/presensipeserta');
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
		redirect('Pembina/presensipeserta');
	}
	public function download_filepresensi($kelas, $kelompok)
	{
		$this->load->library('zip');
		$path = FCPATH.'/uploads/'.$kelas.'/'.$kelompok.'/';
		$this->zip->read_dir($path, FALSE);
		$this->zip->download('presensi.zip');
	}
	public function download_presensipeserta($kelas, $kelompok)
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
	public function download_templatepresensipeserta($kelas, $kelompok)
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
	public function penilaianpeserta()
	{
		$pembina = $this->session->userdata('nrp');

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
				'title' => 'Penilaian Peserta',
				'module' => 'penilaian',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas_pembina($tahun, $semester, $pembina),
				'kelompok' => $this->kelompok->select_kelompok_pembina($kelas, $pembina),

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
				'title' => 'Penilaian Peserta',
				'module' => 'penilaian',

				'message' => $this->session->flashdata('message'),
				'message_bg' => $this->session->flashdata('message_bg'),

				'tahun' => $this->kelas->select_tahun(),
				'kelas' => $this->kelas->select_kelas_pembina($tahun, $semester, $pembina),
				'kelompok' => $this->kelompok->select_kelompok_pembina($kelas, $pembina),

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
		redirect('Pembina/penilaianpeserta');
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

		$password_DB = $this->database->select_password_pembina($this->data['nrp']);
		if (password_verify($password1, $password_DB[0]->password))
		{
			if ($password2 == $password3)
			{
				$this->database->update_password_pembina($this->data['nrp'], $password2);
				$this->database->update_password_dosen($this->data['nrp'], $password2);
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
		redirect('Pembina/password');
	}
	public function get_kelas()
	{
		$pembina = $this->session->userdata('nrp');

		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		
		$kelas = $this->kelas->select_kelas_pembina($tahun, $semester, $pembina);
        echo json_encode($kelas);
	}
	public function get_kelompok()
	{
		$pembina = $this->session->userdata('nrp');

		$kelas = $this->input->post('kelas');
		$kelas = $this->security->xss_clean($kelas);
		
		$kelompok = $this->kelompok->select_kelompok_pembina($kelas, $pembina);
        echo json_encode($kelompok);
	}
}
