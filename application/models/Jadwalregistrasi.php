<?php
class Jadwalregistrasi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function select()
	{
		$query = $this->db->query("SELECT * FROM simits_jadwalregistrasi");
		return $query->result();
	}
	function update($setting, $tanggal)
	{
		$query = $this->db->query("UPDATE simits_jadwalregistrasi SET tanggal = ? WHERE setting = ?", array($tanggal, $setting));
	}
}
?>