<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kondisi extends CI_Controller
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
			//Menyusun template List kondisi
			$data['title'] 	  = "Priority";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "kondisi/index";

        	//Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

        	//Daftar semua kondisi, get dari model_app (kondisi), data akan ditampung dalam parameter 'kondisi'
			$data['kondisi'] = $this->model_app->kondisi()->result();

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
		//Form validasi untuk nama kondisi dengan nama validasi = nama_kondisi
		$this->form_validation->set_rules('nama_kondisi', 'Nama_kondisi', 'required|is_unique[kondisi.nama_kondisi]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Priority.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Priority Already Exists.
							   </div>'
			)
		);

		//Form validasi untuk waktu resolusi dengan nama validasi = waktu_respon
		$this->form_validation->set_rules('waktu_respon', 'Waktu_respon', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Time.
							   </div>'
			)
		);

		//Kondisi jika proses tambah tidak memenuhi syarat validasi akan dikembalikan ke form tambah kondisi
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template List kondisi
				$data['title'] 	  = "Priority";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['modal_show'] = "$('#modal-fade').modal('show');";
				$data['body']     = "kondisi/index";

        		//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

        		//Daftar semua kondisi, get dari model_app (kondisi), data akan ditampung dalam parameter 'kondisi'
				$data['kondisi'] = $this->model_app->kondisi()->result();

				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil menambah kondisi
			//Data kondis ditampung dalam bentuk array
			$data = array(
				'nama_kondisi' => ucfirst($this->input->post('nama_kondisi')),
				'waktu_respon' => $this->input->post('waktu_respon'),
				'warna'		   => strtoupper($this->input->post('warna'))
			);

			//Query insert data yang ditampung ke dalam database. tersimpan ditabel kondisi
			$this->db->insert('kondisi', $data);

			//Set pemberitahuan bahwa data kondisi berhasil ditambahkan
			$this->session->set_flashdata('status', 'Added');
			//Kembali ke halaman kondisi (index)
			redirect('Kondisi');
		}
	}

	public function hapus($id)
	{
		//Menghapus data dengan kondisi jika $id sama dengan id_kondisi yang kita pilih
		//Query menghapus data pada tabel kondisi berdasarkan id_kondisi
		$this->db->where('id_kondisi', $id);
		$this->db->delete('kondisi');

		//Set pemberitahuan bahwa data kondisi berhasil dihapus
		$this->session->set_flashdata('status', 'Deleted');
		//Kembali ke halaman kondisi (index)
		redirect('Kondisi');
	}

	public function edit($id)
	{
		//User harus admin, tidak boleh role user lain
		if($this->session->userdata('level') == "Admin"){
			//Menyusun template edit kondisi
			$data['title'] 	  = "Edit Priority";
			$data['navbar']   = "navbar";
			$data['sidebar']  = "sidebar";
			$data['body']     = "kondisi/edit";

	        //Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			//Get data kondisi yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getkondisi)
			$data['kondisi'] = $this->model_app->getkondisi($id)->row_array();
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
		//Form validasi untuk nama kondisi dengan nama validasi = nama_kondisi
		$this->form_validation->set_rules('nama_kondisi', 'Nama_kondisi', 'required|is_unique[kondisi.nama_kondisi]',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Priority.
							   </div>',
				'is_unique' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> This Priority Already Exists.
							   </div>'
			)
		);

		//Form validasi untuk waktu resolusi dengan nama validasi = waktu_respon
		$this->form_validation->set_rules('waktu_respon', 'Waktu_respon', 'required',
			array(
				'required' => '<div class="alert alert-danger alert-dismissable">
									<strong>Failed!</strong> Please Enter the Time.
							   </div>'
			)
		);

		//Kondisi jika proses edit tidak memenuhi syarat validasi akan dikembalikan ke form edit kondisi
		if($this->form_validation->run() == FALSE){
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Menyusun template edit kondisi
				$data['title'] 	  = "Edit Priority";
				$data['navbar']   = "navbar";
				$data['sidebar']  = "sidebar";
				$data['body']     = "kondisi/edit";

	        	//Session
				$id_dept = $this->session->userdata('id_dept');
				$id_user = $this->session->userdata('id_user');

				//Get data kondisi yang akan diedit sesuai dengan id yang kita pilih, get dari model_app (getkondisi)
				$data['kondisi'] = $this->model_app->getkondisi($id)->row_array();
				//Load template
				$this->load->view('template', $data);
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		} else {
			//Bagian ini jika validasi dipenuhi, maka berhasil update kondisi
			//User harus admin, tidak boleh role user lain
			if($this->session->userdata('level') == "Admin"){
				//Data kondisi ditampung dalam bentuk array
				$data = array(
					'nama_kondisi' => ucfirst($this->input->post('nama_kondisi')),
					'waktu_respon' => $this->input->post('waktu_respon'),
					'warna'		   => strtoupper($this->input->post('warna'))
				);

				//Query update data yang ditampung ke dalam database. tersimpan ditabel kondisi
				$this->db->where('id_kondisi', $id);
				$this->db->update('kondisi', $data);

				//Set pemberitahuan bahwa data kondisi berhasil diupdate
				$this->session->set_flashdata('status', 'Changed');
				//Kembali ke halaman kondisi (index)
				redirect('Kondisi');
			} else {
				//Bagian ini jika role yang mengakses tidak sama dengan admin
				//Akan dibawa ke Controller Errorpage
				redirect('Errorpage');
			}
		}
	}
}