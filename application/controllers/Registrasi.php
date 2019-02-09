<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('api');
		$this->load->model('jadwalregistrasi');
		$this->load->model('mentor_model');
		$this->load->model('smtmentor');
		$this->load->model('verification');
	}
	public function index()
	{
		$jadwalregistrasi = $this->jadwalregistrasi->select();
		foreach ($jadwalregistrasi as $j)
		{
			if ($j->setting == 'start') $start = DateTime::createFromFormat('d M Y - H:i', $j->tanggal);
			if ($j->setting == 'end') $end = DateTime::createFromFormat('d M Y - H:i', $j->tanggal);
		}
		$now = new DateTime('now');

		if ($now > $start and $now < $end)
		{
			$data = array
			(
				'title' => 'Registrasi',
				'now' => $now
			);
			$this->load->view('registrasi/registrasi',array
			(
				'title' => 'Registrasi'
			));
		}
		else
		{
			$data = array
			(
				'title' => 'Registrasi'
			);
			$this->load->view('registrasi/expired', $data);
		}

	}
	public function get_nama()
	{
		$nrp = $this->input->post('nrp');
		$nrp = $this->security->xss_clean($nrp);
		
		$nama = $this->api->get_data_mhs($nrp);
        echo json_encode($nama);
	}

	public function post()
	{
		$NRPmentor = $this->input->post('nrp');
		$NRPmentor = $this->security->xss_clean($NRPmentor);

		// $nama = $this->input->post('nama');
		// $nama = $this->security->xss_clean($nama);
		
		$nama = $this->api->get_data_mhs($NRPmentor);
		if(empty($nama))
		{
			$data = array
			(
				'title' => 'Registrasi',
				'error' => 'NRP belum terdaftar di Integra'
			);
			$this->load->view('registrasi/registrasi', $data);
			return;
		}
		$nama = $nama[0]->nama_lengkap;

		$jenis = $this->input->post('jenis');
		$jenis = $this->security->xss_clean($jenis);
		if ($jenis == 'Laki-Laki') $jenis_kelamin = 'L';
		else $jenis_kelamin = 'P';

		$no_telp = $this->input->post('telp');
		$no_telp = $this->security->xss_clean($no_telp);
		$email = $this->input->post('email');
		$email = $this->security->xss_clean($email);
		$alamat = $this->input->post('alamat');
		$alamat = $this->security->xss_clean($alamat);

		$nilai = $this->input->post('nilai');
		$nilai = $this->security->xss_clean($nilai);
		$pernah_jadi_mentor = $this->input->post('pernah');
		$pernah_jadi_mentor = $this->security->xss_clean($pernah_jadi_mentor);

		$tahun = $this->input->post('tahun');
		$tahun = $this->security->xss_clean($tahun);
		$semester = $this->input->post('semester');
		$semester = $this->security->xss_clean($semester);

		$error = '';

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
		$config['file_name'] = $NRPmentor.'.docx';
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			$error = $error.'<br>'.$this->upload->display_errors();
		}
		else
		{
			if ($this->mentor_model->exist_mentor($NRPmentor) == false)
			{
				$cv = base_url().'cv/'.$NRPmentor.'.docx';
				$password = password_hash($NRPmentor, PASSWORD_BCRYPT);
				$linkfoto = NULL;
				$kode_verifikasi = md5($NRPmentor);
				$verified = 0;
				$this->mentor_model->registrasi($NRPmentor, $nama, $jenis_kelamin, $no_telp, $email, $alamat, $pernah_jadi_mentor, $cv, $password, $linkfoto, $kode_verifikasi, $verified, $nilai);

				$this->smtmentor->registrasi($NRPmentor, $tahun, $semester);

				$response = $this->verification->send_verification($email, $nama, $kode_verifikasi, $NRPmentor);
				if ($response == 'Success')
				{
					$data = array
					(
						'title' => 'No Reply'
					);
					$this->load->view('registrasi/noreply', $data);
					return;
				}
				else
				{
					$error = $error.'<br>'.$response;
					$data = array
					(
						'title' => 'No Reply',
						'error' => $error
					);
					$this->load->view('registrasi/noreply2', $data);
					return;
				}
			}
			else $error = $error.'<br>Mentor sudah pernah terdaftar pada sistem';
		}
		// echo $error;
		$data = array
		(
			'title' => 'Registrasi',
			'error' => $error
		);
		$this->load->view('registrasi/registrasi', $data);
	}

	public function verifikasi($nrp = "", $token = "")
	{
		$response = $this->verification->check_verification($nrp, $token);
		$this->session->sess_destroy();
		if ($response == 'Success')
		{
			$data = array
			(
				'title' => 'Verifikasi',
				'error' => 0
			);
			$this->load->view('registrasi/verified', $data);
		}
		else
		{
			$data = array
			(
				'title' => 'Verifikasi',
				'error' => 1
			);
			$this->load->view('registrasi/verified', $data);
		}
	}
	public function verify()
	{
		$data = array
		(
			'title' => 'Belum Terverifikasi'
		);
		$this->load->view('registrasi/verify', $data);
	}
	public function resend($nrp = "")
	{
		$mentor = $this->mentor_model->select_mentor_byNRP($nrp);
		foreach ($mentor as $m)
		{
			$kode_verifikasi = md5($m->kode_verifikasi);
			$this->verification->update_kode($nrp, $kode_verifikasi);
			$response = $this->verification->send_verification($m->email, $m->nama, $kode_verifikasi, $nrp);
			if ($response == 'Success')
			{
				$data = array
				(
					'title' => 'No Reply'
				);
				$this->load->view('registrasi/noreply', $data);
				return;
			}
			else
			{
				$error = $error.'<br>'.$response;
				$data = array
				(
					'title' => 'No Reply',
					'error' => $error
				);
				$this->load->view('registrasi/noreply2', $data);
				return;
			}
		}
	}
}
