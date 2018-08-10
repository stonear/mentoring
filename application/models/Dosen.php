<?php
class Dosen extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_dosen($NIKdosen, $nama)
	{
		if ($this->exist_dosen($NIKdosen) == false)
		{
			$query = $this->db->query("INSERT INTO simits_dosen(NIKdosen, nama, password) VALUES (?, ?, ?)", array($NIKdosen, $nama, password_hash($NIKdosen, PASSWORD_BCRYPT)));
		}
	}
	function exist_dosen($NIKdosen)
	{
		$query = $this->db->query("SELECT * FROM simits_dosen WHERE NIKdosen = ?", array($NIKdosen));
		if ($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function update_password($NIKdosen, $password)
	{
		$query = $this->db->query("UPDATE simits_dosen SET password = ? WHERE NIKdosen = ?", array(password_hash($password, PASSWORD_BCRYPT), $NIKdosen));
	}
}
?>