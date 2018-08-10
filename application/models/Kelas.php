<?php
class Kelas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function select_kelas($tahun, $semester)
	{
		$query = $this->db->query("SELECT * FROM simits_kelas WHERE tahun = ? AND semester = ? ORDER BY NOkelas", array($tahun, $semester));
		return $query->result();
	}
	function select_kelas_dosen($tahun, $semester, $dosen)
	{
		$query = $this->db->query("SELECT * FROM simits_kelas WHERE tahun = ? AND semester = ? AND NIKdosen = ? ORDER BY NOkelas", array($tahun, $semester, $dosen));
		return $query->result();
	}
	function select_kelas_pembina($tahun, $semester, $pembina)
	{
		$query = $this->db->query("SELECT DISTINCT k.IDkelas AS IDkelas, k.NOkelas AS NOkelas FROM simits_kelas AS k, simits_kelompokmentoring AS km WHERE k.tahun = ? AND k.semester = ? AND k.IDkelas = km.IDkelas AND km.NIKdosenpembina = ? ORDER BY k.NOkelas", array($tahun, $semester, $pembina));
		return $query->result();
	}
	function select_tahunsemester($IDkelas)
	{
		$query = $this->db->query("SELECT tahun, semester FROM simits_kelas WHERE IDkelas = ?", array($IDkelas));
		return $query->result();
	}
	function select_IDkelas($tahun, $semester, $kelas)
	{
		$query = $this->db->query("SELECT IDkelas FROM simits_kelas WHERE tahun = ? AND semester = ? AND NOkelas = ?", array($tahun, $semester, $kelas));
		return $query->result();
	}
	function select_tahun()
	{
		$query = $this->db->query("SELECT tahun FROM simits_kelas GROUP BY tahun");
		return $query->result();
	}
	function create_kelas($kelas, $dosen, $tahun, $semester)
	{
		if ($this->exist_kelas($kelas, $dosen, $tahun, $semester) == false)
		{
			$query = $this->db->query("INSERT INTO simits_kelas(NOkelas, NIKdosen, tahun, semester) VALUES (?, ?, ?, ?)", array($kelas, $dosen, $tahun, $semester));
		}
	}
	function exist_kelas($kelas, $dosen, $tahun, $semester)
	{
		$query = $this->db->query("SELECT * FROM simits_kelas WHERE NOkelas = ? AND NIKdosen = ? AND tahun = ? AND semester = ?", array($kelas, $dosen, $tahun, $semester));
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