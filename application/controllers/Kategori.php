<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kategori extends CI_Controller{
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
			//Menyusun template List kategori
			$data['title'] 	  = "Category";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "kategori/index";

        	//Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

        	//Daftar semua kategori, get dari model_app (kategori), data akan ditampung dalam parameter 'kategori'
			$data['kategori'] = $this->model_app->kategori()->result();

			//Load template
			$this->load->view('template', $data);
		} else {
			//Bagian ini jika role yang mengakses tidak sama dengan admin
			//Akan dibawa ke Controller Errorpage
			redirect('Errorpage');
		}
	}

	public function hapus($id)
	{
		//Menghapus data dengan kondisi jika $id sama dengan id_kategori yang kita pilih
		//Query menghapus data pada tabel kategori berdasarkan id_kategori
		$this->db->where('id_kategori', $id);
		$this->db->delete('kategori');

		//Set pemberitahuan bahwa data kategori berhasil dihapus
		$this->session->set_flashdata('status', 'Deleted');
		//Kembali ke halaman sub departemen (index)
		redirect('Kategori');
	}

	public function tambah()
	{
		//Form validasi untuk nama_kategori dengan nama validasi = nama_kategori
		$this->form_validation->set_rules('nama_kategori', 'Nama_kategori', 'required|is_unique[kategori.nama_kategori]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Category.
								</div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Category Already Exists.
							   </div>'
			)
		);

		//Kondisi jika proses tambah kategori tidak memenuhi syarat validasi akan dikembalikan ke form tambah kategori
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template List kategori
				$data['title'] 	  = "Category";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['modal_show'] = "$('#modal-fade').modal('show');";
				$data['body']     = "kategori/index";

        		//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

        		//Daftar semua kategori, get dari model_app (kategori), data akan ditampung dalam parameter 'kategori'
				$data['kategori'] = $this->model_app->kategori()->result();

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil menambah kategori
			//Data kategori ditampung dalam bentuk array
			$data = array(
				'nama_kategori' => ucfirst($this->input->post('nama_kategori'))
			);

			//Query insert data yang ditampung ke dalam database. tersimpan ditabel kategori
			$this->db->insert('kategori', $data);

			//Set pemberitahuan bahwa data kategori berhasil ditambahkan
			$this->session->set_flashdata('status', 'Added');
			//Kembali ke halaman kategori (index)
			redirect('Kategori');
		}
	}

	public function edit($id)
	{
		//User harus admin, tidak boleh role user lain
		if($this->session->userdata('level') == "Admin"){
			//Menyusun template Edit kategori
			$data['title'] 	  = "Edit Category";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "kategori/edit";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Get data kategori yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getkategeori)
			$data['kategori'] = $this->model_app->getkategeori($id)->row_array();
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
		//Form validasi untuk nama kategori dengan nama validasi = nama_kategori
		$this->form_validation->set_rules('nama_kategori', 'Nama_kategori', 'required|is_unique[kategori.nama_kategori]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Category.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Category Already Exists.
							   </div>'
			)
		);

		//Kondisi jika saat proses update tidak memenuhi syarat validasi akan dikembalikan ke halaman edit kategori
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template Edit kategori
				$data['title'] 	  = "Edit Category";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['body']     = "kategori/edit";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Get data kategori yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getkategeori)
				$data['kategori'] = $this->model_app->getkategeori($id)->row_array();
				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil update kategori
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Data kategori ditampung dalam bentuk array
				$data = array(
					'nama_kategori' => ucfirst($this->input->post('nama_kategori'))
				);

				//Query update data yang ditampung ke dalam database. tersimpan ditabel kategori
				$this->db->where('id_kategori', $id);
				$this->db->update('kategori', $data);

				//Set pemberitahuan bahwa data kategori berhasil diupdate
				$this->session->set_flashdata('status', 'Changed');
				//Kembali ke halaman departemen (index)
				redirect('Kategori');
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		}
	}
}