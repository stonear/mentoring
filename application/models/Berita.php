<?php
class Berita extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_berita($admin, $judul, $konten)
	{
		$query = $this->db->query("INSERT INTO simits_berita(admin, tanggal, judul, konten) VALUES (?, NOW(), ?, ?)", array($admin, $judul, $konten));
	}
	function select_berita($count = 5)
	{
		if ($count == 0)
		{
			$query = $this->db->query("SELECT id, admin, tanggal, judul FROM simits_berita ORDER BY tanggal DESC");
		}
		else
		{
			$query = $this->db->query("SELECT * FROM simits_berita ORDER BY tanggal DESC LIMIT ?", array($count));
		}
		return $query->result();
	}
	function select_berita_byID($id)
	{
		$query = $this->db->query("SELECT * FROM simits_berita WHERE id = ?", array($id));
		return $query->result();
	}
	function update_berita($id, $judul, $konten)
	{
		$query = $this->db->query("UPDATE simits_berita SET judul = ?, konten = ? WHERE id = ?", array($judul, $konten, $id));
	}
	function delete_berita($id)
	{
		$query = $this->db->query("DELETE FROM simits_berita WHERE id = ?", array($id));
	}
}
?>