<?php
class Kelompok extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function select_kelompok($IDkelas)
	{
		// SELECT k.IDkelompok AS id, k.jeniskelamin AS jenis, k.NOkelompok AS no, m.nama AS mentor, p.nama AS pembina, k.hari AS hari
		// FROM
		// (
		//     simits_kelompokmentoring AS k
		//     LEFT JOIN simits_mentor AS m
		//     ON k.NRPmentor = m.NRPmentor
		    
		// )
		// lEFT JOIN simits_dosenpembina AS p
		// ON k.NIKdosenpembina = p.NIKdosenpembina
		// WHERE k.IDkelas = ?
		$query = $this->db->query("SELECT k.IDkelompok AS id, k.jeniskelamin AS jenis, k.NOkelompok AS no, m.nama AS mentor, p.nama AS pembina, k.hari AS hari FROM (simits_kelompokmentoring AS k LEFT JOIN simits_mentor AS m ON k.NRPmentor = m.NRPmentor) LEFT JOIN simits_dosenpembina AS p ON k.NIKdosenpembina = p.NIKdosenpembina WHERE k.IDkelas = ?", array($IDkelas));
		return $query->result();
	}
	function select_kelompok_pembina($kelas, $pembina)
	{
		$query = $this->db->query("SELECT k.IDkelompok AS id, k.jeniskelamin AS jenis, k.NOkelompok AS no, m.nama AS mentor, p.nama AS pembina, k.hari AS hari FROM (simits_kelompokmentoring AS k LEFT JOIN simits_mentor AS m ON k.NRPmentor = m.NRPmentor) LEFT JOIN simits_dosenpembina AS p ON k.NIKdosenpembina = p.NIKdosenpembina WHERE k.IDkelas = ? AND p.NIKdosenpembina = ?", array($kelas, $pembina));
		return $query->result();
	}
	function select_kelompok2($tahun, $semester)
	{
		$query = $this->db->query("SELECT km.IDkelompok AS id, km.jeniskelamin AS jenis, km.NOkelompok AS no, m.nama AS mentor, p.nama AS pembina, km.hari AS hari, k.NOkelas AS kelas FROM simits_kelompokmentoring AS km, simits_kelas AS k, simits_mentor AS m, simits_dosenpembina AS p WHERE km.IDkelas = k.IDkelas AND km.NRPmentor = m.NRPmentor AND km.NIKdosenpembina = p.NIKdosenpembina AND k.tahun = ? AND k.semester = ? ORDER BY km.hari, k.NOkelas, km.NOkelompok, km.jeniskelamin", array($tahun, $semester));
		return $query->result();
	}
	function select_kelompok_byID($IDkelompok)
	{
		$query = $this->db->query("SELECT * FROM simits_kelompokmentoring WHERE IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function select_kelompok_bytahunsemester($tahun, $semester)
	{
		$query = $this->db->query("SELECT km.IDkelompok AS id FROM simits_kelompokmentoring AS km, simits_kelas AS k WHERE km.IDkelas = k.IDkelas AND k.tahun = ? AND k.semester = ?", array($tahun, $semester));
		return $query->result();
	}
	function select_IDkelompok($no, $mentor, $kelas, $pembina, $hari)
	{
		$query = $this->db->query("SELECT IDkelompok FROM simits_kelompokmentoring WHERE NOkelompok = ? AND NRPmentor = ? AND IDkelas = ? AND NIKdosenpembina = ? AND hari = ?", array($no, $mentor, $kelas, $pembina, $hari));
		return $query->result();
	}
	function select_IDkelompok_bymentor($tahun, $semester, $mentor)
	{
		$query = $this->db->query("SELECT km.IDkelompok AS id, km.NOkelompok AS no, k.NOkelas AS kelas FROM simits_kelompokmentoring AS km, simits_kelas AS k WHERE km.IDkelas = k.IDkelas AND k.tahun = ? AND k.semester = ? AND km.NRPmentor = ?", array($tahun, $semester, $mentor));
		return $query->result();
	}
	function select_mentor($IDkelompok)
	{
		$query = $this->db->query("SELECT m.nama AS nama FROM simits_mentor AS m, simits_kelompokmentoring AS k WHERE k.NRPmentor = m.NRPmentor AND k.IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function select_dosen($IDkelompok)
	{
		$query = $this->db->query("SELECT d.nama AS nama FROM simits_dosen AS d, simits_kelompokmentoring AS k, simits_kelas AS kls WHERE kls.NIKdosen = d.NIKdosen AND kls.IDkelas = k.IDkelas AND k.IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function select_pembina($IDkelompok)
	{
		$query = $this->db->query("SELECT d.nama AS nama FROM simits_dosenpembina AS d, simits_kelompokmentoring AS k WHERE k.NIKdosenpembina = d.NIKdosenpembina AND k.IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function select_kelas($IDkelompok)
	{
		$query = $this->db->query("SELECT IDkelas FROM simits_kelompokmentoring WHERE IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function create_kelompok($no, $mentor, $kelas, $pembina, $hari, $jenis)
	{
		$query = $this->db->query("INSERT INTO simits_kelompokmentoring(NOkelompok, NRPmentor, IDkelas, NIKdosenpembina, hari, jeniskelamin) VALUES (?, ?, ?, ?, ?, ?)", array($no, $mentor, $kelas, $pembina, $hari, $jenis));
	}
	function update_kelompok($IDkelompok, $no, $mentor, $pembina, $hari, $jenis)
	{
		$query = $this->db->query("UPDATE simits_kelompokmentoring SET NOkelompok = ?, NRPmentor = ?, NIKdosenpembina = ?, hari = ?, jeniskelamin = ? WHERE IDkelompok = ?", array($no, $mentor, $pembina, $hari, $jenis, $IDkelompok));
	}
	function delete_kelompok($IDkelompok)
	{
		$query = $this->db->query("DELETE FROM simits_kelompokmentoring WHERE IDkelompok = ?", array($IDkelompok));
		$query = $this->db->query("UPDATE simits_peserta SET IDkelompok = -1 WHERE IDkelompok = ?", array($IDkelompok));
	}
	function exist_kelompok($no, $mentor, $pembina, $hari, $jenis)
	{
		$query = $this->db->query("SELECT * FROM simits_kelompokmentoring WHERE NOkelompok = ? AND NRPmentor = ? AND NIKdosenpembina = ? AND hari = ? AND jeniskelamin = ?", array($no, $mentor, $pembina, $hari, $jenis));
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