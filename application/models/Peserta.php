<?php
class Peserta extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_peserta($nrp, $nama, $kelas, $kelompok, $jeniskelamin)
	{
		if ($this->exist_peserta($nrp) == false)
		{
			$query = $this->db->query("INSERT INTO simits_peserta(NRPpeserta, nama, IDkelas, IDkelompok, jeniskelamin) VALUES (?, ?, ?, ?, ?)", array($nrp, $nama, $kelas, $kelompok, $jeniskelamin));
		}
		else
		{
			// untuk peserta ngulang
			$query = $this->db->query("UPDATE simits_peserta SET kelas = ? WHERE NRPpeserta = ?", array($kelas, $nrp));
		}
	}
	function select_peserta($IDkelas, $IDkelompok)
	{
		$query = $this->db->query("SELECT NRPpeserta, nama, jeniskelamin FROM simits_peserta WHERE IDkelas = ? AND IDkelompok = ?", array($IDkelas, $IDkelompok));
		return $query->result();
	}
	function select_peserta_bykelas($IDkelas)
	{
		$query = $this->db->query("SELECT NRPpeserta, nama, jeniskelamin FROM simits_peserta WHERE IDkelas = ?", array($IDkelas));
		return $query->result();
	}
	function select_peserta_bykelompok($IDkelompok)
	{
		$query = $this->db->query("SELECT NRPpeserta, nama FROM simits_peserta WHERE IDkelompok = ?", array($IDkelompok));
		return $query->result();
	}
	function select_peserta_byNO($NOkelompok, $jeniskelamin, $tahun, $semester)
	{
		$query = $this->db->query("SELECT p.NRPpeserta AS NRPpeserta, p.nama AS nama FROM simits_peserta AS p, simits_kelompokmentoring AS km, simits_kelas AS k WHERE km.IDkelas = k.IDkelas AND km.NOkelompok = ? AND km.jeniskelamin = ? AND k.tahun = ? AND k.semester = ? AND km.IDkelompok = p.IDkelompok", array($NOkelompok, $jeniskelamin, $tahun, $semester));
		return $query->result();
	}
	function select_peserta_bymentor($tahun, $semester, $mentor)
	{
		$query = $this->db->query("SELECT p.NRPpeserta AS NRPpeserta, p.nama AS nama FROM simits_peserta AS p, simits_kelompokmentoring AS km, simits_kelas AS k WHERE k.IDkelas = km.IDkelas AND km.IDkelompok = p.IDkelompok AND k.tahun = ? AND k.semester = ? AND km.NRPmentor = ?", array($tahun, $semester, $mentor));
		return $query->result();
	}
	function select_by_tahunsemester($tahun, $semester)
	{
		$query = $this->db->query("SELECT p.NRPpeserta AS nrp FROM simits_peserta AS p, simits_kelas AS k WHERE p.IDkelas = k.IDkelas AND k.tahun = ? AND k.semester = ?", array($tahun, $semester));
		return $query->result();
	}
	function select_tahun()
	{
		$query = $this->db->query("SELECT DISTINCT k.tahun AS tahun FROM simits_peserta AS p, simits_kelas AS k WHERE p.IDkelas = k.IDkelas");
		return $query->result();
	}
	function update_kelompok($nrp, $kelompok)
	{
		$query = $this->db->query("UPDATE simits_peserta SET IDkelompok = ? WHERE NRPpeserta = ?", array($kelompok, $nrp));
	}
	function exist_peserta($nrp)
	{
		$query = $this->db->query("SELECT * FROM simits_peserta WHERE NRPpeserta = ?", array($nrp));
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