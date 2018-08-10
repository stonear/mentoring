<?php
class Nilai extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_nilai($IDnilai, $nilai, $NRPpeserta)
	{
		if ($this->exist_nilai($IDnilai, $NRPpeserta) == false)
		{
			$query = $this->db->query("INSERT INTO simits_nilai(IDnilai, nilai, NRPpeserta) VALUES (?, ?, ?)", array($IDnilai, $nilai, $NRPpeserta));
		}
	}
	function select_nilai($kelompok)
	{
		$query = $this->db->query("SELECT a.NRPpeserta AS nrp, a.IDnilai AS IDnilai, a.nilai AS nilai FROM simits_peserta AS p, simits_nilai AS a WHERE p.IDkelompok = ? AND p.NRPpeserta = a.NRPpeserta", array($kelompok));
		return $query->result();
	}
	function select_nilai_bykelas($kelas)
	{
		$query = $this->db->query("SELECT a.NRPpeserta AS nrp, a.IDnilai AS IDnilai, a.nilai AS nilai FROM simits_peserta AS p, simits_nilai AS a WHERE p.IDkelas = ? AND p.NRPpeserta = a.NRPpeserta ORDER BY nrp", array($kelas));
		return $query->result();
	}
	function update_nilai($NRPpeserta, $IDnilai, $nilai)
	{
		if ($this->exist_nilai($IDnilai, $NRPpeserta) == true)
		{
			$query = $this->db->query("UPDATE simits_nilai SET nilai = ? WHERE NRPpeserta = ? AND IDnilai = ?", array($nilai, $NRPpeserta, $IDnilai));
		}
		else
		{
			$query = $this->db->query("INSERT INTO simits_nilai(NRPpeserta, IDnilai, nilai) VALUES (?, ?, ?)", array($NRPpeserta, $IDnilai, $nilai));
		}
	}
	function exist_nilai($IDnilai, $NRPpeserta)
	{
		$query = $this->db->query("SELECT * FROM simits_nilai WHERE IDnilai = ? AND NRPpeserta = ?", array($IDnilai, $NRPpeserta));
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