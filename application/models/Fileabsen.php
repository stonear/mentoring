<?php
class Fileabsen extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_file($mingguke, $IDkelompok, $link)
	{
		if ($this->exist_file($mingguke, $IDkelompok) == false)
		{
			$query = $this->db->query("INSERT INTO simits_fileabsen(mingguke, IDkelompok, linkfile) VALUES (?, ?, ?)", array($mingguke, $IDkelompok, $link));
		}
		else
		{
			$query = $this->db->query("UPDATE simits_fileabsen SET linkfile = ? WHERE IDkelompok = ? AND mingguke = ?", array($link, $IDkelompok, $mingguke));
		}
	}
	function select_file($IDkelompok)
	{
		$query = $this->db->query("SELECT mingguke, linkfile FROM simits_fileabsen WHERE IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function update_file($IDkelompok, $mingguke, $link)
	{
		if ($this->exist_file($mingguke, $IDkelompok) == true)
		{
			$query = $this->db->query("UPDATE simits_fileabsen SET linkfile = ? WHERE IDkelompok = ? AND mingguke = ?", array($link, $IDkelompok, $mingguke));
		}
		else
		{
			$query = $this->db->query("INSERT INTO simits_fileabsen(mingguke, IDkelompok, linkfile) VALUES (?, ?, ?)", array($mingguke, $IDkelompok, $link));
		}
	}
	function delete_file($IDkelompok, $mingguke)
	{
		$query = $this->db->query("DELETE FROM simits_fileabsen WHERE IDkelompok = ? AND mingguke = ?", array($IDkelompok, $mingguke));
	}
	function path2file($IDkelompok, $mingguke)
	{
		$query = $this->db->query("SELECT linkfile FROM simits_fileabsen WHERE IDkelompok = ? AND mingguke = ?", array($IDkelompok, $mingguke));
		return $query->result();
	}
	function exist_file($mingguke, $IDkelompok)
	{
		$query = $this->db->query("SELECT * FROM simits_fileabsen WHERE mingguke = ? AND IDkelompok = ?", array($mingguke, $IDkelompok));
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