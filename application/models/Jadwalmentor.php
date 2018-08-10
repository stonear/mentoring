<?php
class Jadwalmentor extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_jadwal($tahun, $semester, $mingguke, $NIKdosenpembina)
	{
		if ($this->exist_jadwal($tahun, $semester, $mingguke, $NIKdosenpembina) == false)
		{
			$query = $this->db->query("INSERT INTO simits_jadwalmentor(tahun, semester, mingguke, NIKdosenpembina, tanggal) VALUES (?, ?, ?, ?, ?)", array($tahun, $semester, $mingguke, $NIKdosenpembina, ' '));
		}
	}
	function select_jadwal($tahun, $semester, $NIKdosenpembina)
	{
		$query = $this->db->query("SELECT mingguke, tanggal FROM simits_jadwalmentor WHERE tahun = ? AND semester = ? AND NIKdosenpembina = ?", array($tahun, $semester, $NIKdosenpembina));
		return $query->result();
	}
	function update_jadwal($tahun, $semester, $mingguke, $NIKdosenpembina, $tanggal)
	{
		if ($this->exist_jadwal($tahun, $semester, $mingguke, $NIKdosenpembina) == true)
		{
			$query = $this->db->query("UPDATE simits_jadwalmentor SET tanggal = ? WHERE tahun = ? AND semester = ? AND mingguke = ? AND NIKdosenpembina = ?", array($tanggal, $tahun, $semester, $mingguke, $NIKdosenpembina));
		}
		else
		{
			$query = $this->db->query("INSERT INTO simits_jadwalmentor(tahun, semester, mingguke, NIKdosenpembina, tanggal) VALUES (?, ?, ?, ?, ?)", array($tahun, $semester, $mingguke, $NIKdosenpembina, $tanggal));
		}
	}
	function exist_jadwal($tahun, $semester, $mingguke, $NIKdosenpembina)
	{
		$query = $this->db->query("SELECT * FROM simits_jadwalmentor WHERE tahun = ? AND semester = ? AND mingguke = ? AND NIKdosenpembina = ?", array($tahun, $semester, $mingguke, $NIKdosenpembina));
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