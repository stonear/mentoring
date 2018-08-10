<?php
class Admin2 extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function create_admin($username, $password)
	{
		if ($this->exist_admin($username) == false)
		{
			$query = $this->db->query("INSERT INTO simits_admin(username, password) VALUES (?, ?)", array($username, password_hash($password, PASSWORD_BCRYPT)));
		}
	}
	function select_admin()
	{
		$query = $this->db->query("SELECT username FROM simits_admin");
		return $query->result();
	}
	function exist_admin($username)
	{
		$query = $this->db->query("SELECT * FROM simits_admin WHERE username = ?", array($username));
		if ($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function update_admin($username, $password)
	{
		$query = $this->db->query("UPDATE simits_admin SET password = ? WHERE username = ?", array(password_hash($password, PASSWORD_BCRYPT), $username));
	}
	function delete_admin($username)
	{
		$query = $this->db->query("DELETE FROM simits_admin WHERE username = ?", array($username));
	}
}
?>