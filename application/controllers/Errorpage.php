<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errorpage extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//Meload model_app
		$this->load->model('model_app');
		//Jika session tidak ditemukan
		if (!$this->session->userdata('id_user')) {
			$this->session->set_flashdata('status1', 'expired');
			redirect('login');
		}
	}

	public function index()
	{
		//Menyusun template
		$data['title'] 	  = "Error. Page that you requested are forbidden";
		$data['navbar']   = "navbar";
		$data['sidebar']  = "sidebar";
		$data['body']     = "error";

		//Session
		$id_dept = $this->session->userdata('id_dept');
		$id_user = $this->session->userdata('id_user');

		//Load template
		$this->load->view('template', $data);
	}
}