<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subdept extends CI_Controller
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
			//Menyusun template List sub departemen
			$data['title'] 	  = "Sub Department";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "subdept/index";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Daftar semua sub departemen, get dari model_app (subdepartemen), data akan ditampung dalam parameter 'sub'
			$data['sub'] = $this->model_app->subdepartemen()->result();

			//Dropdown pilih departemen, menggunakan model_app (dropdown_departemen), nama departemen ditampung pada 'dd_departemen', data yang akan di simpan adalah id_departemen dan akan ditampung pada 'id_departemen'
			$data['dd_departemen'] = $this->model_app->dropdown_departemen();
			$data['id_departemen'] = "";

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
		//Form validasi untuk nama_bagian_dept dengan nama validasi = nama_bagian_dept
		$this->form_validation->set_rules('nama_bagian_dept', 'Nama_bagian_dept', 'required|is_unique[bagian_departemen.nama_bagian_dept]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Sub Department.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Sub Department Already Exists.
							   </div>'
			)
		);

		//Form validasi untuk id_departemen dengan nama validasi = id_departemen
		$this->form_validation->set_rules('id_departemen', 'Id_departemen', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Choose Department.
							   </div>'
			)
		);

		//Kondisi jika proses tambah sub departemen tidak memenuhi syarat validasi akan dikembalikan ke form tambah sub departemen
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template List sub departemen dengan form tambah sub departemen
				$data['title'] 	  = "Sub Department";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['modal_show'] = "$('#modal-fade').modal('show');";
				$data['body']     = "subdept/index";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Daftar semua sub departemen, get dari model_app (subdepartemen), data akan ditampung dalam parameter 'sub'
				$data['sub'] = $this->model_app->subdepartemen()->result();

				//Dropdown pilih departemen, menggunakan model_app (dropdown_departemen), nama departemen ditampung pada 'dd_departemen', data yang akan di simpan adalah id_departemen dan akan ditampung pada 'id_departemen'
				$data['dd_departemen'] = $this->model_app->dropdown_departemen();
				$data['id_departemen'] = "";

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil menambah sub departemen
			//Data sub departemen ditampung dalam bentuk array
			$data = array(
				'nama_bagian_dept' => ucfirst($this->input->post('nama_bagian_dept')),
				'id_dept'          => $this->input->post('id_departemen')
			);

			//Query insert data yang ditampung ke dalam database. tersimpan ditabel bagian_departemen
			$this->db->insert('bagian_departemen', $data);

			//Set pemberitahuan bahwa data sub departemen berhasil ditambahkan
			$this->session->set_flashdata('status', 'Added');
			//Kembali ke halaman sub departemen (index)
			redirect('Subdept');
		}
	}

	public function hapus($id)
	{
		//Menghapus data dengan kondisi jika $id sama dengan id_bagian_dept yang kita pilih
		//Query menghapus data pada tabel bagian_departemen berdasarkan id_bagian_dept
		$this->db->where('id_bagian_dept', $id);
		$this->db->delete('bagian_departemen');

		//Set pemberitahuan bahwa data sub departemen berhasil dihapus
		$this->session->set_flashdata('status', 'Deleted');
		//Kembali ke halaman sub departemen (index)
		redirect('Subdept');
	}

	public function edit($id)
	{
		//User harus admin, tidak boleh role user lain
		if($this->session->userdata('level') == "Admin"){
			//Menyusun template Edit sub departemen
			$data['title'] 	  = "Edit Sub Department";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "subdept/edit";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Query untuk mengambil data sub departemen yang akan diedit, query ditampung dalam variabel '$row' untuk memanggil setiap data pada 1 sub departemen
			$row = $this->db->query("SELECT * FROM bagian_departemen WHERE id_bagian_dept = '$id'")->row();

			//Mengambil data id_bagian_dept yang sesuai dengan $id yang dipilih dan ditampung pada variabel $data dengan nama = id_bagian_dept
			$data['id_bagian_dept'] = $id;
			//Mengambil data nama_bagian_dept melalui query $row dan ditampung pada variabel $data dengan nama = nama_bagian_dept
			$data['nama_bagian_dept'] = $row->nama_bagian_dept;

			//Dropdown pilih departemen, menggunakan model_app (dropdown_departemen), nama departemen ditampung pada 'dd_departemen', dan mengambil id_departemen melalui $row dan ditampung pada variabel $data dengan nama = id_departemen
			$data['dd_departemen'] = $this->model_app->dropdown_departemen();
			$data['id_departemen'] = $row->id_dept;

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
		//Form validasi untuk nama_bagian_dept dengan nama validasi = nama_bagian_dept
		$this->form_validation->set_rules('nama_bagian_dept', 'Nama_bagian_dept', 'required|is_unique[bagian_departemen.nama_bagian_dept]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Sub Department.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Sub Department Already Exists.
							   </div>'
			)
		);

		//Form validasi untuk id_departemen dengan nama validasi = id_departemen
		$this->form_validation->set_rules('id_departemen', 'Id_departemen', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Choose Department.
							   </div>'
			)
		);

		//Kondisi jika proses update sub departemen tidak memenuhi syarat validasi akan dikembalikan ke halaman edit sub departemen
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template Edit sub departemen
				$data['title'] 	  = "Edit Sub Department";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['body']     = "subdept/edit";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Query untuk mengambil data sub departemen yang akan diedit, query ditampung dalam variabel '$row' untuk memanggil setiap data pada 1 sub departemen
				$row = $this->db->query("SELECT * FROM bagian_departemen WHERE id_bagian_dept = '$id'")->row();

				//mengambil data id_bagian_dept yang sesuai dengan $id yang dipilih dan ditampung pada variabel $data dengan nama = id_bagian_dept
				$data['id_bagian_dept'] = $id;
				//mengambil data nama_bagian_dept melalui query $row dan ditampung pada variabel $data dengan nama = nama_bagian_dept
				$data['nama_bagian_dept'] = $row->nama_bagian_dept;

				//Dropdown pilih departemen, menggunakan model_app (dropdown_departemen), nama departemen ditampung pada 'dd_departemen', dan mengambil id_departemen melalui $row dan ditampung pada variabel $data dengan nama = id_departemen
				$data['dd_departemen'] = $this->model_app->dropdown_departemen();
				$data['id_departemen'] = $row->id_dept;

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil update sub departemen
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Data sub departemen ditampung dalam bentuk array
				$data = array(
					'nama_bagian_dept' => ucfirst($this->input->post('nama_bagian_dept')),
					'id_dept'          => $this->input->post('id_departemen'),
				);

				//Query update data yang ditampung ke dalam database. tersimpan ditabel bagian_departemen
				$this->db->where('id_bagian_dept', $id);
				$this->db->update('bagian_departemen', $data);

				//Set pemberitahuan bahwa data sub_departemen berhasil diupdate
				$this->session->set_flashdata('status', 'Changed');
				//Kembali ke halaman sub departemen (index)
				redirect('Subdept');
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		}
	}
}