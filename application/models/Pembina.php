<?php
class Pembina extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function select_pembina()
	{
		$query = $this->db->query("SELECT NIKdosenpembina, nama FROM simits_dosenpembina");
		return $query->result();
	}
	function select_pembina_bynik($nik)
	{
		$query = $this->db->query("SELECT nama FROM simits_dosenpembina WHERE NIKdosenpembina = ?", array($nik));
		return $query->result();
	}
	function select_pembina_bytahunsemester($tahun, $semester)
	{
		$query = $this->db->query("SELECT DISTINCT p.NIKdosenpembina AS nik, p.nama AS nama FROM simits_dosenpembina AS p, simits_kelompokmentoring AS km, simits_kelas AS k WHERE p.NIKdosenpembina = km.NIKdosenpembina AND km.IDkelas = k.IDkelas AND k.tahun = ? AND k.semester = ?", array($tahun, $semester));
		return $query->result();
	}
	function create_pembina($nik, $nama)
	{
		$query = $this->db->query("INSERT INTO simits_dosenpembina(NIKdosenpembina, nama , password) VALUES (?, ?, ?)", array($nik, $nama, password_hash($nik, PASSWORD_BCRYPT)));
	}
	function update_passwordpembina($nik, $password)
	{
		$query = $this->db->query("UPDATE simits_dosenpembina SET password = ? WHERE NIKdosenpembina = ?", array(password_hash($password, PASSWORD_BCRYPT), $nik));
	}
	function delete_pembina($nik)
	{
		$query = $this->db->query("DELETE FROM simits_dosenpembina WHERE NIKdosenpembina = ?", array($nik));
	}
	function exist_pembina($nik)
	{
		$query = $this->db->query("SELECT * FROM simits_dosenpembina WHERE NIKdosenpembina = ?", array($nik));
		if ($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>