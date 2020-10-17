<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subkategori extends CI_Controller
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
			//Menyusun template List sub kategori
			$data['title'] 	  = "Sub Category";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "subkategori/index";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Daftar semua sub kategori, get dari model_app (subkategori), data akan ditampung dalam parameter 'subkat'
			$data['subkat'] = $this->model_app->subkategori()->result();

			//Dropdown pilih kategori, menggunakan model_app (dropdown_kategori), nama kategori ditampung pada 'dd_kategori', data yang akan di simpan adalah id_kategori dan akan ditampung pada 'id_kategori'
			$data['dd_kategori'] = $this->model_app->dropdown_kategori();
			$data['id_kategori'] = "";

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
		//Menghapus data dengan kondisi jika $id sama dengan id_sub_kategori yang kita pilih
		//Query menghapus data pada tabel sub_kategori berdasarkan id_sub_kategori
		$this->db->where('id_sub_kategori', $id);
		$this->db->delete('sub_kategori');

		//Set pemberitahuan bahwa data sub kategori berhasil dihapus
		$this->session->set_flashdata('status', 'Deleted');
		//Kembali ke halaman sub kategori (index)
		redirect('Subkategori');
	}

	public function tambah()
	{
		//Form validasi untuk nama_sub_kategori dengan nama validasi = nama_sub_kategori
		$this->form_validation->set_rules('nama_sub_kategori', 'Nama_sub_kategori', 'required|is_unique[sub_kategori.nama_sub_kategori]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Sub Category.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Sub Category Already Exists.
							   </div>'
			)
		);

		//Form validasi untuk id_kategori dengan nama validasi = id_kategori
		$this->form_validation->set_rules('id_kategori', 'Id_kategori', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Choose Category.
							   </div>'
			)
		);

		//Kondisi jika proses tambah sub kategori tidak memenuhi syarat validasi akan dikembalikan ke form tambah sub kategori
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template List sub kategori
				$data['title'] 	  = "Sub Category";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['modal_show'] = "$('#modal-fade').modal('show');";
				$data['body']     = "subkategori/index";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Daftar semua sub kategori, get dari model_app (subkategori), data akan ditampung dalam parameter 'subkat'
				$data['subkat'] = $this->model_app->subkategori()->result();

				//Dropdown pilih kategori, menggunakan model_app (dropdown_kategori), nama kategori ditampung pada 'dd_kategori', data yang akan di simpan adalah id_kategori dan akan ditampung pada 'id_kategori'
				$data['dd_kategori'] = $this->model_app->dropdown_kategori();
				$data['id_kategori'] = "";

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil menambah sub kategori
			//Data sub kategori ditampung dalam bentuk array
			$data = array(
				'nama_sub_kategori' => ucfirst($this->input->post('nama_sub_kategori')),
				'id_kategori'       => $this->input->post('id_kategori')
			);

			//Query insert data yang ditampung ke dalam database. tersimpan ditabel sub_kategori
			$this->db->insert('sub_kategori', $data);

			//Set pemberitahuan bahwa data sub kategori berhasil ditambahkan
			$this->session->set_flashdata('status', 'Added');
			//Kembali ke halaman sub kategori (index)
			redirect('Subkategori');
		}
	}

	public function edit($id)
	{
		//User harus admin, tidak boleh role user lain
		if($this->session->userdata('level') == "Admin"){
			//Menyusun template Edit sub kategori
			$data['title'] 	  = "Edit Sub Category";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "subkategori/edit";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Query untuk mengambil data sub kategori yang akan diedit, query ditampung dalam variabel '$row'
			$row = $this->db->query("SELECT * FROM sub_kategori WHERE id_sub_kategori = '$id'")->row();

			//Mengambil data id_sub_kategori yang sesuai dengan $id yang dipilih dan ditampung pada variabel $data dengan nama = id_sub_kategori
			$data['id_sub_kategori'] 	= $id;
			//Mengambil data nama_sub_kategori melalui query $row dan ditampung pada variabel $data dengan nama = nama_sub_kategori
			$data['nama_sub_kategori'] 	= $row->nama_sub_kategori;

			//Dropdown pilih kategori, menggunakan model_app (dropdown_kategori), nama kategori ditampung pada 'dd_kategori', dan mengambil id_kategori melalui $row dan ditampung pada variabel $data dengan nama = id_kategori
			$data['dd_kategori'] = $this->model_app->dropdown_kategori();
			$data['id_kategori'] = $row->id_kategori;

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
		//Form validasi untuk nama_sub_kategori dengan nama validasi = nama_sub_kategori
		$this->form_validation->set_rules('nama_sub_kategori', 'Nama_sub_kategori', 'required|is_unique[sub_kategori.nama_sub_kategori]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Sub Category.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Sub Category Already Exists.
							   </div>'
			)
		);

		//Form validasi untuk id_kategori dengan nama validasi = id_kategori
		$this->form_validation->set_rules('id_kategori', 'Id_kategori', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Choose Category.
							   </div>'
			)
		);

		//Kondisi jika proses edit sub kategori tidak memenuhi syarat validasi akan dikembalikan ke form edit sub kategori
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template Edit sub kategori
				$data['title'] 	  = "Edit Sub Category";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['body']     = "subkategori/edit";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Query untuk mengambil data sub kategori yang akan diedit, query ditampung dalam variabel '$row'
				$row = $this->db->query("SELECT * FROM sub_kategori WHERE id_sub_kategori = '$id'")->row();

				//Mengambil data id_sub_kategori yang sesuai dengan $id yang dipilih dan ditampung pada variabel $data dengan nama = id_sub_kategori
				$data['id_sub_kategori'] 	= $id;
				//Mengambil data nama_sub_kategori melalui query $row dan ditampung pada variabel $data dengan nama = nama_sub_kategori
				$data['nama_sub_kategori'] 	= $row->nama_sub_kategori;

				//Dropdown pilih kategori, menggunakan model_app (dropdown_kategori), nama kategori ditampung pada 'dd_kategori', dan mengambil id_kategori melalui $row dan ditampung pada variabel $data dengan nama = id_kategori
				$data['dd_kategori'] = $this->model_app->dropdown_kategori();
				$data['id_kategori'] = $row->id_kategori;

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil update sub kategori
			if($this->session->userdata('level') == "Admin"){
				//Data sub kategori ditampung dalam bentuk array
				$data = array(
					'nama_sub_kategori' => ucfirst($this->input->post('nama_sub_kategori')),
					'id_kategori'       => $this->input->post('id_kategori'),
				);

				//Query update data yang ditampung ke dalam database. tersimpan ditabel sub_kategori
				$this->db->where('id_sub_kategori', $id);
				$this->db->update('sub_kategori', $data);

				//Set pemberitahuan bahwa data sub kategori berhasil diupdate
				$this->session->set_flashdata('status', 'Changed');
				//Kembali ke halaman sub kategori (index)
				redirect('Subkategori');
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		}
	}
}