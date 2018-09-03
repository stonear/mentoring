<?php
class Verification extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function send_verification($email, $nama, $token, $nrp)
	{
		$config = Array(  
		    'protocol' => 'smtp',  
		    'smtp_host' => 'ssl://smtp.googlemail.com',  
		    'smtp_port' => 465,  
		    'smtp_user' => 'mentoring.its.ac.id@gmail.com',   
		    'smtp_pass' => 'mentor1ngITS!',   
		    'mailtype' => 'html',
		    'charset' => 'iso-8859-1'  
	   );  
		$this->load->library('email', $config);  
		$this->email->set_newline("\r\n");  
		$this->email->from('mentoring.its.ac.id@gmail.com', 'No Reply ~ Mentoring ITS');   
		$this->email->to($email);   
		$this->email->subject('Verifikasi Akun Mentoring ITS');   
		$this->email->message('Halo '.$nama.', mohon verifikasi alamat email anda dengan cara klik tautan berikut ini.<br><a href="'.base_url('Registrasi/verifikasi/'.$nrp.'/'.$token).'"><b>Klik di sini</b></a><br>Username: '.$nrp.'<br>Password: '.$nrp.'<br>Email ini dikirimkan secara otomatis. Mohon tidak membalas ke email ini.');
	   	if (!$this->email->send())
		{  
			return $this->email->print_debugger();   
		}
		else
		{  
			return 'Success';
		}
	}
	function check_verification($nrp, $token)
	{
		$query = $this->db->query("SELECT NRPmentor FROM simits_mentor WHERE NRPmentor = ? AND kode_verifikasi = ?", array($nrp, $token));
		$result = $query->result();
		if (count($result) > 0)
		{
			foreach ($result as $r)
			{
				$this->verify($r->NRPmentor);
			}
			return 'Success';
		}
		else return 'Failed';
	}
	function verify($id)
	{
		$query = $this->db->query("UPDATE simits_mentor SET verified = ?, kode_verifikasi = ? WHERE NRPmentor = ?", array(1, NULL, $id));
	}
	function update_kode($nrp, $kode_verifikasi)
	{
		$query = $this->db->query("UPDATE simits_mentor SET kode_verifikasi = ? WHERE NRPmentor = ?", array($kode_verifikasi, $nrp));
	}
}
?>