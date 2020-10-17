<?php
defined('BASEPATH') or exit('No direct script access allowed');

//////////////////////////////////////Controller ini sebagai controller bantu untuk dropdown teknisi, sub kategori, dan sub departemen//////////////////////////////////////

class Select extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//Meload model_app
		$this->load->model('model_app');
	}

	//Method yang berguna untuk menampilkan history tiket yang sedang dikerjakan oleh teknisi yang dipilih admin pada saat admin melakukan penugasan tiket ke teknisi
	public function select_job()
	{
		//Mengambil input teknisi yang dipilih oleh admin 
		$id_teknisi = $this->input->post('id_teknisi');
		//Query untuk mengambil data tiket yang sedang dikerjakan oleh teknisi yang dipilih oleh admin
		$data['dataassigment'] = $this->db->query("SELECT A.progress, A.status, A.id_ticket, A.tanggal, 
												   B.nama_sub_kategori, C.nama_kategori, D.nama, D.email FROM ticket A 
				                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
				                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
				                                   LEFT JOIN karyawan D ON D.nik = A.reported
				                                   WHERE A.status IN (3,4,5) AND A.teknisi = '$id_teknisi'")->result();

		$row = $this->db->query("SELECT A.nama, A.email FROM karyawan A LEFT JOIN user B ON A.nik = B.username WHERE B.username = '$id_teknisi'")->row();
		if ($id_teknisi == ""){
			$data['nama']  	= "";
			$data['email']  = "";
		} else {
			$data['nama']  	= $row->nama;
			$data['email']  = $row->email;
		}
		
		//Load template 
		$this->load->view('combo/select_job', $data);
	}

	public function select_kondisi()
	{
		$id_kondisi = $this->input->post('id_kondisi');
		$row = $this->db->query("SELECT waktu_respon FROM kondisi WHERE id_kondisi = '$id_kondisi'")->row();
		if ($id_kondisi == ""){
			$data['waktu_respon']  	= "";
		} else {
			$data['waktu_respon']  	= $row->waktu_respon;
		}

		//Load template
		$this->load->view('combo/select_kondisi', $data);
	}

	//Method yang berguna untuk menampilkan sub kategori dari setiap kategori yang dipilih oleh pegawai saat ingin membuat tiket
	public function select_sub()
	{
		//Mengambil input kategori yang dipilih oleh pegawai
		$id_kategori = $this->input->post('id_kategori');
		
		//Jika pegawai tidak memilih kategori, maka sub kategori tidak akan ditampilkan
		if ($id_kategori == "") {
			//Dropdown pilih sub kategori, menggunakan model_app (dropdown_sub_kategori), nama sub kategori ditampung pada 'dd_sub_kategori', data yang akan di simpan adalah id_sub_kategori dan akan ditampung pada 'id_sub_kategori'
			$data['dd_sub_kategori'] = $this->model_app->dropdown_sub_kategori('');
			$data['id_sub_kategori'] = "";
		} else {
			//Bagian ini adalah kondisi dimana pegawai memilih salah satu kategori, maka sub kategori dari kategori yang dipilih akan ditampilkan
			//Dropdown pilih sub kategori, menggunakan model_app (dropdown_sub_kategori) mengikuti id_kategori yang dipilih, nama sub kategori ditampung pada 'dd_sub_kategori', data yang akan di simpan adalah id_sub_kategori dan akan ditampung pada 'id_sub_kategori'
			$data['dd_sub_kategori'] = $this->model_app->dropdown_sub_kategori($id_kategori);
			$data['id_sub_kategori'] = "";
		}

		//Load template
		$this->load->view('combo/select_sub', $data);
	}

	//Method ini berguna untuk menampilkan sub departemen dari setiap departemen yang dipilih oleh admin saat ingin menambahkan pegawai baru
	public function select_subdept()
	{
		//Mengambil input departemen yang dipilih oleh admin
		$id_departemen = $this->input->post('id_departemen');

		//Jika admin tidak memilih departemen, maka sub departemen tidak akan ditampilkan
		if ($id_departemen == "") {
			//Dropdown pilih sub departemen, menggunakan model_app (dropdown_bagian_departemen), nama sub departemen ditampung pada 'dd_bagian_departemen', data yang akan di simpan adalah id_bagian_departemen dan akan ditampung pada 'id_bagian_departemen'
			$data['dd_bagian_departemen'] = $this->model_app->dropdown_bagian_departemen('');
			$data['id_bagian_departemen'] = "";
		} else {
			//Bagian ini adalah kondisi dimana admin memilih salah satu departemen, maka sub departemen dari departemen yang dipilih akan ditampilkan
			//Dropdown pilih sub departemen, menggunakan model_app (dropdown_bagian_departemen) mengikuti id_dept yang dipilih, nama sub departemen ditampung pada 'dd_bagian_departemen', data yang akan di simpan adalah id_bagian_departemen dan akan ditampung pada 'id_bagian_departemen'
			$data['dd_bagian_departemen'] = $this->model_app->dropdown_bagian_departemen($id_departemen);
			$data['id_bagian_departemen'] = "";
		}

		//Load template
		$this->load->view('combo/select_subdept', $data);
	}

	public function select_email()
	{
		//Mengambil input nik yang dipilih oleh admin
		$id_pegawai 	= $this->input->post('id_pegawai');
		$row 			= $this->db->query("SELECT * FROM karyawan WHERE nik = '$id_pegawai'")->row();
		$data['nama']  	= $row->nama;
		$data['email']  = $row->email;

		$this->load->view('combo/select_email', $data);
	}

	public function select_tahun()
	{
		$id_tahun 	 	 	= $this->input->post('id_tahun');
		$data['tahun']		= $id_tahun;
		$data['stat_bulan'] 	= $this->db->query("SELECT MONTHNAME(tanggal) AS bulan, COUNT(*) AS jumtiket FROM ticket 
					                                   	WHERE YEAR(tanggal) = '$id_tahun'
					                                   	GROUP BY MONTHNAME(tanggal) 
					                                   	ORDER BY MONTH(tanggal) ASC")->result();

		$data['stat_sub_tahun'] = $this->db->query("SELECT B.nama_sub_kategori, COUNT(*) AS totalsub FROM ticket A 
	                                   					LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
				                                    	WHERE YEAR(A.tanggal) = '$id_tahun' AND A.status NOT IN (0)
				                                    	GROUP BY A.id_sub_kategori")->result();

		$data['stat_prio_tahun'] = $this->db->query("SELECT A.id_kondisi, B.nama_kondisi, B.warna, COUNT(*) AS jumkondisi FROM ticket A 
					                                    LEFT JOIN kondisi B ON B.id_kondisi = A.id_kondisi 
					                                    WHERE YEAR(A.tanggal) = '$id_tahun' AND A.status NOT IN (0)
					                                    GROUP BY A.id_kondisi ORDER BY A.id_kondisi ASC")->result();

		$data['stat_status_tahun']= $this->db->query("SELECT status, COUNT(*) AS total FROM ticket WHERE YEAR(tanggal) = '$id_tahun' GROUP BY status ORDER BY status ASC")->result();

		if ($id_tahun == "") {
			//Dropdown pilih sub departemen, menggunakan model_app (dropdown_bagian_departemen), nama sub departemen ditampung pada 'dd_bagian_departemen', data yang akan di simpan adalah id_bagian_departemen dan akan ditampung pada 'id_bagian_departemen'
			$data['dd_bulan'] = $this->model_app->pilih_bulan('');
			$data['id_bulan'] = "";
		} else {
			//Bagian ini adalah kondisi dimana admin memilih salah satu departemen, maka sub departemen dari departemen yang dipilih akan ditampilkan
			//Dropdown pilih sub departemen, menggunakan model_app (dropdown_bagian_departemen) mengikuti id_dept yang dipilih, nama sub departemen ditampung pada 'dd_bagian_departemen', data yang akan di simpan adalah id_bagian_departemen dan akan ditampung pada 'id_bagian_departemen'
			$data['dd_bulan'] = $this->model_app->pilih_bulan($id_tahun);
			$data['id_bulan'] = "";
		}

		$this->load->view('combo/select_tahun', $data);
	}
	public function select_bulan()
	{
		$id_bulan= $this->input->post('id_bulan');
		$data['bulan']= $id_bulan;
		$data['stat_harian'] = $this->db->query("SELECT DATE_FORMAT(tanggal, '%d-%b') AS hari, COUNT(*) AS jumlah FROM ticket 
												WHERE DATE_FORMAT(tanggal, '%Y/ %M') = '$id_bulan'
												GROUP BY DAY(tanggal)")->result();

		$data['stat_sub_bulan'] = $this->db->query("SELECT B.nama_sub_kategori, COUNT(*) AS total FROM ticket A 
                                   					LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
			                                    	WHERE DATE_FORMAT(A.tanggal, '%Y/ %M')='$id_bulan' AND A.status NOT IN (0)
			                                    	GROUP BY A.id_sub_kategori")->result();

		$data['stat_prio_bulan'] = $this->db->query("SELECT A.id_kondisi, B.nama_kondisi, B.warna, COUNT(*) AS jumkondisi FROM ticket A 
					                                    LEFT JOIN kondisi B ON B.id_kondisi = A.id_kondisi 
					                                    WHERE DATE_FORMAT(A.tanggal, '%Y/ %M')='$id_bulan' AND A.status NOT IN (0)
					                                    GROUP BY A.id_kondisi ORDER BY A.id_kondisi ASC")->result();

		$data['stat_status_bulan']= $this->db->query("SELECT status, COUNT(*) AS total FROM ticket WHERE DATE_FORMAT(tanggal, '%Y/ %M')='$id_bulan' GROUP BY status ORDER BY status ASC")->result();

		$this->load->view('combo/select_bulan', $data);
	}
}