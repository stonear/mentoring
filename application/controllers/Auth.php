<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->model('database');
	}
	public function index()
	{
		if($this->session->userdata('status') == 'login')
		{
			redirect($this->session->userdata('role'));
		}
		else
		{
			$data = array
			(
				'title' => 'Login'
			);
			$this->load->view('login', $data);
		}
	}
	public function notfound()
	{
		$data = array
		(
			'title' => '404'
		);
		$this->load->view('404', $data);
	}
	public function auth()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$admin = $this->database->auth_admin($username);
		if(count($admin) > 0)
		{
			if (password_verify($password, $admin[0]->password))
			{
				$data_session = array
				(
					'nama' => $admin[0]->username,
					'nrp' => 'Administrator',
					'status' => 'login',
					'role' => 'Admin'
				);
				$this->session->set_userdata($data_session);
				redirect('Admin');
			}
			else redirect('Auth');
		}
		$mentor = $this->database->auth_mentor($username);
		if(count($mentor) > 0)
		{
			if (password_verify($password, $mentor[0]->password))
			{
				$data_session = array
				(
					'nama' => $mentor[0]->nama,
					'nrp' => $mentor[0]->NRPmentor,
					'status' => 'login',
					'role' => 'Mentor',
					'verified' => $mentor[0]->verified,

					'password' => $mentor[0]->password
				);
				$this->session->set_userdata($data_session);
				redirect('Mentor');
			}
			else redirect('Auth');
		}
		$dosen = $this->database->auth_dosen($username);
		if(count($dosen) > 0)
		{
			if (password_verify($password, $dosen[0]->password))
			{
				$pembina = $this->database->auth_pembina($username);
				if(count($pembina) > 0)
				{
					if (password_verify($password, $pembina[0]->password))
					{
						$this->session->set_userdata('nama', $dosen[0]->nama);
						$this->session->set_userdata('nrp', $dosen[0]->NIKdosen);
						$this->session->set_userdata('status', 'login');
						redirect('Auth/role');
					}
				}
				$data_session = array
				(
					'nama' => $dosen[0]->nama,
					'nrp' => $dosen[0]->NIKdosen,
					'status' => 'login',
					'role' => 'Dosen'
				);
				$this->session->set_userdata($data_session);
				redirect('Dosen');
			}
			else redirect('Auth');
		}
		$pembina = $this->database->auth_pembina($username);
		if(count($pembina) > 0)
		{
			if (password_verify($password, $pembina[0]->password))
			{
				$data_session = array
				(
					'nama' => $pembina[0]->nama,
					'nrp' => $pembina[0]->NIKdosenpembina,
					'status' => 'login',
					'role' => 'Pembina'
				);
				$this->session->set_userdata($data_session);
				redirect('Pembina');
			}
			else redirect('Auth');
		}
		redirect('Auth');
	}
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('Auth');
	}
	public function role()
	{
		$status = $this->session->userdata('status');
		if ($status == 'login')
		{
			$this->load->view('role');
		}
		else redirect('Auth');
	}
	public function select_role($role = '')
	{
		$status = $this->session->userdata('status');
		if ($status == 'login')
		{
			if ($role == 'pembina')
			{
				$this->session->set_userdata('role', 'Pembina');
				redirect('Pembina');
			}
			else if ($role == 'dosen')
			{
				$this->session->set_userdata('role', 'Dosen');
				redirect('Dosen');
			}
			else redirect('Auth/role');
		}
		else redirect('Auth');
	}
}
