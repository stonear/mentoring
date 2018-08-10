<?php
class Jadwal extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_jadwal($mingguke, $IDkelompok)
	{
		if ($this->exist_jadwal($mingguke, $IDkelompok) == false)
		{
			$query = $this->db->query("INSERT INTO simits_jadwal(mingguke, tanggal, IDkelompok) VALUES (?, ?, ?)", array($mingguke, ' ', $IDkelompok));
		}
	}
	function select_jadwal($IDkelompok)
	{
		$query = $this->db->query("SELECT mingguke, tanggal FROM simits_jadwal WHERE IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function update_jadwal($IDkelompok, $mingguke, $tanggal)
	{
		if ($this->exist_jadwal($mingguke, $IDkelompok) == true)
		{
			$query = $this->db->query("UPDATE simits_jadwal SET tanggal = ? WHERE IDkelompok = ? AND mingguke = ?", array($tanggal, $IDkelompok, $mingguke));
		}
		else
		{
			$query = $this->db->query("INSERT INTO simits_jadwal(mingguke, tanggal, IDkelompok) VALUES (?, ?, ?)", array($mingguke, $tanggal, $IDkelompok));
		}
	}
	function exist_jadwal($mingguke, $IDkelompok)
	{
		$query = $this->db->query("SELECT * FROM simits_jadwal WHERE mingguke = ? AND IDkelompok = ?", array($mingguke, $IDkelompok));
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