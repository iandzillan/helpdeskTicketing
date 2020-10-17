<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informasi extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//Meload model_app
		$this->load->model('model_app');

		//Jika session tidak ditemukan
		if (!$this->session->userdata('id_user')) {
			//Kembali ke halaman Login
			$this->session->set_flashdata('status1', 'expired');
			redirect('login');
		}
	}

	public function index()
	{
		//User harus admin, tidak boleh role user lain
		if($this->session->userdata('level') == "Admin"){
			//Menyusun template List Informasi
			$data['title'] 	  = "Information";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "informasi/index";

        	//Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

        	//Daftar semua informasi, get dari model_app (informasi), data akan ditampung dalam parameter 'informasi'
			$data['informasi'] = $this->model_app->informasi()->result();

			//Load template
			$this->load->view('template', $data);
		} else {
			//Bagian ini jika role yang mengakses tidak sama dengan admin
			//Akan dibawa ke Controller Errorpage 
			redirect('Errorpage');
		}
	}

	public function tambah()
	{
		//Form validasi untuk subject dengan nama validasi = subject
		$this->form_validation->set_rules('subject', 'Subject', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Subject.
							   </div>'
			)
		);

		//Form validasi untuk deskripsi pengumuman dengan nama validasi = pesan
		$this->form_validation->set_rules('pesan', 'Pesan', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Announcement.
							   </div>'
			)
		);

		//Kondisi jika proses tambah tidak memenuhi syarat validasi akan dikembalikan ke form tambah informasi
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template List Informasi
				$data['title'] 	  = "Information";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['modal_show'] = "$('#modal-fade').modal('show');";
				$data['body']     = "informasi/index";

        		//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

        		//Daftar semua informasi, get dari model_app (informasi), data akan ditampung dalam parameter 'informasi'
				$data['informasi'] = $this->model_app->informasi()->result();

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage 
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil menambah informasi
			//Data informasi ditampung dalam bentuk array
			$data = array(
				'tanggal' => date("Y-m-d  H:i:s"),
				'subject' => ucfirst($this->input->post('subject')),
				'pesan'   => ucfirst($this->input->post('pesan')),
				'id_user' => $this->session->userdata('id_user')
			);

			//Query insert data yang ditampung ke dalam database. tersimpan ditabel informasi
			$this->db->insert('informasi', $data);

			//Set pemberitahuan bahwa data informasi berhasil ditambahkan
			$this->session->set_flashdata('status', 'Added');
			//Kembali ke halaman informasi (index)
			redirect('Informasi');
		}
	}

	public function hapus($id)
	{
		//Menghapus data dengan kondisi jika $id sama dengan id_informasi yang kita pilih
		//Query menghapus data pada tabel informasi berdasarkan id_informasi
		$this->db->where('id_informasi', $id);
		$this->db->delete('informasi');

		//Set pemberitahuan bahwa data informasi berhasil dihapus
		$this->session->set_flashdata('status', 'Deleted');
		//Kembali ke halaman informasi (index)
		redirect('Informasi');
	}

	public function edit($id)
	{
		//User harus admin, tidak boleh role user lain
		if($this->session->userdata('level') == "Admin"){
			//Menyusun template Edit Informasi
			$data['title'] 	  = "Edit Information";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "informasi/edit";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Get data informasi yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getinformasi)
			$data['informasi'] = $this->model_app->getinformasi($id)->row_array();
			//Load template
			$this->load->view('template', $data);
		} else {
			//Bagian ini jika role yang mengakses tidak sama dengan admin
			//Akan dibawa ke Controller Errorpage
			redirect('Errorpage');
		}
	}

	public function update($id)
	{
		//Form validasi untuk subject dengan nama validasi = subject
		$this->form_validation->set_rules('subject', 'Subject', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Subject.
							   </div>'
			)
		);

		//Form validasi untuk deskripsi pengumuman dengan nama validasi = pesan
		$this->form_validation->set_rules('pesan', 'Pesan', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Announcement.
							   </div>'
			)
		);

		//Kondisi jika proses edit tidak memenuhi syarat validasi akan dikembalikan ke form edit informasi
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template Edit Informasi
				$data['title'] 	  = "Edit Information";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['body']     = "informasi/edit";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Get data informasi yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getinformasi)
				$data['informasi'] = $this->model_app->getinformasi($id)->row_array();
				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil update informasi
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Data informasi ditampung dalam bentuk array
				$data = array(
					'tanggal' => date("Y-m-d  H:i:s"),
					'subject' => ucfirst($this->input->post('subject')),
					'pesan'   => ucfirst($this->input->post('pesan')),
					'id_user' => $this->session->userdata('id_user')
				);

				//Query update data yang ditampung ke dalam database. tersimpan ditabel informasi
				$this->db->where('id_informasi', $id);
				$this->db->update('informasi', $data);

				//Set pemberitahuan bahwa data informasi berhasil diupdate
				$this->session->set_flashdata('status', 'Changed');
				//Kembali ke halaman informasi (index)
				redirect('Informasi');
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		}
	}
}