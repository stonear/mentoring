<?php
class Absenmentor extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_absen($tahun, $semester, $mingguke, $NRPmentor)
	{
		if ($this->exist_absen($tahun, $semester, $mingguke, $NRPmentor) == false)
		{
			$query = $this->db->query("INSERT INTO simits_absenmentor(tahun, semester, mingguke, statuskehadiran, nrpmentor) VALUES (?, ?, ?, ?, ?)", array($tahun, $semester, $mingguke, 0, $NRPmentor));
		}
	}
	function select_absen($tahun, $semester, $pembina)
	{
		$query = $this->db->query("SELECT DISTINCT a.nrpmentor AS nrp, a.mingguke AS mingguke, a.statuskehadiran AS status FROM simits_absenmentor AS a, simits_mentor AS m, simits_kelompokmentoring AS km, simits_kelas AS k WHERE a.nrpmentor = m.NRPmentor AND m.NRPmentor = km.NRPmentor AND km.IDkelas = k.IDkelas AND km.NIKdosenpembina = ? AND k.tahun = ? AND k.semester = ?", array($pembina, $tahun, $semester));
		return $query->result();
	}
	function select_absen2($tahun, $semester)
	{
		$query = $this->db->query("SELECT DISTINCT a.nrpmentor AS nrp, a.mingguke AS mingguke, a.statuskehadiran AS status FROM simits_absenmentor AS a, simits_mentor AS m, simits_kelompokmentoring AS km, simits_kelas AS k WHERE a.nrpmentor = m.NRPmentor AND m.NRPmentor = km.NRPmentor AND km.IDkelas = k.IDkelas AND k.tahun = ? AND k.semester = ?", array($tahun, $semester));
		return $query->result();
	}
	function update_absen($tahun, $semester, $mingguke, $NRPmentor, $statuskehadiran)
	{
		if ($this->exist_absen($tahun, $semester, $mingguke, $NRPmentor) == true)
		{
			$query = $this->db->query("UPDATE simits_absenmentor SET statuskehadiran = ? WHERE tahun = ? AND semester = ? AND nrpmentor = ? AND mingguke = ?", array($statuskehadiran, $tahun, $semester, $NRPmentor, $mingguke));
		}
		else
		{
			$query = $this->db->query("INSERT INTO simits_absenmentor(tahun, semester, mingguke, statuskehadiran, nrpmentor) VALUES (?, ?, ?, ?, ?)", array($tahun, $semester, $mingguke, $statuskehadiran, $NRPmentor));
		}
	}
	function exist_absen($tahun, $semester, $mingguke, $NRPmentor)
	{
		$query = $this->db->query("SELECT * FROM simits_absenmentor WHERE tahun = ? AND semester = ? AND mingguke = ? AND nrpmentor = ?", array($tahun, $semester, $mingguke, $NRPmentor));
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