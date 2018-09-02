<?php
class Smtmentor extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function registrasi($nrp, $tahun, $semester)
	{
		if ($this->exist_sm($nrp, $tahun, $semester) == false)
		{
			$query = $this->db->query("INSERT INTO simits_smtmentor(nrp, tahun, semester) VALUES (?, ?, ?)", array($nrp, $tahun, $semester));
		}
	}
	function exist_sm($nrp, $tahun, $semester)
	{
		$query = $this->db->query("SELECT * FROM simits_smtmentor WHERE nrp = ? AND tahun = ? AND semester = ?", array($nrp, $tahun, $semester));
		if ($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function select_byNRP($nrp)
	{
		$query = $this->db->query("SELECT * FROM simits_smtmentor WHERE nrp = ?", array($nrp));
		return $query->result();
	}
	function delete($nrp, $tahun, $semester)
	{
		$this->db->query("DELETE FROM simits_smtmentor WHERE nrp = ? AND tahun = ? AND semester = ?", array($nrp, $tahun, $semester));
	}
}
?>