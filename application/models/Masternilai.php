<?php
class Masternilai extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function select_masternilai()
	{
		$query = $this->db->query("SELECT * FROM simits_masternilai");
		return $query->result();
	}
	function create_masternilai($namanilai)
	{
		$query = $this->db->query("INSERT INTO simits_masternilai(namanilai) VALUES (?)", array($namanilai));
	}
	function update_masternilai($IDnilai, $namanilai_baru)
	{
		$query = $this->db->query("UPDATE simits_masternilai SET namanilai = ? WHERE IDnilai = ?", array($namanilai_baru, $IDnilai));
	}
	function delete_masternilai($IDnilai)
	{
		$query = $this->db->query("DELETE FROM simits_masternilai WHERE IDnilai = ?", array($IDnilai));
	}
}
?>