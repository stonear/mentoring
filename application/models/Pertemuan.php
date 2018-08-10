<?php
class Pertemuan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function select_pertemuan()
	{
		$query = $this->db->query("SELECT * FROM simits_pertemuan");
		return $query->result();
	}
	function select_jumlahpertemuan($tahun, $semester)
	{
		$query = $this->db->query("SELECT jumlahpertemuan FROM simits_pertemuan WHERE tahun = ? AND semester = ?", array($tahun, $semester));
		return $query->result();
	}
	function create_pertemuan($tahun, $semester, $jumlah)
	{
		$query = $this->db->query("INSERT INTO simits_pertemuan(tahun, semester, jumlahpertemuan) VALUES (?, ?, ?)", array($tahun, $semester, $jumlah));
	}
	function update_pertemuan($tahun, $semester, $tahun_baru, $semester_baru, $jumlah_baru)
	{
		$query = $this->db->query("UPDATE simits_pertemuan SET tahun = ?, semester = ?, jumlahpertemuan = ? WHERE tahun = ? AND semester = ?", array($tahun_baru, $semester_baru, $jumlah_baru, $tahun, $semester));
	}
	function delete_pertemuan($tahun, $semester)
	{
		$query = $this->db->query("DELETE FROM simits_pertemuan WHERE tahun = ? AND semester = ?", array($tahun, $semester));
	}
	function exist_pertemuan($tahun, $semester)
	{
		$query = $this->db->query("SELECT * FROM simits_pertemuan WHERE tahun = ? AND semester = ?", array($tahun, $semester));
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