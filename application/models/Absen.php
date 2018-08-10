<?php
class Absen extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_absen($mingguke, $NRPpeserta)
	{
		if ($this->exist_absen($mingguke, $NRPpeserta) == false)
		{
			$query = $this->db->query("INSERT INTO simits_absen(mingguke, statuskehadiran, NRPpeserta) VALUES (?, ?, ?)", array($mingguke, 0, $NRPpeserta));
		}
	}
	function select_absen($kelompok)
	{
		$query = $this->db->query("SELECT a.NRPpeserta AS nrp, a.mingguke AS mingguke, a.statuskehadiran AS status FROM simits_peserta AS p, simits_absen AS a WHERE p.IDkelompok = ? AND p.NRPpeserta = a.NRPpeserta", array($kelompok));
		return $query->result();
	}
	function select_absen_bykelas($kelas)
	{
		$query = $this->db->query("SELECT a.NRPpeserta AS nrp, a.mingguke AS mingguke, a.statuskehadiran AS status FROM simits_peserta AS p, simits_absen AS a WHERE p.IDkelas = ? AND p.NRPpeserta = a.NRPpeserta ORDER BY nrp", array($kelas));
		return $query->result();
	}
	function update_absen($NRPpeserta, $mingguke, $statuskehadiran)
	{
		if ($this->exist_absen($mingguke, $NRPpeserta) == true)
		{
			$query = $this->db->query("UPDATE simits_absen SET statuskehadiran = ? WHERE NRPpeserta = ? AND mingguke = ?", array($statuskehadiran, $NRPpeserta, $mingguke));
		}
		else
		{
			$query = $this->db->query("INSERT INTO simits_absen(mingguke, statuskehadiran, NRPpeserta) VALUES (?, ?, ?)", array($mingguke, $statuskehadiran, $NRPpeserta));
		}
	}
	function exist_absen($mingguke, $NRPpeserta)
	{
		$query = $this->db->query("SELECT * FROM simits_absen WHERE mingguke = ? AND NRPpeserta = ?", array($mingguke, $NRPpeserta));
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