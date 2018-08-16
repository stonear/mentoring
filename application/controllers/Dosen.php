<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends CI_Controller
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
		$this->load->model('nilai');
		$this->load->model('pertemuan');
		$this->load->model('peserta');

		if($this->session->userdata('status') != 'login' or $this->session->userdata('role') != 'Dosen')
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
	public function presensi()
	{
		$dosen = $this->session->userdata('nrp');

		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$kelas = $this->input->post('kelas');
		$kelas = $this->security->xss_clean($kelas);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_kelas = $this->session->flashdata('kelas');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_kelas)) $kelas = $temp_kelas;

		$peserta = $this->peserta->select_peserta_bykelas($kelas);
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
			'kelas' => $this->kelas->select_kelas_dosen($tahun, $semester, $dosen),

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,
			'kelas_selected' => $kelas,

			'peserta' => $peserta,
			'jumlahpertemuan' => $jumlahpertemuan,
			'presensi' => $this->absen->select_absen_bykelas($kelas)
		);
		$this->load->view('master-layout', $data);
	}
	public function download_presensi($kelas)
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

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Dosen Agama:');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($bold);

		$objPHPExcel->getActiveSheet()->setCellValue('B2', $this->data['nama']);

		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('B3', 'NAMA');
		$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Pertemuan ke-');
		$objPHPExcel->getActiveSheet()->mergeCells('C3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'3');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
		$objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'4')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + $jumlahpertemuan[0]->jumlahpertemuan).'4')->applyFromArray($styleArray);

		$k = 1;
		for ($i = ord('C'); $i < (ord('C') + $jumlahpertemuan[0]->jumlahpertemuan); $i++)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(chr($i).'4', $k++);
		}
		
		$peserta = $this->peserta->select_peserta_bykelas($kelas);
		$presensi = $this->absen->select_absen_bykelas($kelas);
		$k = 5;
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
	public function penilaian()
	{
		$dosen = $this->session->userdata('nrp');

		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		$kelas = $this->input->post('kelas');
		$kelas = $this->security->xss_clean($kelas);

		$temp_tahun = $this->session->flashdata('tahun');
		$temp_semester = $this->session->flashdata('semester');
		$temp_kelas = $this->session->flashdata('kelas');
		
		if (isset($temp_tahun)) $tahun = $temp_tahun;
		if (isset($temp_semester)) $semester = $temp_semester;
		if (isset($temp_kelas)) $kelas = $temp_kelas;

		$peserta = $this->peserta->select_peserta_bykelas($kelas);
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
			'kelas' => $this->kelas->select_kelas_dosen($tahun, $semester, $dosen),

			'tahun_selected' => $tahun,
			'semester_selected' => $semester,
			'kelas_selected' => $kelas,

			'peserta' => $peserta,
			'aspekpenilaian' => $aspekpenilaian,
			'nilai' => $this->nilai->select_nilai_bykelas($kelas)
		);
		$this->load->view('master-layout', $data);
	}
	public function download_penilaian($kelas)
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

		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Dosen Agama:');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($bold);

		$objPHPExcel->getActiveSheet()->setCellValue('B2', $this->data['nama']);

		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'NRP');
		$objPHPExcel->getActiveSheet()->setCellValue('B3', 'NAMA');
		$k = ord('C');
		foreach ($aspekpenilaian as $a)
		{
			$objPHPExcel->getActiveSheet()->setCellValue(chr($k).'3', $a->namanilai);
			$k++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue(chr($k).'3', 'Rata-rata');
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + count($aspekpenilaian) + 1).'3')->applyFromArray($fontHeader);
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.chr(ord('A') + 1 + count($aspekpenilaian) + 1).'3')->applyFromArray($styleArray);

		$peserta = $this->peserta->select_peserta_bykelas($kelas);
		$nilai = $this->nilai->select_nilai_bykelas($kelas);
		$b = 4;
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
		redirect('Dosen/password');
	}
	public function get_kelas()
	{
		$dosen = $this->session->userdata('nrp');

		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);
		
		$kelas = $this->kelas->select_kelas_dosen($tahun, $semester, $dosen);
        echo json_encode($kelas);
	}
}
