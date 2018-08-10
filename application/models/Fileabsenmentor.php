<?php
class Fileabsenmentor extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_file($tahun, $semester, $mingguke, $NIKdosenpembina, $link)
	{
		if ($this->exist_file($tahun, $semester, $mingguke, $NIKdosenpembina) == false)
		{
			$query = $this->db->query("INSERT INTO simits_fileabsenmentor(tahun, semester, mingguke, nipdosenpembina, link) VALUES (?, ?, ?, ?, ?)", array($tahun, $semester, $mingguke, $NIKdosenpembina, $link));
		}
		else
		{
			$query = $this->db->query("UPDATE simits_fileabsenmentor SET link = ? WHERE tahun = ? AND semester = ? AND mingguke = ? AND nipdosenpembina = ?", array($link, $tahun, $semester, $mingguke, $NIKdosenpembina));
		}
	}
	function select_file($tahun, $semester, $NIKdosenpembina)
	{
		$query = $this->db->query("SELECT mingguke, link FROM simits_fileabsenmentor WHERE tahun = ? AND semester = ? AND nipdosenpembina = ?", array($tahun, $semester, $NIKdosenpembina));
		return $query->result();
	}
	function update_file($tahun, $semester, $mingguke, $NIKdosenpembina, $link)
	{
		if ($this->exist_file($tahun, $semester, $mingguke, $NIKdosenpembina) == true)
		{
			$query = $this->db->query("UPDATE simits_fileabsenmentor SET link = ? WHERE tahun = ? AND semester = ? AND mingguke = ? AND nipdosenpembina = ?", array($link, $tahun, $semester, $mingguke, $NIKdosenpembina));
		}
		else
		{
			$query = $this->db->query("INSERT INTO simits_fileabsenmentor(tahun, semester, mingguke, nipdosenpembina, link) VALUES (?, ?, ?, ?, ?)", array($tahun, $semester, $mingguke, $NIKdosenpembina, $link));
		}
	}
	function delete_file($NIKdosenpembina, $mingguke)
	{
		$query = $this->db->query("DELETE FROM simits_fileabsenmentor WHERE nipdosenpembina = ? AND mingguke = ?", array($NIKdosenpembina, $mingguke));
	}
	function path2file($NIKdosenpembina, $mingguke)
	{
		$query = $this->db->query("SELECT link FROM simits_fileabsenmentor WHERE nipdosenpembina = ? AND mingguke = ?", array($NIKdosenpembina, $mingguke));
		return $query->result();
	}
	function exist_file($tahun, $semester, $mingguke, $NIKdosenpembina)
	{
		$query = $this->db->query("SELECT * FROM simits_fileabsenmentor WHERE tahun = ? AND semester = ? AND mingguke = ? AND nipdosenpembina = ?", array($tahun, $semester, $mingguke, $NIKdosenpembina));
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