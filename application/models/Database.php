<?php
class Database extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function up()
	{
		$query1 = $this->db->query("INSERT INTO simits_admin(username, password) VALUES (?, ?)", array('aoko', password_hash('mentor1ngITS!', PASSWORD_BCRYPT)));
		$query2 = $this->db->query("INSERT INTO simits_dosen(NIKdosen, password) VALUES (?, ?)", array('dosen', password_hash('dosen', PASSWORD_BCRYPT)));
		$query3 = $this->db->query("INSERT INTO simits_dosenpembina(NIKdosenpembina, password) VALUES (?, ?)", array('pembina', password_hash('pembina', PASSWORD_BCRYPT)));
		$query4 = $this->db->query("INSERT INTO simits_mentor(NRPmentor, password, verified) VALUES (?, ?, ?)", array('mentor', password_hash('mentor', PASSWORD_BCRYPT), 1));
	}
	function auth_admin($username)
	{
		$query = $this->db->query("SELECT * FROM simits_admin WHERE username = ?", array($username));
		return $query->result();
	}
	function auth_mentor($username)
	{
		$query = $this->db->query("SELECT * FROM simits_mentor WHERE NRPmentor = ?", array($username));
		return $query->result();
	}
	function auth_dosen($username)
	{
		$query = $this->db->query("SELECT * FROM simits_dosen WHERE NIKdosen = ?", array($username));
		return $query->result();
	}
	function auth_pembina($username)
	{
		$query = $this->db->query("SELECT * FROM simits_dosenpembina WHERE NIKdosenpembina = ?", array($username));
		return $query->result();
	}
	function select_password_mentor($NRPmentor)
	{
		$query = $this->db->query("SELECT password FROM simits_mentor WHERE NRPmentor = ?", array($NRPmentor));
		return $query->result();
	}
	function update_password_mentor($NRPmentor, $password)
	{
		$query = $this->db->query("UPDATE simits_mentor SET password = ? WHERE NRPmentor = ?", array(password_hash($password, PASSWORD_BCRYPT), $NRPmentor));
	}
	function select_password_pembina($NIKdosenpembina)
	{
		$query = $this->db->query("SELECT password FROM simits_dosenpembina WHERE NIKdosenpembina = ?", array($NIKdosenpembina));
		return $query->result();
	}
	function update_password_pembina($NIKdosenpembina, $password)
	{
		$query = $this->db->query("UPDATE simits_dosenpembina SET password = ? WHERE NIKdosenpembina = ?", array(password_hash($password, PASSWORD_BCRYPT), $NIKdosenpembina));
	}
	function select_password_dosen($NIKdosen)
	{
		$query = $this->db->query("SELECT password FROM simits_dosen WHERE NIKdosen = ?", array($NIKdosen));
		return $query->result();
	}
	function update_password_dosen($NIKdosen, $password)
	{
		$query = $this->db->query("UPDATE simits_dosen SET password = ? WHERE NIKdosen = ?", array(password_hash($password, PASSWORD_BCRYPT), $NIKdosen));
	}
}
?>