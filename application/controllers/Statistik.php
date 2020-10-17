<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Statistik extends CI_Controller
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
			//Menyusul telplate dashboard
			$data['title'] 		= "Statistic";
			$data['navbar']     = "navbar";
			$data['sidebar']	= "sidebar";
			$data['body'] 		= "statistik/statistik";

        	//Session
			$id_dept = $this->session->userdata('id_dept');
			$id_user = $this->session->userdata('id_user');

			$data['stat_tahun'] = $this->model_app->Stat_Tahun()->result();

			$data['dd_tahun'] = $this->model_app->pilih_tahun();
			$data['id_tahun'] = "";

			$data['dd_bulan'] = $this->model_app->pilih_bulan('');
			$data['id_bulan'] = "";

			$this->load->view('template', $data);
		} else {
			//Bagian ini jika role yang mengakses tidak sama dengan admin
			//Akan dibawa ke Controller Errorpage 
			redirect('Errorpage');
		}
	}

	public function report()
	{
		if($this->session->userdata('level') == "Admin"){
			$tgl1 = $this->input->post('tgl1');
			$tgl2 = $this->input->post('tgl2');

			$report = $this->model_app->report($tgl1, $tgl2)->result();

			$spreadsheet = new Spreadsheet;

			$spreadsheet->getProperties()
			->setCreator("GENESYS - LBE IT Support")
			->setLastModifiedBy("GENESYS - LBE IT Support")
			->setTitle("Report of ticket")
			->setSubject("Report of ticket")
			->setDescription("Report document of ticket, generated using PHP classes.")
			->setCategory("Report document");

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'No');
			$sheet->setCellValue('B1', 'ID Ticket');
			$sheet->setCellValue('C1', 'Name');
			$sheet->setCellValue('D1', 'Date Submited');
			$sheet->setCellValue('E1', 'Deadline Date');
			$sheet->setCellValue('F1', 'Last Update');
			$sheet->setCellValue('G1', 'Priority');
			$sheet->setCellValue('H1', 'Status');
			$sheet->setCellValue('I1', 'Location');
			$sheet->setCellValue('J1', 'Category');
			$sheet->setCellValue('K1', 'Sub Category');
			$sheet->setCellValue('L1', 'Assigned Techinician');
			$sheet->setCellValue('M1', 'Subject');

			$nomor = 1;
			$baris = 2;
			$status="";

			foreach ($report as $key) {
				if ($key->status == 0) {
					$status= "Ticket Rejected";
				} else if ($key->status == 1) {
					$status= "Ticket Submited";
				} else if ($key->status == 2) {
					$status= "Category Changed";
				} else if ($key->status == 3) {
					$status= "Technician selected";
				} else if ($key->status == 4) {
					$status= "On Process";
				} else if ($key->status == 5) {
					$status= "Pending";
				} else if ($key->status == 6) {
					$status= "Solve";
				} else if ($key->status == 7) {
					$status= "Late Finished";
				}
				$sheet->setCellValue('A'.$baris, $nomor);
				$sheet->setCellValue('B'.$baris, $key->id_ticket);
				$sheet->setCellValue('C'.$baris, $key->nama);
				$sheet->setCellValue('D'.$baris, $key->tanggal);
				$sheet->setCellValue('E'.$baris, $key->deadline);
				$sheet->setCellValue('F'.$baris, $key->last_update);
				$sheet->setCellValue('G'.$baris, $key->nama_kondisi);
				$sheet->setCellValue('H'.$baris, $status);
				$sheet->setCellValue('I'.$baris, $key->lokasi);
				$sheet->setCellValue('J'.$baris, $key->nama_kategori);
				$sheet->setCellValue('K'.$baris, $key->nama_sub_kategori);
				$sheet->setCellValue('L'.$baris, $key->nama_teknisi);
				$sheet->setCellValue('M'.$baris, $key->problem_summary);

				$nomor++;
				$baris++;
			}

			$writer = new Xlsx($spreadsheet);

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Report.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
		} else {
			//Bagian ini jika role yang mengakses tidak sama dengan admin
			//Akan dibawa ke Controller Errorpage 
			redirect('Errorpage');
		}
	}
}