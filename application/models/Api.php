<?php
class Api extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		//$this->authorization = 'Authorization: Bearer a063553f-20fe-3d22-9e04-bb08a96e31e4';
		// $this->authorization = 'Authorization: Bearer 2513a10a-beda-3709-ae6f-2d99b33987cc';
		$this->authorization = 'Authorization: Bearer a9e6f3ca-675e-3c5c-afd3-098fcfc264a2';
	}
	function get_data_mhs($nrp)
	{
		$url = 'https://api.its.ac.id:8243/akademik/1.3/mahasiswa/'. $nrp;
		$cSession = curl_init(); 
		curl_setopt($cSession, CURLOPT_URL, $url);
		curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Accept: application/json' , $this->authorization));
    	$result = curl_exec($cSession);
    	if($result === false) echo 'Curl error: ' . curl_error($cSession);
    	curl_close($cSession);
    	return json_decode($result);
	}
	function get_data_dosen($nik)
	{
		$url = 'https://api.its.ac.id:8243/akademik/1.3/dosen/'. $nik;
		$cSession = curl_init(); 
		curl_setopt($cSession, CURLOPT_URL, $url);
		curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Accept: application/json' , $this->authorization));
    	$result = curl_exec($cSession);
    	if($result === false) echo 'Curl error: ' . curl_error($cSession);
    	curl_close($cSession);
    	return json_decode($result);
	}
	function get_data_kurikulum($idProdi, $tahun, $semester)
	{
		$url = 'https://api.its.ac.id:8243/akademik/1.3/prodi/'.$idProdi.'/kurikulum?tahun='.$tahun.'&semester='.$semester;
		$cSession = curl_init(); 
		curl_setopt($cSession, CURLOPT_URL, $url);
		curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Accept: application/json' , $this->authorization));
    	$result = curl_exec($cSession);
    	if($result === false) echo 'Curl error: ' . curl_error($cSession);
    	curl_close($cSession);
    	return json_decode($result);
	}
	function get_daftar_mhs($idProdi, $idMatkul, $kelas, $tahun, $semester)
	{
		$url = 'https://api.its.ac.id:8243/akademik/1.3/prodi/'.$idProdi.'/matakuliah/'.$idMatkul.'/kelas/'.$kelas.'/peserta?tahun='.$tahun.'&semester='.$semester;
		//echo $url;
		$cSession = curl_init(); 
		curl_setopt($cSession, CURLOPT_URL, $url);
		curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Accept: application/json' , $this->authorization));
    	$result = curl_exec($cSession);
    	if($result === false) echo 'Curl error: ' . curl_error($cSession);
    	curl_close($cSession);
    	return json_decode($result);
	}
	function get_daftar_prodi()
	{
		$url = 'https://api.its.ac.id:8243/akademik/1.3/prodi?per-page=1000';
		$cSession = curl_init(); 
		curl_setopt($cSession, CURLOPT_URL, $url);
		curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Accept: application/json' , $this->authorization));
    	$result = curl_exec($cSession);
    	if($result === false) echo 'Curl error: ' . curl_error($cSession);
    	curl_close($cSession);
    	return json_decode($result);
	}
	function get_daftar_kelas($prodi, $tahun, $semester)
	{
		$url = 'https://api.its.ac.id:8243/akademik/1.3/prodi/'.$prodi.'/kelas?tahun='.$tahun.'&semester='.$semester.'&per-page=1000';
		$cSession = curl_init(); 
		curl_setopt($cSession, CURLOPT_URL, $url);
		curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Accept: application/json' , $this->authorization));
    	$result = curl_exec($cSession);
    	if($result === false) echo 'Curl error: ' . curl_error($cSession);
    	curl_close($cSession);
    	return json_decode($result);
	}
	function get_daftar_dosen($nama)
	{
		$url = 'https://api.its.ac.id:8243/akademik/1.3/dosen?nama='.$nama;
		$cSession = curl_init(); 
		curl_setopt($cSession, CURLOPT_URL, $url);
		curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Accept: application/json' , $this->authorization));
    	$result = curl_exec($cSession);
    	if($result === false) echo 'Curl error: ' . curl_error($cSession);
    	curl_close($cSession);
    	return json_decode($result);
	}
}
?>