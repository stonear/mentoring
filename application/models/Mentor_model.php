<?php
class Mentor_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function select_mentor()
	{
		$query = $this->db->query("SELECT NRPmentor, nama FROM simits_mentor");
		return $query->result();
	}
	function select_mentor2($tahun, $semester)
	{
		$query = $this->db->query("SELECT DISTINCT m.NRPmentor AS nrp, m.nama AS nama FROM simits_mentor AS m, simits_kelompokmentoring AS km, simits_kelas AS k WHERE m.NRPmentor = km.NRPmentor AND km.IDkelas = k.IDkelas AND k.tahun = ? AND k.semester = ?", array($tahun, $semester));
		return $query->result();
	}
	function select_mentor3($tahun, $semester)
	{
		$query = $this->db->query("SELECT m.NRPmentor, m.nama, m.jenis_kelamin FROM simits_mentor AS m, simits_smtmentor AS sm WHERE m.NRPmentor = sm.nrp AND sm.tahun = ? AND sm.semester = ?", array($tahun, $semester));
		return $query->result();
	}
	function select_mentor_byNRP($nrp)
	{
		$query = $this->db->query("SELECT * FROM simits_mentor WHERE NRPmentor = ?", array($nrp));
		return $query->result();
	}
	function select_mentor_bypembina($tahun, $semester, $pembina)
	{
		$query = $this->db->query("SELECT DISTINCT m.NRPmentor AS nrp, m.nama AS nama FROM simits_mentor AS m, simits_kelompokmentoring AS km, simits_kelas AS k WHERE m.NRPmentor = km.NRPmentor AND km.IDkelas = k.IDkelas AND km.NIKdosenpembina = ? AND k.tahun = ? AND k.semester = ?", array($pembina, $tahun, $semester));
		return $query->result();
	}
	function create_mentor($nrp, $nama, $jenis_kelamin)
	{
		$query = $this->db->query("INSERT INTO simits_mentor(NRPmentor, nama, jenis_kelamin, password, verified) VALUES (?, ?, ?, ?, ?)", array($nrp, $nama, $jenis_kelamin, password_hash($nrp, PASSWORD_BCRYPT), 1));
	}
	function registrasi($NRPmentor, $nama, $jenis_kelamin, $no_telp, $email, $alamat, $pernah_jadi_mentor, $cv, $password, $linkfoto, $kode_verifikasi, $verified, $nilai)
	{
		$query = $this->db->query("INSERT INTO simits_mentor(NRPmentor, nama, jenis_kelamin, no_telp, email, alamat, pernah_jadi_mentor, cv, password, linkfoto, kode_verifikasi, verified, nilai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($NRPmentor, $nama, $jenis_kelamin, $no_telp, $email, $alamat, $pernah_jadi_mentor, $cv, $password, $linkfoto, $kode_verifikasi, $verified, $nilai));
	}
	function update_passwordmentor($nrp, $password)
	{
		$query = $this->db->query("UPDATE simits_mentor SET password = ? WHERE NRPmentor = ?", array(password_hash($password, PASSWORD_BCRYPT), $nrp));
	}
	function update_namamentor($nrp, $nama)
	{
		$query = $this->db->query("UPDATE simits_mentor SET nama = ? WHERE NRPmentor = ?", array($nama, $nrp));
	}
	function update_jkmentor($nrp, $jenis_kelamin)
	{
		$query = $this->db->query("UPDATE simits_mentor SET jenis_kelamin = ? WHERE NRPmentor = ?", array($jenis_kelamin, $nrp));
	}
	function update_foto($nrp, $link)
	{
		$query = $this->db->query("UPDATE simits_mentor SET linkfoto = ? WHERE NRPmentor = ?", array($link, $nrp));
	}
	function update_cv($nrp, $link)
	{
		$query = $this->db->query("UPDATE simits_mentor SET cv = ? WHERE NRPmentor = ?", array($link, $nrp));
	}
	function update_profil($nrp, $jenis_kelamin, $no, $email, $alamat, $pernah, $nilai)
	{
		$query = $this->db->query("UPDATE simits_mentor SET jenis_kelamin = ?, no_telp = ?, email = ?, alamat = ?, pernah_jadi_mentor = ?, nilai = ? WHERE NRPmentor = ?", array($jenis_kelamin, $no, $email, $alamat, $pernah, $nilai, $nrp));
	}
	function delete_mentor($nrp)
	{
		$query = $this->db->query("DELETE FROM simits_mentor WHERE NRPmentor = ?", array($nrp));
	}
	function exist_mentor($nrp)
	{
		$query = $this->db->query("SELECT * FROM simits_mentor WHERE NRPmentor = ?", array($nrp));
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