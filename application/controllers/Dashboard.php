<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        //Meload model_app
		$this->load->model('model_app');
        //Jika session tidak ditemukan
		if (!$this->session->userdata('id_user')) {
            //Kembali ke halaman Login
            $this->session->set_flashdata("msg", "<div class='alert alert-info'>
       		<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
       	    <strong><span class='glyphicon glyphicon-remove-sign'></span></strong> 
       	    Please Login First.</div>");
            redirect('login');
        }
	}

	public function index()
	{
		//Menyusul telplate dashboard
		$data['title'] 		= "Dashboard";
        $data['navbar']     = "navbar";
        $data['sidebar']	= "sidebar";
        $data['body'] 		= "dashboard/dashboard";

        //Session
        $id_dept = $this->session->userdata('id_dept');
        $id_user = $this->session->userdata('id_user');

        //Papan Pengumuman
        $data['datainformasi'] = $this->model_app->informasi()->result();

        ///////////////////////////////////////////////////////////////Dashboard Admin///////////////////////////////////////////////////////////////

        //Jumlah Tiket
        $data['jml_ticket']         = $this->model_app->getTicket()->num_rows();
        //Jumlah tiket yang ditolak Admin
        $data['jml_reject']         = $this->model_app->getStatusTicket(0)->num_rows();
        //Jumlah tiket yang butuh persetujuan Admin
        $jlmnew = $this->db->query("SELECT COUNT(id_ticket) AS jlm_new FROM ticket WHERE status IN (1,2)")->row();
        $data['jlm_new']           = $jlmnew->jlm_new;
        //Jumlah tiket yang belum memilih teknisi
        $data['jml_choose']         = $this->model_app->getStatusTicket(1)->num_rows();
        //Jumlah tiket yang butuh persetujuan teknisi
        $data['jml_approve_tek']    = $this->model_app->getStatusTicket(3)->num_rows();
        //Jumlah tiket yang sedang dikerjakan
        $data['jml_process']        = $this->model_app->getStatusTicket(4)->num_rows();
        //Jumlah tiket yang sedang dipending
        $data['jml_pending']        = $this->model_app->getStatusTicket(5)->num_rows();
        //Jumlah tiket selesai
        $jlmdone = $this->db->query("SELECT COUNT(id_ticket) AS jlm_done FROM ticket WHERE status IN (6,7)")->row();
        $data['jml_done']           = $jlmdone->jlm_done;

        //Resume ticket
        $data['ticket']        = $this->model_app->all_ticket()->result();
        $data['jlmticket']     = $this->model_app->all_ticket()->num_rows();

        //Papan Teknisi
        $data['teknisi']            = $this->model_app->getTek()->result();

        $data['lbl_subkat']         = $this->model_app->Bar_Ticket()->result();

        $data['lbl_kondisi']        = $this->model_app->pie_kondisi()->result();

        $data['lbl_perbulan']       = $this->model_app->line_bulan()->result();

        $data['lbl_status']       = $this->model_app->pie_status()->result();

        ///////////////////////////////////////////////////////////////Dashboard Teknisi///////////////////////////////////////////////////////////////

        //Jumlah tiket setiap teknisi
        $tek_assign = $this->db->query("SELECT COUNT(id_ticket) AS jlm_tekassign FROM ticket 
                                         WHERE teknisi = '$id_user'")->row();
        $data['tekassign'] = $tek_assign->jlm_tekassign;
        //Jumlah tiket yang perlu di approve tiap teknisi
        $tek_approve = $this->db->query("SELECT COUNT(id_ticket) AS jlm_tekapprove FROM ticket
                                         WHERE status = 3 AND teknisi = '$id_user'")->row();
        $data['tekapprove'] = $tek_approve->jlm_tekapprove;
        //Jumlah tiket yang dikerjakan tiap teknisi
        $tek_kerja   = $this->db->query("SELECT COUNT(id_ticket) AS jlm_tekkerja FROM ticket
                                         WHERE status = 4 AND teknisi = '$id_user'")->row();
        $data['tekkerja'] = $tek_kerja->jlm_tekkerja;
        //Jumlah tiket yang dipending tiap teknisi
        $tek_pending = $this->db->query("SELECT COUNT(id_ticket) AS jlm_tekpending FROM ticket
                                         WHERE status = 5 AND teknisi = '$id_user'")->row();
        $data['tekpending'] = $tek_pending->jlm_tekpending;
        //Jumlah tiket yang selesai dikerjakan tiap teknisi
        $tek_selesai = $this->db->query("SELECT COUNT(id_ticket) AS jlm_tekselesai FROM ticket 
                                         WHERE status IN (6,7) AND teknisi = '$id_user'")->row();
        $data['tekselesai'] = $tek_selesai->jlm_tekselesai;

        //Resume ticket
        $data['datatickettek']  = $this->model_app->allassignment($id_user)->result();
        $data['jlmtugas']       = $this->model_app->allassignment($id_user)->num_rows();

        ///////////////////////////////////////////////////////////////Dashboard Pegawai///////////////////////////////////////////////////////////////
        
        //Jumlah semua ticket
        $user_ticket = $this->db->query("SELECT COUNT(id_ticket) AS jlm_userticket FROM ticket
                                         WHERE reported = '$id_user'")->row();
        $data['userticket'] = $user_ticket->jlm_userticket;
        //Jumlah ticket yang sudah diapprove
        $user_approve = $this->db->query("SELECT COUNT(id_ticket) AS jlm_userapprove FROM ticket
                                         WHERE reported = '$id_user' AND status = 1")->row();
        $data['userapprove'] = $user_approve->jlm_userapprove;
        //Jumlah ticket yang di reject
        $user_reject = $this->db->query("SELECT COUNT(id_ticket) AS jlm_userreject FROM ticket
                                         WHERE reported = '$id_user' AND status = 0")->row();
        $data['userreject'] = $user_reject->jlm_userreject;
        //Jumlah ticket yang sedang proses
        $user_process = $this->db->query("SELECT COUNT(id_ticket) AS jlm_userprocess FROM ticket
                                         WHERE reported = '$id_user' AND status = 4")->row();
        $data['userprocess'] = $user_process->jlm_userprocess;
        //Jumlah ticket yang sedang di pending
        $user_pending = $this->db->query("SELECT COUNT(id_ticket) AS jlm_userpending FROM ticket
                                         WHERE reported = '$id_user' AND status = 5")->row();
        $data['userpending'] = $user_pending->jlm_userpending;
        //Jumlah ticket yang selesai
        $user_done = $this->db->query("SELECT COUNT(id_ticket) AS jlm_userdone FROM ticket
                                         WHERE reported = '$id_user' AND status = 6")->row();
        $data['userdone'] = $user_done->jlm_userdone;

        //Resume ticket
        $data['dataticketuser']         = $this->model_app->myticket($id_user)->result();

        $this->load->view('template', $data);
	}
}