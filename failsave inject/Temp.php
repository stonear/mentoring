<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temp extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->model('database');
	}
	public function index()
	{
		$this->database->up();
	}
}