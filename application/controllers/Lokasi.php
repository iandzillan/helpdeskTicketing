<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi extends CI_Controller
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
			//Menyusun template List Lokasi
			$data['title'] 	  = "Lokasi";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "lokasi/index";

        	//Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

        	//Daftar semua lokasi, get dari model_app (lokasi), data akan ditampung dalam parameter 'lokasi'
			$data['lokasi'] = $this->model_app->lokasi()->result();

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
		//Form validasi untuk lokasi dengan nama validasi = lokasi
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|is_unique[lokasi.lokasi]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Location.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Location Already Exists.
							   </div>'
			)
		);

		//Kondisi jika proses tambah tidak memenuhi syarat validasi akan dikembalikan ke form tambah lokasi
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template List Lokasi
				$data['title'] 	  = "Lokasi";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['modal_show'] = "$('#modal-fade').modal('show');";
				$data['body']     = "lokasi/index";

        		//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

        		//Daftar semua lokasi, get dari model_app (lokasi), data akan ditampung dalam parameter 'lokasi'
				$data['lokasi'] = $this->model_app->lokasi()->result();

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage 
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil menambah lokasi
			//Data lokasi ditampung dalam bentuk array
			$data = array(
				'lokasi' => ucfirst($this->input->post('lokasi'))
			);

			//Query insert data yang ditampung ke dalam database. tersimpan ditabel lokasi
			$this->db->insert('lokasi', $data);

			//Set pemberitahuan bahwa data lokasi berhasil ditambahkan
			$this->session->set_flashdata('status', 'Added');
			//Kembali ke halaman lokasi (index)
			redirect('Lokasi');
		}
	}

	public function hapus($id)
	{
		//Menghapus data dengan kondisi jika $id sama dengan id_lokasi yang kita pilih
		//Query menghapus data pada tabel lokasi berdasarkan id_lokasi
		$this->db->where('id_lokasi', $id);
		$this->db->delete('lokasi');

		//Set pemberitahuan bahwa data lokasi berhasil dihapus
		$this->session->set_flashdata('status', 'Deleted');
		//Kembali ke halaman lokasi (index)
		redirect('Lokasi');
	}

	public function edit($id)
	{
		//User harus admin, tidak boleh role user lain
		if($this->session->userdata('level') == "Admin"){
			//Menyusun template Edit Lokasi
			$data['title'] 	  = "Edit Department";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "lokasi/edit";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Get data lokasi yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getlokasi)
			$data['lokasi'] = $this->model_app->getlokasi($id)->row_array();
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
		//Form validasi untuk lokasi dengan nama validasi = lokasi
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'required|is_unique[lokasi.lokasi]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Location.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Location Already Exists.
							   </div>'
			)
		);

		//Kondisi jika saat proses update tidak memenuhi syarat validasi akan dikembalikan ke halaman edit lokasi
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template Edit Lokasi
				$data['title'] 	  = "Edit Department";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['body']     = "lokasi/edit";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Get data lokasi yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getlokasi)
				$data['lokasi'] = $this->model_app->getlokasi($id)->row_array();
				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil update lokasi
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Data lokasi ditampung dalam bentuk array
				$data = array(
					'lokasi' => ucfirst($this->input->post('lokasi'))
				);

				//Query update data yang ditampung ke dalam database. tersimpan ditabel lokasi
				$this->db->where('id_lokasi', $id);
				$this->db->update('lokasi', $data);

				//Set pemberitahuan bahwa data lokasi berhasil diupdate
				$this->session->set_flashdata('status', 'Changed');
				//Kembali ke halaman lokasi (index)
				redirect('Lokasi');
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		}
	}
}