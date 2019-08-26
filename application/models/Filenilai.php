<?php
class Filenilai extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create_file($tahun, $semester, $kelas, $linknilai, $tglupload, $keterangan)
	{
		if ($this->exist_file($tahun, $semester, $kelas) == false)
		{
			$query = $this->db->query("INSERT INTO simits_filenilai(tahun, semester, IDkelas, linknilai, tglupload, keterangan) VALUES (?, ?, ?, ?, ?, ?)", array($tahun, $semester, $kelas, $linknilai, $tglupload, $keterangan));
		}
		else
		{
			$query = $this->db->query("UPDATE simits_filenilai SET linknilai = ? , tglupload = ? , keterangan = ? WHERE tahun = ? AND semester = ? AND IDkelas = ?", array($linknilai, $tglupload, $keterangan, $tahun, $semester, $kelas));
		}
	}

	function select_all_bysmt($tahun, $semester)
	{
		$query = $this->db->query("SELECT * FROM simits_filenilai WHERE tahun = ? AND semester = ?", array($tahun, $semester));
		return $query->result();
	}
	
	function select_all_bykls($tahun, $semester, $kelas)
	{
		$query = $this->db->query("SELECT * FROM simits_filenilai WHERE tahun = ? AND semester = ? AND IDkelas = ?", array($tahun, $semester, $kelas));
		return $query->result();
	}
	function delete_file($tahun, $semester, $kelas)
	{
		$query = $this->db->query("DELETE FROM simits_filenilai WHERE tahun = ? AND semester = ? AND IDkelas = ?", array($tahun, $semester, $kelas));
	}
	function path2file($tahun, $semester, $kelas)
	{
		$query = $this->db->query("SELECT linknilai FROM simits_filenilai WHERE tahun = ? AND semester = ? AND IDkelas = ?", array($tahun, $semester, $kelas));
		return $query->result();
	}
	function exist_file($tahun, $semester, $kelas)
	{
		$query = $this->db->query("SELECT * FROM simits_filenilai WHERE tahun = ? AND semester = ? AND IDkelas = ? ", array($tahun, $semester, $kelas));
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