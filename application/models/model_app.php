<?php

class Model_app extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        $config = Array(  
          'protocol'  => 'smtp',  
          'smtp_host' => 'ssl://smtp.googlemail.com',  
          'smtp_port' => 465,  
          'smtp_user' => 'yourEmail',   
          'smtp_pass' => 'yourEmailPassword',
          'mailtype'  => 'html',
          'starttls'  => true,
          'newline'   => "\r\n" 
        );  

        $this->email->initialize($config);
    }

//////////////////////////////////////////////////////////////////////////////////Bagian Admin//////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////Bagian Menu Ticket///////////////////////////////////////////////////////////
    //Method untuk mendapatkan semua ticket
    public function all_ticket()
    {
        //Query untuk mendapatkan semua ticket dengan diurutkan berdasarkan tanggal tiket dibuat
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.tanggal, A.last_update, A.id_kondisi, A.deadline, A.teknisi,
                                   A.problem_summary, A.filefoto, B.nama_sub_kategori, C.nama_kategori, 
                                   D.nama, F.nama_dept, G.nama_kondisi, G.warna, G.waktu_respon, H.lokasi, I.nama_jabatan, K.nama AS nama_teknisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept
                                   LEFT JOIN departemen F ON F.id_dept = E.id_dept 
                                   LEFT JOIN kondisi G ON G.id_kondisi = A.id_kondisi
                                   LEFT JOIN lokasi H ON H.id_lokasi = A.id_lokasi
                                   LEFT JOIN jabatan I ON I.id_jabatan = D.id_jabatan
                                   LEFT JOIN karyawan K ON K.nik = A.teknisi
                                   ORDER BY A.tanggal DESC");
        return $query;
    }

    //Method untuk mendapatkan semua ticket yang belum dilakukan approval
    public function approve_ticket($id)
    {
        //Query untuk mendapatkan semua ticket dengan status 0 (Reject) atau 1 (Belum di approve) dengan diurutkan berdasarkan tanggal ticket dibuat
        $query = $this->db->query("SELECT A.status ,A.status, A.id_ticket, A.tanggal, A.id_kondisi, A.deadline,
                                   A.problem_detail, A.problem_summary, A.filefoto, B.nama_sub_kategori, 
                                   C.nama_kategori, D.nama, F.nama_dept, G.nama_kondisi, G.warna, H.lokasi, I.nama_jabatan FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori 
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen F ON F.id_dept = E.id_dept
                                   LEFT JOIN kondisi G ON G.id_kondisi = A.id_kondisi
                                   LEFT JOIN lokasi H ON H.id_lokasi = A.id_lokasi
                                   LEFT JOIN jabatan I ON I.id_jabatan = D.id_jabatan
                                   WHERE A.status IN (1,2)");
        return $query;
    }

    //Method yang digunakan untuk proses reject ticket dengan parameter id_ticket
    public function reject($id)
    {
        //Mengambil session admin
        $id_user    = $this->session->userdata('id_user');

        //Melakukan update data ticket dengan mengubah status ticket menjadi 0, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
        $data = array(
          'status'     => 0,
          'last_update'=> date("Y-m-d  H:i:s")
        );

        //Melakukan insert data tracking ticket bahwa ticket di-reject oleh admin, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
        $datatracking = array(
          'id_ticket'  => $id,
          'tanggal'    => date("Y-m-d  H:i:s"),
          'status'     => "Ticket Rejected",
          'deskripsi'  => "",
          'id_user'    => $id_user
        );

        //Query untuk melakukan update data ticket sesuai dengan array '$data' ke tabel ticket
        $this->db->where('id_ticket', $id);
        $this->db->update('ticket', $data);

        //Query untuk melakukan insert data tracking ticket sesuai dengan array '$datatracking' ke tabel tracking
        $this->db->insert('tracking', $datatracking);
    }

    //Method yang digunakan untuk proses approve ticket dengan parameter (id_ticket)
    public function approve($id)
    {
        $kondisi    = $this->input->post('id_kondisi');
        $sql        = $this->db->query("SELECT tanggal FROM ticket WHERE id_ticket = '$id'")->row();
        $sql2       = $this->db->query("SELECT nama_kondisi FROM kondisi WHERE id_kondisi = '$kondisi'")->row();
        //Data
        $prio       = $sql2->nama_kondisi;
        $date       = $sql->tanggal;
        $date2      = $this->input->post('waktu_respon');
        //Mengambil session admin
        $id_user    = $this->session->userdata('id_user');

        //Melakukan update data ticket dengan mengubah status ticket menjadi 2, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
        $data = array(
          'id_kondisi' => $kondisi,
          'deadline'   => date('Y-m-d H:i:s', strtotime($date. ' + '.$date2.' days')),
          'status'     => 3,
          'last_update'=> date("Y-m-d  H:i:s"),
          'teknisi'    => $this->input->post('id_teknisi')
        );

        //Melakukan insert data tracking ticket bahwa ticket di-approve oleh admin, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
        $datatracking = array(
          'id_ticket'  => $id,
          'tanggal'    => date("Y-m-d  H:i:s"),
          'status'     => "Ticket Received",
          'deskripsi'  => "Priority of the ticket is set to ".$prio." and assigned to technician.",
          'id_user'    => $id_user
        );

        //Query untuk melakukan update data ticket sesuai dengan array '$data' ke tabel ticket
        $this->db->where('id_ticket', $id);
        $this->db->update('ticket', $data);

        //Query untuk melakukan insert data tracking ticket sesuai dengan array '$datatracking' ke tabel tracking
        $this->db->insert('tracking', $datatracking);
    }

    //Method untuk menaruh data user teknisi sesuai dengan kategori yang dipilih pada dropdown
    function dropdown_teknisi()
    { 
        //Query untuk mengambil data user yang memiliki level 'Technician'
        $query = $this->db->query("SELECT A.username, B.nama FROM user A LEFT JOIN karyawan B ON B.nik = A.username WHERE A.level = 'Technician'");

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data user teknisi ke dalam dropdown, value yang akan diambil adalah value id_user yang memiliki level 'Technician'
        foreach ($query->result() as $row) {
            $value[$row->username] = $row->nama;
        }
        return $value;
    }

    //Method yang digunakan untuk proses memilih teknisi untuk ticket dengan parameter (id_ticket)
    public function input_tugas($id)
    {
        //Mengambil session admin
        $id_user    = $this->session->userdata('id_user');

        //Melakukan update data ticket dengan mengubah status ticket menjadi 3 dan memasukkan teknisi yang telah diinput, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
        $data = array(
          'teknisi'    => $this->input->post('id_teknisi'),
          'status'     => 3,
          'last_update'=> date("Y-m-d  H:i:s")
        );

        //Melakukan insert data tracking ticket bahwa ticket sudah ditugaskan kepada teknisi, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
        $datatracking = array(
          'id_ticket'  => $id,
          'tanggal'    => date("Y-m-d  H:i:s"),
          'status'     => "Ticket is assigned to technician",
          'deskripsi'  => "",
          'id_user'    => $id_user
        );

        //Query untuk melakukan update data ticket sesuai dengan array '$data' ke tabel ticket
        $this->db->where('id_ticket', $id);
        $this->db->update('ticket', $data);
        
        //Query untuk melakukan insert data tracking ticket sesuai dengan array '$datatracking' ke tabel tracking
        $this->db->insert('tracking', $datatracking);
    }

    //////////////////////////////////////////////////////////////Bagian Menu Office//////////////////////////////////////////////////////////////
    //Method untuk mengambil semua data departemen
    public function departemen()
    {
        //Query untuk mengambil semua data departemen dan diurutkan berdasarkan nama_dept
        $query = $this->db->query("SELECT * FROM departemen ORDER BY nama_dept");
        return $query;
    }

    //Method untuk mengambil data departemen yang akan diedit dengan parameter id_dept
    public function getdepartemen($id)
    {
        //Query untuk mengambil data departemen berdasarkan id_dept untuk dilakukan edit
        $query = $this->db->query("SELECT * FROM departemen WHERE id_dept = '$id'");
        return $query;
    }

    //Method untuk mengambil semua data sub departemen
    public function subdepartemen()
    {
        //Query untuk mengambil semua data sub departemen dan diurutkan berdasarkan nama_bagian_dept
        $query = $this->db->query("SELECT * FROM bagian_departemen A LEFT JOIN departemen B ON B.id_dept = A.id_dept ORDER BY nama_bagian_dept");
        return $query;
    }

    //Method untuk mengambil semua data jabatan
    public function jabatan()
    {
        //Query untuk mengambil semua data jabatan dan diurutkan berdasarkan nama_jabatan
        $query = $this->db->query("SELECT * FROM jabatan ORDER BY nama_jabatan");
        return $query;
    }

    //Method untuk mengambil data jabatan yang akan diedit dengan parameter id_jabatan
    public function getjabatan($id)
    {
        //Query untuk mengambil data jabtan berdasarkan id_jabatan untuk dilakukan edit
        $query = $this->db->query("SELECT * FROM jabatan WHERE id_jabatan = '$id'");
        return $query;
    }

    //Method untuk mengambil semua data pegawai
    public function pegawai()
    {
        //Query untuk mengambil semua data pegawai dan diurutkan berdasarkan nik
        $query = $this->db->query("SELECT A.nama, A.email, A.nik, B.nama_jabatan, C.nama_bagian_dept, D.nama_dept FROM karyawan A 
                                   LEFT JOIN jabatan B ON B.id_jabatan = A.id_jabatan
                                   LEFT JOIN bagian_departemen C ON C.id_bagian_dept = A.id_bagian_dept
                                   LEFT JOIN departemen D ON D.id_dept = C.id_dept ORDER BY A.nik");
        return $query;
    }

    //Method untuk mengambil semua data lokasi
    public function lokasi()
    {
        //Query untuk mengambil semua data lokasi dengan diurutkan berdasarkan lokasi
        $query = $this->db->query("SELECT * FROM lokasi ORDER BY lokasi");
        return $query;
    }

    //Method untuk mengambil data lokasi yang akan diedit dengan parameter id_lokasi
    public function getlokasi($id)
    {
        //Query untuk mengambil data lokasi berdasarkan id_lokasi untuk dilakukan edit
        $query = $this->db->query("SELECT * FROM lokasi WHERE id_lokasi = '$id'");
        return $query;
    }

    //Method untuk menaruh data departemen pada dropdown
    public function dropdown_departemen()
    {
        //Query untuk mengambil data departemen dan diurutkan berdasarkan nama departemen
        $sql = "SELECT * FROM departemen ORDER BY nama_dept";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data departemen ke dalam dropdown, value yang akan diambil adalah value id_dept
        foreach ($query->result() as $row) {
            $value[$row->id_dept] = $row->nama_dept;
        }
        return $value;
    }

    //Method untuk menaruh data jabatan pada dropdown
    public function dropdown_jabatan()
    {
        //Query untuk mengambil data jabatan dan diurutkan berdasarkan nama jabatan
        $sql = "SELECT * FROM jabatan ORDER BY nama_jabatan";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data jabatan ke dalam dropdown, value yang akan diambil adalah value id_jabatan
        foreach ($query->result() as $row) {
            $value[$row->id_jabatan] = $row->nama_jabatan;
        }
        return $value;
    }

    //Method untuk menaruh data sub departemen sesuai dengan departemen yang dipilih pada dropdown
    public function dropdown_bagian_departemen($id_departemen)
    {
        //Query untuk mengambil data sub departemen dan diurutkan berdasarkan nama sub departemen
        $sql = "SELECT * FROM bagian_departemen where id_dept ='$id_departemen' ORDER BY nama_bagian_dept";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data sub departemen ke dalam dropdown, value yang akan diambil adalah value id_bagian_dept
        foreach ($query->result() as $row) {
            $value[$row->id_bagian_dept] = $row->nama_bagian_dept;
        }
        return $value;
    }

    ///////////////////////////////////////////////////////////Bagian Menu Configuration///////////////////////////////////////////////////////////
    //Method untuk mengambil semua data user 
    public function user()
    {
        //Query untuk mengambil semua data user dan diurutkan berdasarkan level user
        $query = $this->db->query("SELECT A.username, A.level, A.id_user, A.password, B.nik, B.nama, 
                                   B.email, C.nama_bagian_dept, C.id_dept, D.nama_dept FROM user A 
                                   LEFT JOIN karyawan B ON B.nik = A.username 
                                   LEFT JOIN bagian_departemen C ON C.id_bagian_dept = B.id_bagian_dept 
                                   LEFT JOIN departemen D ON D.id_dept = C.id_dept ORDER BY A.level");
        return $query;
    }

    //Method yang digunakan untuk membuat kode user secara otomatis
    public function getkodeuser()
    {
        //Query untuk mengembalikan value terbesar yang ada di kolom id_user
        $query = $this->db->query("SELECT max(id_user) AS max_code FROM user");

        //Menampung fungsi yang akan mengembalikan hasil 1 baris dari query ke dalam variabel $row
        $row = $query->row_array();

        //Menampung hasil kode user terbesar dari query
        $max_id = $row['max_code'];

        //Membuat format kode user dengan dengan memulai kode dari posisi 1 dan panjang kode 4
        $max_fix = (int) substr($max_id, 1, 4);

        //Hasil dari kode terbesar yang sudah didapatkan ditambah dengan 1, hasil dari penjumlahan ini akan digunakan sebagai kode user terbaru
        $max_id_user = $max_fix + 1;

        //Membuat id_user dengan format U + kode user terbaru
        $id_user = "U" . sprintf("%04s", $max_id_user);
        return $id_user;
    }

    //Method untuk menaruh data pegawai pada dropdown
    public function dropdown_pegawai()
    {
        //Query untuk mengambil data pegawai dan diurutkan berdasarkan nama
        $sql = "SELECT A.nama, A.nik FROM karyawan A 
        LEFT JOIN bagian_departemen B ON B.id_bagian_dept = A.id_bagian_dept
        LEFT JOIN departemen C ON C.id_dept = B.id_dept 
        ORDER BY nama";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data pegawai ke dalam dropdown, value yang akan diambil adalah value nik
        foreach ($query->result() as $row) {
            $value[$row->nik] = $row->nama;
        }
        return $value;
    }

    //Method untuk membuat level user
    public function dropdown_level()
    {
        //Menyusun value pada dropdown
        $value[''] = '--CHOOSE--';
        $value['Admin'] = 'Admin';
        $value['Technician'] = 'Technician';
        $value['User'] = 'User';

        return $value;
    }

    //Method untuk mengambil semua data kondisi 
    public function kondisi()
    {
        //Query untuk mengambil semua data kondisi dengan diurutkan berdasarkan waktu respon
        $query = $this->db->query("SELECT * FROM kondisi ORDER BY waktu_respon");
        return $query;
    }

    //Method untuk mengambil data kondisi yang akan diedit dengan parameter id_kondisi
    public function getkondisi($id)
    {
        //Query untuk mengambil data kondisi berdasarkan id_kondisi untuk dilakukan edit
        $query = $this->db->query("SELECT * FROM kondisi WHERE id_kondisi = '$id'");
        return $query;
    }

    //Method untuk menaruh data kondisi pada dropdown
    public function dropdown_kondisi()
    {
        //Query untuk mengambil data kondisi dan diurutkan berdasarkan nama kondisi
        $sql = "SELECT * FROM kondisi ORDER BY waktu_respon";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data kondisi ke dalam dropdown, value yang akan diambil adalah value id_kondisi
        foreach ($query->result() as $row) {
            $value[$row->id_kondisi] = $row->nama_kondisi . "  -  (Process Target " . $row->waktu_respon . " " . "Day)";
        }
        return $value;
    }

    //Method untuk mengambil semua data kategori
    public function kategori()
    {
        //Query untuk mengambil semua data kategori dengan diurutkan berdasarkan nama kategori
        $query = $this->db->query("SELECT * FROM kategori ORDER BY nama_kategori");
        return $query;
    }

    //Method untuk mengambil data kategori yang akan diedit dengan parameter id_kategori
    public function getkategeori($id)
    {
        //Query untuk mengambil data kategori berdasarkan id_kategori untuk dilakukan edit
        $query = $this->db->query("SELECT * FROM kategori WHERE id_kategori = '$id'");
        return $query;
    }

    //Method untuk mengambil semua data sub kategori
    public function subkategori()
    {
        //Query untuk mengambil semua data sub kategori yang di-join dengan tabel kategori berdasarkan id_kategori dan diurutkan berdasarkan nama_kategori
        $query = $this->db->query("SELECT * FROM sub_kategori A LEFT JOIN kategori B ON B.id_kategori = A.id_kategori ORDER BY B.nama_kategori");
        return $query;
    }

    //Method untuk mengambil semua data informasi
    public function informasi()
    {
        //Query untuk mengambil semua data informasi yang diuurutkan berdasarkan tanggal
        $query = $this->db->query("SELECT A.tanggal, A.subject, A.pesan, A.id_informasi, C.nama FROM informasi A 
                                   LEFT JOIN karyawan C ON C.nik =  A.id_user
                                   ORDER BY A.tanggal DESC");
        return $query;
    }

    //Method untuk mengambil data informasi yang akan diedit dengan parameter id_informasi
    public function getinformasi($id)
    {
        //Query untuk mengambil data informasi berdasarkan id_informasi untuk dilakukan edit
        $query = $this->db->query("SELECT * FROM informasi WHERE id_informasi = '$id'");
        return $query;
    }
//////////////////////////////////////////////////////////////////////////////Selesai Bagian Admin//////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////////////////////Bagian Teknisi/////////////////////////////////////////////////////////////////////////////////

    //Method untuk mendapatkan semua ticket yang ditugaskan kepada teknisi dan belum dilakukan approval oleh teknisi
    public function approve_tugas($id)
    {
        //Query untuk mendapatkan semua ticket dengan status 3 (Technician selected) atau 5 (Pending) dengan diurutkan berdasarkan tanggal ticket dibuat
        $query = $this->db->query("SELECT A.progress, A.status, A.id_ticket, A.reported, A.tanggal, A.id_kondisi, A.deadline,
                                   A.problem_detail, A.problem_summary, A.filefoto, B.nama_sub_kategori, 
                                   C.nama_kategori, D.nama, G.nama_kondisi, G.warna, H.lokasi, J.nama_dept FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username
                                   LEFT JOIN kondisi G ON G.id_kondisi = A.id_kondisi
                                   LEFT JOIN lokasi H ON H.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen I ON I.id_bagian_dept = D.id_bagian_dept
                                   LEFT JOIN departemen J ON J.id_dept = I.id_dept
                                   WHERE F.nik = '$id' AND A.status IN (3,5) ORDER BY A.deadline ASC");
        return $query;
    }

    //Method untuk mendapatkan semua ticket yang ditugaskan kepada teknisi dan sudah dilakukan approval oleh teknisi
    public function daftar_tugas($id)
    {
        //Query untuk mendapatkan semua ticket dengan status 4 (On Process) atau 6 (Solve) dengan diurutkan berdasarkan tanggal tiket dibuat
        $query = $this->db->query("SELECT A.progress, A.status, A.id_ticket, A.reported, A.tanggal, A.tanggal_solved, A.id_kondisi, A.deadline,
                                   A.problem_detail, A.problem_summary, A.filefoto, B.nama_sub_kategori, 
                                   C.nama_kategori, D.nama, G.nama_kondisi, G.warna, H.lokasi, J.nama_dept, K.nama_jabatan FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username
                                   LEFT JOIN kondisi G ON G.id_kondisi = A.id_kondisi
                                   LEFT JOIN lokasi H ON H.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen I ON I.id_bagian_dept = D.id_bagian_dept
                                   LEFT JOIN departemen J ON J.id_dept = I.id_dept
                                   LEFT JOIN jabatan K ON K.id_jabatan = D.nik
                                   WHERE F.nik = '$id' AND A.status IN (4,6,7) ORDER BY G.waktu_respon ASC");
        return $query;
    }

    //Method yang digunakan untuk melakukan approval ticket oleh teknisi
    public function approve_tiket($id)
    {
        //Mengambil session teknisi
        $id_user = $this->session->userdata('id_user');

        //Melakukan update data ticket dengan mengubah status ticket menjadi 4 dan memasukkan tanggal tiket mulai diproses, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
        $data = array(
          'status'         => 4,
          'tanggal_proses' => date("Y-m-d  H:i:s"),
          'last_update'    => date("Y-m-d  H:i:s")
        );

        //Melakukan insert data tracking ticket sedang dikerjakan oleh teknisi, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
        $datatracking = array(
          'id_ticket'  => $id,
          'tanggal'    => date("Y-m-d  H:i:s"),
          'status'     => "On Process",
          'deskripsi'  => "",
          'id_user'    => $id_user
        );

        //Query untuk melakukan update data ticket sesuai dengan array '$data' ke tabel ticket
        $this->db->where('id_ticket', $id);
        $this->db->update('ticket', $data);

        //Query untuk melakukan insert data tracking ticket sesuai dengan array '$datatracking' ke tabel tracking
        $this->db->insert('tracking', $datatracking);
    }

    //Method yang digunakan untuk melakukan pending ticket oleh teknisi
    public function pending_tugas($id)
    {
        //Mengambil session teknisi
        $id_user = $this->session->userdata('id_user');

        //Melakukan update data ticket dengan mengubah status ticket menjadi 5, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
        $data = array(
          'status'     => 5,
          'last_update'=> date("Y-m-d  H:i:s")
        );

        //Melakukan insert data tracking ticket di-pending oleh teknisi, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
        $datatracking = array(
          'id_ticket'  => $id,
          'tanggal'    => date("Y-m-d  H:i:s"),
          'status'     => "Pending",
          'deskripsi'  => "",
          'id_user'    => $id_user
        );

        //Query untuk melakukan update data ticket sesuai dengan array '$data' ke tabel ticket
        $this->db->where('id_ticket', $id);
        $this->db->update('ticket', $data);

        //Query untuk melakukan insert data tracking ticket sesuai dengan array '$datatracking' ke tabel tracking
        $this->db->insert('tracking', $datatracking);
    }

    //Method yang digunakan untuk melakukan update progress ticket oleh teknisi
    public function update($id)
    {
        //Mengambil session teknisi
        $id_user  = $this->session->userdata('id_user');

        //Mengambil data progress dan deskripsi untuk update system tracking ticket
        $progress = $this->input->post('progress');
        $date     = date("Y-m-d  H:i:s");     
        $sql      = $this->db->query("SELECT deadline FROM ticket WHERE id_ticket='$id'")->row();     

        //Konfigurasi Upload Gambar
        $config['upload_path']    = './teknisi/';   //Folder untuk menyimpan gambar
        $config['allowed_types']  = 'gif|jpg|jpeg|png|pdf'; //Tipe file yang diizinkan
        $config['max_size']       = '2048';     //Ukuran maksimum file gambar yang diizinkan
        $config['max_width']      = '0';        //Ukuran lebar maks. 0 menandakan ga ada batas
        $config['max_height']     = '0';        //Ukuran tinggi maks. 0 menandakan ga ada batas

        //Memanggil library upload pada codeigniter dan menyimpan konfirguasi
        $this->load->library('upload', $config);

        //Jika upload gambar tidak sesuai dengan konfigurasi di atas, maka upload gambar gagal, dan kembali ke halaman Create ticket
        if (!$this->upload->do_upload('fileupdate')){
            $this->session->set_flashdata('status', 'Something went wrong! Image file is more than 2MB or not supported format');
            redirect('List_ticket_tek/detail_update/'.$id);
        } else {
            //Bagian ini jika file gambar sesuai dengan konfirgurasi di atas
            //Menampung file gambar ke variable 'gambar'
            $gambar   = $this->upload->data();
            //Kondisi jika progress yang sudah selesai, maka status ticket pada system tracking ticket menjadi ticket closed dengan keterangan progress ticketnya juga
            if($progress==100)
            {
                if (date("Y-m-d  H:i:s") > $sql->deadline) {
                  //Melakukan update data ticket dengan mengubah status ticket menjadi 6, memasukkan tanggal proses selesai, dan memasukkan progress dari ticket, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
                  $data = array(
                    'status'         => 7,
                    'last_update'    => $date,
                    'tanggal_solved' => $date,
                    'progress'       => $progress
                  );

                  //Melakukan insert data tracking ticket closed oleh teknisi, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
                  $datatracking = array(
                    'id_ticket'  => $id,
                    'tanggal'    => $date,
                    'status'     => "Ticket Closed. Progress: " . $progress . " %",
                    'deskripsi'  => ucfirst($this->input->post('desk')),
                    'id_user'    => $id_user,
                    'filefoto'   => $gambar['file_name']
                  );
                } else {
                  //Melakukan update data ticket dengan mengubah status ticket menjadi 6, memasukkan tanggal proses selesai, dan memasukkan progress dari ticket, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
                  $data = array(
                    'status'         => 6,
                    'last_update'    => $date,
                    'tanggal_solved' => $date,
                    'progress'       => $progress
                  );

                  //Melakukan insert data tracking ticket closed oleh teknisi, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
                  $datatracking = array(
                    'id_ticket'  => $id,
                    'tanggal'    => $date,
                    'status'     => "Ticket Closed. Progress: " . $progress . " %",
                    'deskripsi'  => ucfirst($this->input->post('desk')),
                    'id_user'    => $id_user,
                    'filefoto'   => $gambar['file_name']
                  );
                }
            } 
            else 
            {
                //Bagian ini jika kondisinya progress ticket belum selesai dikerjakan, maka data yang diupdate hanya status dan progress
                //Melakukan update data ticket dengan mengubah status ticket menjadi 4, dan memasukkan progress dari ticket, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
                $data = array(
                  'status'       => 4,
                  'last_update'  => date("Y-m-d  H:i:s"),
                  'progress'     => $progress
                );

                //Melakukan insert data tracking ticket progress oleh teknisi, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
                $datatracking = array(
                  'id_ticket'  => $id,
                  'tanggal'    => date("Y-m-d  H:i:s"),
                  'status'     => "Progress: " . $progress . " %",
                  'deskripsi'  => ucfirst($this->input->post('desk')),
                  'id_user'    => $id_user, 
                  'filefoto'   => $gambar['file_name']
                );
            }
            //Query untuk melakukan update data ticket sesuai dengan array '$data' ke tabel ticket
            $this->db->where('id_ticket', $id);
            $this->db->update('ticket', $data);

            //Query untuk melakukan insert data tracking ticket sesuai dengan array '$datatracking' ke tabel tracking
            $this->db->insert('tracking', $datatracking);
        }
    }

    public function changekategori($id)
    {
        $kat      = $this->input->post('id_kategori');
        $subkat   = $this->input->post('id_sub_kategori');
        $row      = $this->model_app->getkategeori($kat)->row();
        $key      = $this->db->query("SELECT * FROM sub_kategori WHERE id_sub_kategori = '$subkat'")->row();
        $sql      = $this->db->query("SELECT id_sub_kategori FROM ticket WHERE id_ticket = '$id'")->row();
        $id_user  = $this->session->userdata('id_user');

        if ($subkat == $sql->id_sub_kategori){
          $this->session->set_flashdata('status1', 'You have not changed the sub category!');
          redirect('List_ticket_tek/change/'.$id);
        } else {
          //Konfigurasi Upload Gambar
          $config['upload_path']    = './teknisi/';   //Folder untuk menyimpan gambar
          $config['allowed_types']  = 'gif|jpg|jpeg|png|pdf'; //Tipe file yang diizinkan
          $config['max_size']       = '2048';     //Ukuran maksimum file gambar yang diizinkan
          $config['max_width']      = '0';        //Ukuran lebar maks. 0 menandakan ga ada batas
          $config['max_height']     = '0';        //Ukuran tinggi maks. 0 menandakan ga ada batas

          //Memanggil library upload pada codeigniter dan menyimpan konfirguasi
          $this->load->library('upload', $config);
          //Jika upload gambar tidak sesuai dengan konfigurasi di atas, maka upload gambar gagal, dan kembali ke halaman Create ticket
          if (!$this->upload->do_upload('filediagnosa')){
            $this->session->set_flashdata('status', 'Something went wrong! The file is more than 2MB or not supported format');
            redirect('List_ticket_tek/change/'.$id);
          } else {
            $gambar = $this->upload->data();
            $data = array(
              'id_sub_kategori'   => $subkat,
              'last_update'       => date("Y-m-d  H:i:s"),
              'status'            => 2,
              'teknisi'           => NULL,
              'problem_detail'    => ucfirst($this->input->post('diagnos'))
            );

            $datatracking = array(
              'id_ticket'  => $id,
              'tanggal'    => date("Y-m-d  H:i:s"),
              'status'     => "Category Changed to ".$row->nama_kategori."(".$key->nama_sub_kategori.")",
              'deskripsi'  => ucfirst($this->input->post('diagnos')),
              'id_user'    => $id_user,
              'filefoto'   => $gambar['file_name']
            );
            $this->db->where('id_ticket', $id);
            $this->db->update('ticket', $data);

            $this->db->insert('tracking', $datatracking);
          }
        }
    }
/////////////////////////////////////////////////////////////////////////////Selesai Bagian Teknisi/////////////////////////////////////////////////////////////////////////////




//////////////////////////////////////////////////////////////////////////////////Bagian User//////////////////////////////////////////////////////////////////////////////////

    //Method yang digunakan untuk membuat kode ticket secara otomatis
    public function getkodeticket()
    {
        //Query untuk mengembalikan value terbesar yang ada di kolom id_ticket
        $query = $this->db->query("SELECT max(id_ticket) AS max_code FROM ticket");

        //Menampung fungsi yang akan mengembalikan hasil 1 baris dari query ke dalam variabel $row
        $row = $query->row_array();

        //Menampung hasil kode ticket terbesar dari query
        $max_id = $row['max_code'];
        //Mengambil kode ticket pada database posisi 9 dan panjang kode 4
        $max_fix = (int) substr($max_id, 9, 4);

        //Hasil dari kode terbesar yang sudah didapatkan ditambah dengan 1, hasil dari penjumlahan ini akan digunakan sebagai kode ticket terbaru
        $max_ticket = $max_fix + 1;

        //Mengambil tanggal sekarang
        $tanggal = date("d");
        //Mengambil bulan sekarang
        $bulan = date("m");
        //Mengambil tahun sekarang
        $tahun = date("Y");

        //Membuat id_ticket dengan format T + tahun + bulan + tanggal + kode user terbaru (%04s merupakan fungsi untuk menentukan lebar minimum yang dimiliki nilai variable serta mengubah int menjadi string, %04s menandakan lebar minimum dari tiket yaitu 4 dengan padding berupa angka 0)
        $ticket = "T" . $tahun . $bulan . $tanggal . sprintf("%04s", $max_ticket);
        return $ticket;
    }

    //Method untuk mengambil semua ticket yang dimiliki user dengan parameter id_user
    public function myticket($id)
    {
        //Query untuk mendapatkan semua ticket yang dimiliki user dengan diurutkan berdasarkan tanggal
        $query = $this->db->query("SELECT A.progress, A.status, A.id_ticket, A.reported, A.tanggal, A.id_kondisi, A.deadline, A.last_update,
                                   A.problem_detail, A.problem_summary, A.filefoto, B.nama_sub_kategori, A.teknisi,
                                   C.nama_kategori, D.nama, G.nama_kondisi, G.warna, H.lokasi, J.nama_dept, K.nama AS nama_teknisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN user E ON E.id_user = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username
                                   LEFT JOIN kondisi G ON G.id_kondisi = A.id_kondisi
                                   LEFT JOIN lokasi H ON H.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen I ON I.id_bagian_dept = D.id_bagian_dept
                                   LEFT JOIN departemen J ON J.id_dept = I.id_dept
                                   LEFT JOIN karyawan K ON K.nik = A.teknisi
                                   WHERE A.reported = '$id' ORDER BY A.tanggal DESC");
        return $query;
    }

    //Method untuk mengambil data detail dari setiap ticket dengan parameter id_ticket
    public function detail_ticket($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, A.id_kondisi, A.deadline,
                                   A.problem_summary, A.problem_detail, A.filefoto, A.id_kondisi, A.id_sub_kategori, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, 
                                   H.nama_bagian_dept, I.nama_dept, J.nama_kondisi, J.warna, J.waktu_respon, K.nama_jabatan FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = A.teknisi
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   LEFT JOIN jabatan K ON K.id_jabatan = D.id_jabatan
                                   WHERE A.id_ticket = '$id'");
        return $query;
    }

    //Method untuk mengambil data tracking dari setiap ticket dengan parameter id_ticket
    public function tracking_ticket($id)
    {
        //Query untuk mendapatkan data tracking dari setiap ticket
        $query = $this->db->query("SELECT A.tanggal, A.status, A.deskripsi, A.filefoto, B.nama FROM tracking A 
                                   LEFT JOIN karyawan B ON B.nik = A.id_user
                                   WHERE A.id_ticket ='$id'");
        return $query;
    }

    //Method untuk mengambil profile dari setiap user
    public function profile($id)
    {
        //Query untuk mengambil data profile dari setiap user
        $query = $this->db->query("SELECT A.nik, A.nama, A.email, A.id_jabatan, A.id_bagian_dept, B.level, 
                                   C.nama_jabatan, D.id_dept, D.nama_bagian_dept, E.nama_dept FROM karyawan A 
                                   LEFT JOIN user B ON B.username = A.nik 
                                   LEFT JOIN jabatan C ON C.id_jabatan = A.id_jabatan 
                                   LEFT JOIN bagian_departemen D ON D.id_bagian_dept = A.id_bagian_dept 
                                   LEFT JOIN departemen E ON E.id_dept = D.id_dept WHERE A.nik ='$id'"); 
        return $query;
    }

    //Method untuk menaruh data kategori pada dropdown
    public function dropdown_kategori()
    {
        //Query untuk mengambil data kategori dan diurutkan berdasarkan nama kategori
        $sql = "SELECT * FROM kategori ORDER BY nama_kategori";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data kategori ke dalam dropdown, value yang akan diambil adalah value id_kategori
        foreach ($query->result() as $row) {
            $value[$row->id_kategori] = $row->nama_kategori;
        }
        return $value;
    }

    //Method untuk menaruh data lokasi pada dropdown
    public function dropdown_lokasi()
    {
        //Query untuk mengambil data lokasi dan diurutkan berdasarkan nama lokasi
        $sql = "SELECT * FROM lokasi ORDER BY lokasi";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data lokasi ke dalam dropdown, value yang akan diambil adalah value id_lokasi
        foreach ($query->result() as $row) {
            $value[$row->id_lokasi] = $row->lokasi;
        }
        return $value;
    }

    //Method untuk menaruh data sub ketegori sesuai dengan kategori yang dipilih pada dropdown
    public function dropdown_sub_kategori($id_kategori)
    {
        //Query untuk mengambil data sub kategori dan diurutkan berdasarkan nama sub kategori
        $sql = "SELECT * FROM sub_kategori where id_kategori ='$id_kategori' ORDER BY nama_sub_kategori";
        $query = $this->db->query($sql);

        //Value default pada dropdown
        $value[''] = '-- CHOOSE --';
        //Menaruh data sub kategori ke dalam dropdown, value yang akan diambil adalah value id_sub_kategori
        foreach ($query->result() as $row) {
            $value[$row->id_sub_kategori] = $row->nama_sub_kategori;
        }
        return $value;
    }
//////////////////////////////////////////////////////////////////////////////Selesai Bagian User//////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////Bagian Dashboard////////////////////////////////////////////////////////////////////////////////

    //Method untuk mengambil data semua ticket
    public function getTicket()
    {
        //Query untuk mengambil data semua ticket
        return $this->db->get('ticket');
    }

    //Method untuk mengambil data teknisi dan jumlah tugasnya
    public function getTek()
    {
        $query = $this->db->query("SELECT A.id_user, B.nama, SUM(C.status NOT IN (1,2,6,7)) as total FROM user A 
                                    LEFT JOIN karyawan B ON B.nik = A.username 
                                    LEFT JOIN ticket C ON C.teknisi = B.nik 
                                    WHERE A.level = 'technician' GROUP BY B.nama ");
        return $query;
    }

    public function allassignment($id)
    {
        $query = $this->db->query("SELECT A.progress, A.status, A.id_ticket, A.reported, A.tanggal, A.id_kondisi, A.deadline,
                                   A.problem_detail, A.problem_summary, A.filefoto, B.nama_sub_kategori, 
                                   C.nama_kategori, D.nama, G.nama_kondisi, G.warna, H.lokasi, J.nama_dept FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username
                                   LEFT JOIN kondisi G ON G.id_kondisi = A.id_kondisi
                                   LEFT JOIN lokasi H ON H.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen I ON I.id_bagian_dept = D.id_bagian_dept
                                   LEFT JOIN departemen J ON J.id_dept = I.id_dept
                                   WHERE F.nik = '$id' AND A.status IN (3,4,5) ORDER BY A.tanggal DESC");
        return $query;
    }

    //Method untuk mengambil data semua ticket berdasarkan statusnya
    public function getStatusTicket($status){
        //Query untuk mengambil data semua ticket berdasarkan status
        $this->db->where('status', $status);
        return $this->db->get('ticket');
    }

    //Method untuk mengambil data semua user dengan level 'Technician'
    public function getTeknisi(){
        //Query untuk mengambil data semua user dengan level 'Technician'
        $this->db->where('level', 'Technician');
        return $this->db->get('user');
    }

    //Method untuk mengambil data semua user dengan level 'User'
    public function getUser(){
        //Query untuk mengambil data semua user dengan level 'User'
        $this->db->where('level', 'User');
        return $this->db->get('user');
    }

    public function Bar_Ticket()
    {
        $query = $this->db->query("SELECT B.nama_sub_kategori, COUNT(*) AS total FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   WHERE YEAR(A.tanggal)=YEAR(NOW()) AND A.status NOT IN (0)
                                   GROUP BY A.id_sub_kategori");
        return $query;
    }

    public function pie_kondisi()
    {
        $query = $this->db->query("SELECT B.nama_kondisi, B.warna, A.id_kondisi, COUNT(*) AS jumkondisi FROM ticket A 
                                   LEFT JOIN kondisi B ON B.id_kondisi = A.id_kondisi 
                                   WHERE YEAR(A.tanggal)=YEAR(NOW()) AND A.status NOT IN (0)
                                   GROUP BY A.id_kondisi ORDER BY A.id_kondisi ASC");

        return $query;
    }

    public function line_bulan()
    {
        $query = $this->db->query("SELECT MONTHNAME(tanggal) AS bulan, COUNT(*) AS jumbulan FROM ticket 
                                   WHERE YEAR(tanggal)=YEAR(NOW())
                                   GROUP BY MONTHNAME(tanggal) 
                                   ORDER BY MONTH(tanggal) ASC");

        return $query;
    }

    public function pie_status()
    {
      $query = $this->db->query("SELECT status, COUNT(*) AS jumstat FROM ticket 
                                  WHERE YEAR(tanggal)=YEAR(NOW()) 
                                  GROUP BY status ORDER BY status ASC");
      return $query;
    }
////////////////////////////////////////////////////////////////////////////Selesai Bagian Dashboard////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////Bagian Statistik////////////////////////////////////////////////////////////////////////////////
    public function Stat_Tahun()
    {
        $query = $this->db->query("SELECT YEAR(tanggal) AS tahun, COUNT(*) AS jumtahun FROM ticket
                                   GROUP BY YEAR(tanggal)");
        return $query;
    }

    public function pilih_tahun()
    {
        $query = $this->db->query("SELECT YEAR(tanggal) AS tahun FROM ticket ORDER BY YEAR(tanggal) ASC ");

        $value[''] = '--Select Period--';
        foreach ($query->result() as $row) {
            $value[$row->tahun] = $row->tahun;
        }
        return $value;
    }

    public function pilih_bulan($id_tahun)
    {
        $query = $this->db->query("SELECT DATE_FORMAT(tanggal, '%Y/ %M') AS bulan FROM ticket WHERE DATE_FORMAT(tanggal, '%Y') = '$id_tahun'  ORDER BY MONTH(tanggal) ASC ");

        $value[''] = '--Select Month--';
        foreach ($query->result() as $row) {
            $value[$row->bulan] = $row->bulan;
        }
        return $value;
    }

    public function report($tgl1, $tgl2)
    {
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.last_update, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, A.id_kondisi, A.deadline,
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, 
                                   I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE DATE(A.tanggal) BETWEEN '$tgl1' AND '$tgl2'");
        return $query;
    }

/////////////////////////////////////////////////////////////////////////////Bagian Notifikasi Email/////////////////////////////////////////////////////////////////////////////
    public function emailbuatticket($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, A.id_kondisi, A.deadline,
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();

        $isiEmail  = "<h1>New Ticket (".$query->id_ticket.") Has Been Submited</h1>";
        $isiEmail .= "<div>Ticket with ID Number ".$query->id_ticket." has been submited by ".$query->nama."</div>";
        $isiEmail .= "<div>Please respone and set the priority of the ticket in <b>GENESYS Web Aplication</b></div>";
        $isiEmail .= '<div>
                        <table>
                          <tbody>
                            <tr>
                              <td>ID Ticket</td>
                              <td>:</td>
                              <td>'.$query->id_ticket.'</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>'.$query->nama.'</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td>'.$query->email.'</td>
                            </tr>
                            <tr>
                              <td>Category</td>
                              <td>:</td>
                              <td>'.$query->nama_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Sub Category</td>
                              <td>:</td>
                              <td>'.$query->nama_sub_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Location</td>
                              <td>:</td>
                              <td>'.$query->lokasi.'</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td>Submited</td>
                            </tr>
                            <tr>
                              <td>Problem</td>
                              <td>:</td>
                              <td>'.$query->problem_summary.'</td>
                            </tr>
                            <tr>
                              <td>Detail</td>
                              <td>:</td>
                              <td>'.nl2br($query->problem_detail).'</td>
                            </tr>
                         </tbody>
                        </table>
                      </div>';

        $this->email->from('ithelpdesk.lbe@gmail.com', $query->nama); 
        $this->email->to('ithelpdesk.lbe@gmail.com');   
        $this->email->subject('New Ticket ('.$query->id_ticket.') Has Been Submited');   
        $this->email->attach('uploads/'.$query->filefoto);
        $this->email->message($isiEmail);  
        if (!$this->email->send()) {  
          //Set pemberitahuan bahwa data tiket berhasil dibuat
          $this->session->set_flashdata('status', 'Submited');
          //Dialihkan ke halaman my ticket
          redirect('List_ticket_user/index');  
        }else{  
          echo 'Success to send email';   
        }
    }

    public function emailapprove($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();

        $isiEmail  = "<h1>Your Ticket (".$query->id_ticket.") Has Been Received</h1>";
        $isiEmail .= "<div>Dear ".$query->nama."</div>";
        $isiEmail .= "<div>Your ticket will be processed according to predetermined priorities. You can track your ticket in <b>GENESYS Web Aplication</b></div>";
        $isiEmail .= '<div>
                        <table>
                          <tbody>
                            <tr>
                              <td>ID Ticket</td>
                              <td>:</td>
                              <td>'.$query->id_ticket.'</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>'.$query->nama.'</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td>'.$query->email.'</td>
                            </tr>
                            <tr>
                              <td>Category</td>
                              <td>:</td>
                              <td>'.$query->nama_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Sub Category</td>
                              <td>:</td>
                              <td>'.$query->nama_sub_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Priority</td>
                              <td>:</td>
                              <td>'.$query->nama_kondisi.'</td>
                            </tr>
                            <tr>
                              <td>Location</td>
                              <td>:</td>
                              <td>'.$query->lokasi.'</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td>Received</td>
                            </tr>
                            <tr>
                              <td>Problem</td>
                              <td>:</td>
                              <td>'.$query->problem_summary.'</td>
                            </tr>
                            <tr>
                              <td>Detail</td>
                              <td>:</td>
                              <td>'.nl2br($query->problem_detail).'</td>
                            </tr>
                         </tbody>
                        </table>
                      </div>';

        $this->email->set_newline("\r\n");  
        $this->email->from('ithelpdesk.lbe@gmail.com', 'Genesys');   
        $this->email->to($query->email);   
        $this->email->subject('Your Ticket ('.$query->id_ticket.') Has Been Received');   
        $this->email->attach('uploads/'.$query->filefoto);
        $cid = $this->email->attachment_cid('uploads/'.$query->filefoto);
        $this->email->message($isiEmail);  
        if (!$this->email->send()) {  
            //Set pemberitahuan bahwa tiket berhasil ditugaskan ke teknisi
            $this->session->set_flashdata('status', 'Assigned');
            //Kembali ke halaman List approvel ticket (list_approve)
            redirect('List_ticket/list_approve');   
        }else{  
          echo 'Success to send email';   
        }
    }

    public function emailreject($id)
    {
        $message  = $this->input->post('message');
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();

        $isiEmail  = "<h1>Sorry, Your Ticket (".$query->id_ticket.") Has Been Rejected</h1>";
        $isiEmail .= "<div>Dear ".$query->nama."</div>";
        $isiEmail .= "<div>".$message."</div>";
        $isiEmail .= '<div>
                        <table>
                          <tbody>
                            <tr>
                              <td>ID Ticket</td>
                              <td>:</td>
                              <td>'.$query->id_ticket.'</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>'.$query->nama.'</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td>'.$query->email.'</td>
                            </tr>
                            <tr>
                              <td>Category</td>
                              <td>:</td>
                              <td>'.$query->nama_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Sub Category</td>
                              <td>:</td>
                              <td>'.$query->nama_sub_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Priority</td>
                              <td>:</td>
                              <td>'.$query->nama_kondisi.'</td>
                            </tr>
                            <tr>
                              <td>Location</td>
                              <td>:</td>
                              <td>'.$query->lokasi.'</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td>Rejected</td>
                            </tr>
                            <tr>
                              <td>Problem</td>
                              <td>:</td>
                              <td>'.$query->problem_summary.'</td>
                            </tr>
                            <tr>
                              <td>Detail</td>
                              <td>:</td>
                              <td>'.nl2br($query->problem_detail).'</td>
                            </tr>
                         </tbody>
                        </table>
                      </div>';

        $this->email->set_newline("\r\n");  
        $this->email->from('ithelpdesk.lbe@gmail.com', 'Genesys');   
        $this->email->to($query->email);   
        $this->email->subject('Your Ticket ('.$query->id_ticket.') Has Been Rejected');   
        $this->email->attach('uploads/'.$query->filefoto);
        $cid = $this->email->attachment_cid('uploads/'.$query->filefoto);
        $this->email->message($isiEmail);  
        if (!$this->email->send()) {  
          //Set pemberitahuan bahwa ticket berhasil di-reject
          $this->session->set_flashdata('status', 'Rejected');
          //Kembali ke halaman List approvel ticket (list_approve)
          redirect('List_ticket/list_approve');   
        }else{  
          echo 'Success to send email';   
        }
    }

    public function emailtugas($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();

        $isiEmail  = "<h1>You Are Assigned To The Ticket (".$query->id_ticket.")</h1>";
        $isiEmail .= "<div>Dear ".$query->nama_teknisi."</div>";
        $isiEmail .= "<div>Pelase check your <code>Ticket Assigned</code> menu on <b>GENESYS Web Application</b></div>";
        $isiEmail .= '<div>
                        <table>
                          <tbody>
                            <tr>
                              <td>ID Ticket</td>
                              <td>:</td>
                              <td>'.$query->id_ticket.'</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>'.$query->nama.'</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td>'.$query->email.'</td>
                            </tr>
                            <tr>
                              <td>Category</td>
                              <td>:</td>
                              <td>'.$query->nama_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Sub Category</td>
                              <td>:</td>
                              <td>'.$query->nama_sub_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Priority</td>
                              <td>:</td>
                              <td>'.$query->nama_kondisi.'</td>
                            </tr>
                            <tr>
                              <td>Location</td>
                              <td>:</td>
                              <td>'.$query->lokasi.'</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td>Assigned to you</td>
                            </tr>
                            <tr>
                              <td>Problem</td>
                              <td>:</td>
                              <td>'.$query->problem_summary.'</td>
                            </tr>
                            <tr>
                              <td>Detail</td>
                              <td>:</td>
                              <td>'.nl2br($query->problem_detail).'</td>
                            </tr>
                         </tbody>
                        </table>
                      </div>';
  
        $this->email->set_newline("\r\n");  
        $this->email->from('ithelpdesk.lbe@gmail.com', 'Genesys');   
        $this->email->to($this->input->post('email'));   
        $this->email->subject('Your Are Assigned To The Ticket ('.$query->id_ticket.')');   
        $this->email->attach('uploads/'.$query->filefoto);
        $cid = $this->email->attachment_cid('uploads/'.$query->filefoto);
        $this->email->message($isiEmail);  
        if (!$this->email->send()) {  
          //Set pemberitahuan bahwa tiket berhasil ditugaskan ke teknisi
            $this->session->set_flashdata('status', 'Assigned');
            //Kembali ke halaman List approvel ticket (list_approve)
            redirect('List_ticket/list_approve');  
        }else{  
          echo 'Success to send email';   
        }
    }

    public function emaildiproses($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();

        $isiEmail  = "<h1>Your Ticket (".$query->id_ticket.") being processed</h1>";
        $isiEmail .= "<div>Dear ".$query->nama."</div>";
        $isiEmail .= "<div>You can track your ticket in <b>GENESYS Web Aplication</b></div>";
        $isiEmail .= '<div>
                        <table>
                          <tbody>
                            <tr>
                              <td>ID Ticket</td>
                              <td>:</td>
                              <td>'.$query->id_ticket.'</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>'.$query->nama.'</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td>'.$query->email.'</td>
                            </tr>
                            <tr>
                              <td>Category</td>
                              <td>:</td>
                              <td>'.$query->nama_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Sub Category</td>
                              <td>:</td>
                              <td>'.$query->nama_sub_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Priority</td>
                              <td>:</td>
                              <td>'.$query->nama_kondisi.'</td>
                            </tr>
                            <tr>
                              <td>Location</td>
                              <td>:</td>
                              <td>'.$query->lokasi.'</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td>processed by '.$query->nama_teknisi.'</td>
                            </tr>
                            <tr>
                              <td>Problem</td>
                              <td>:</td>
                              <td>'.$query->problem_summary.'</td>
                            </tr>
                            <tr>
                              <td>Detail</td>
                              <td>:</td>
                              <td>'.nl2br($query->problem_detail).'</td>
                            </tr>
                         </tbody>
                        </table>
                      </div>';
 
        $this->email->set_newline("\r\n");  
        $this->email->from('ithelpdesk.lbe@gmail.com', 'Genesys');   
        $this->email->to($query->email);   
        $this->email->subject('Your Ticket ('.$query->id_ticket.') being processed by technician');   
        $this->email->attach('uploads/'.$query->filefoto);
        $this->email->message($isiEmail);  
        if (!$this->email->send()) {  
          //Set pemberitahuan bahwa ticket berhasil di-approve
          $this->session->set_flashdata('status', 'Process');
          //Kembali ke halaman List approval ticket (Ticket Assigned)
          redirect('List_ticket_tek/index_approve');   
        }else{  
          echo 'Success to send email';   
        }
    }

    public function emaildipending($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();

        $isiEmail  = "<h1>Your Ticket (".$query->id_ticket.") is pending</h1>";
        $isiEmail .= "<div>Dear ".$query->nama."</div>";
        $isiEmail .= "<div>Your ticket will be handled soon</div>";
        $isiEmail .= '<div>
                        <table>
                          <tbody>
                            <tr>
                              <td>ID Ticket</td>
                              <td>:</td>
                              <td>'.$query->id_ticket.'</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>'.$query->nama.'</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td>'.$query->email.'</td>
                            </tr>
                            <tr>
                              <td>Category</td>
                              <td>:</td>
                              <td>'.$query->nama_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Sub Category</td>
                              <td>:</td>
                              <td>'.$query->nama_sub_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Priority</td>
                              <td>:</td>
                              <td>'.$query->nama_kondisi.'</td>
                            </tr>
                            <tr>
                              <td>Location</td>
                              <td>:</td>
                              <td>'.$query->lokasi.'</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td>Pending by '.$query->nama_teknisi.'</td>
                            </tr>
                            <tr>
                              <td>Problem</td>
                              <td>:</td>
                              <td>'.$query->problem_summary.'</td>
                            </tr>
                            <tr>
                              <td>Detail</td>
                              <td>:</td>
                              <td>'.nl2br($query->problem_detail).'</td>
                            </tr>
                         </tbody>
                        </table>
                      </div>';
  
        $this->email->set_newline("\r\n");  
        $this->email->from('ithelpdesk.lbe@gmail.com', 'Genesys');   
        $this->email->to($query->email);   
        $this->email->subject('Your Ticket ('.$query->id_ticket.') is pending by technician');   
        $this->email->attach('uploads/'.$query->filefoto);
        $cid = $this->email->attachment_cid('uploads/'.$query->filefoto);
        $this->email->message($isiEmail);  
        if (!$this->email->send()) {  
          //Set pemberitahuan bahwa ticket berhasil di-pending
          $this->session->set_flashdata('status', 'Hold');
          //Kembali ke halaman List approval ticket (Ticket Assigned)
          redirect('List_ticket_tek/index_approve');   
        }else{  
          echo 'Success to send email';   
        }
    }

    public function emailselesai($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();
        if($query->progress==100){
            $isiEmail  = "<h1>Your Ticket (".$query->id_ticket.") is Done</h1>";
            $isiEmail  .= "<div>Dear ".$query->nama."</div>";
            $isiEmail .= "<div>Your ticket is done of progress. The ticket will be closed.</div>";
            $isiEmail .= "<div>Thank You.</div>";
            $isiEmail .= '<div>
                            <table>
                              <tbody>
                                <tr>
                                  <td>ID Ticket</td>
                                  <td>:</td>
                                  <td>'.$query->id_ticket.'</td>
                                </tr>
                                <tr>
                                  <td>Nama</td>
                                  <td>:</td>
                                  <td>'.$query->nama.'</td>
                                </tr>
                                <tr>
                                  <td>Email</td>
                                  <td>:</td>
                                  <td>'.$query->email.'</td>
                                </tr>
                                <tr>
                                  <td>Category</td>
                                  <td>:</td>
                                  <td>'.$query->nama_kategori.'</td>
                                </tr>
                                <tr>
                                  <td>Sub Category</td>
                                  <td>:</td>
                                  <td>'.$query->nama_sub_kategori.'</td>
                                </tr>
                                <tr>
                                  <td>Priority</td>
                                  <td>:</td>
                                  <td>'.$query->nama_kondisi.'</td>
                                </tr>
                                <tr>
                                  <td>Location</td>
                                  <td>:</td>
                                  <td>'.$query->lokasi.'</td>
                                </tr>
                                <tr>
                                  <td>Status</td>
                                  <td>:</td>
                                  <td>Ticket Closed</td>
                                </tr>
                                <tr>
                                  <td>Problem</td>
                                  <td>:</td>
                                  <td>'.$query->problem_summary.'</td>
                                </tr>
                                <tr>
                                  <td>Detail</td>
                                  <td>:</td>
                                  <td>'.nl2br($query->problem_detail).'</td>
                                </tr>
                             </tbody>
                            </table>
                          </div>';
 
            $this->email->set_newline("\r\n");  
            $this->email->from('ithelpdesk.lbe@gmail.com', 'Genesys');   
            $this->email->to($query->email);   
            $this->email->subject('Your Ticket ('.$query->id_ticket.') is Done');   
            $this->email->attach('uploads/'.$query->filefoto);
            $cid = $this->email->attachment_cid('uploads/'.$query->filefoto);
            $this->email->message($isiEmail);  
            if (!$this->email->send()) {  
              //Set pemberitahuan bahwa ticket berhasil di-update
              $this->session->set_flashdata('status', 'Updated');
              //Kembali ke halaman List ticket (Assignment Ticket)
              redirect('List_ticket_tek/index_tugas');   
            }else{  
              echo 'Success to send email';   
            }
        }
    }

    public function emailubah($id)
    {
        //Query untuk mendapatkan data detail dari setiap ticket
        $query = $this->db->query("SELECT A.id_ticket, A.status, A.progress, A.tanggal, A.tanggal_proses, A.tanggal_solved, 
                                   A.problem_summary, A.problem_detail, A.filefoto, B.nama_sub_kategori, C.id_kategori, 
                                   C.nama_kategori, D.nama, D.email, F.nama AS nama_teknisi, G.lokasi, H.nama_bagian_dept, I.nama_dept, J.nama_kondisi FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori 
                                   LEFT JOIN karyawan D ON D.nik = A.reported 
                                   LEFT JOIN user E ON E.username = A.teknisi
                                   LEFT JOIN karyawan F ON F.nik = E.username 
                                   LEFT JOIN lokasi G ON G.id_lokasi = A.id_lokasi
                                   LEFT JOIN bagian_departemen H ON H.id_bagian_dept = D.id_bagian_dept 
                                   LEFT JOIN departemen I ON I.id_dept = H.id_dept
                                   LEFT JOIN kondisi J ON J.id_kondisi = A.id_kondisi
                                   WHERE A.id_ticket = '$id'")->row();

        $isiEmail  = "<h1>Category of Ticket (".$query->id_ticket.") Has Been Changed</h1>";
        $isiEmail .= "<div>Please assign the technician after you check the detail to resolve the ticket</div>";
        $isiEmail .= '<div>
                        <table>
                          <tbody>
                            <tr>
                              <td>ID Ticket</td>
                              <td>:</td>
                              <td>'.$query->id_ticket.'</td>
                            </tr>
                            <tr>
                              <td>Nama</td>
                              <td>:</td>
                              <td>'.$query->nama.'</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>:</td>
                              <td>'.$query->email.'</td>
                            </tr>
                            <tr>
                              <td>Category</td>
                              <td>:</td>
                              <td>'.$query->nama_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Sub Category</td>
                              <td>:</td>
                              <td>'.$query->nama_sub_kategori.'</td>
                            </tr>
                            <tr>
                              <td>Priority</td>
                              <td>:</td>
                              <td>'.$query->nama_kondisi.'</td>
                            </tr>
                            <tr>
                              <td>Location</td>
                              <td>:</td>
                              <td>'.$query->lokasi.'</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>:</td>
                              <td>Category Changed</td>
                            </tr>
                            <tr>
                              <td>Problem</td>
                              <td>:</td>
                              <td>'.$query->problem_summary.'</td>
                            </tr>
                            <tr>
                              <td>Detail</td>
                              <td>:</td>
                              <td>'.nl2br($query->problem_detail).'</td>
                            </tr>
                         </tbody>
                        </table>
                      </div>';
  
        $this->email->set_newline("\r\n");  
        $this->email->from('ithelpdesk.lbe@gmail.com', $query->nama_teknisi);   
        $this->email->to('ithelpdesk.lbe@gmail.com');   
        $this->email->subject('Category of Ticket ('.$query->id_ticket.') Has Been Changed');   
        $this->email->attach('uploads/'.$query->filefoto);
        $cid = $this->email->attachment_cid('uploads/'.$query->filefoto);
        $this->email->message($isiEmail);  
        if (!$this->email->send()) {  
          $this->session->set_flashdata('status', 'Returned');
          //Kembali ke halaman List ticket (Assignment Ticket)
          redirect('List_ticket_tek/index_tugas');   
        }else{  
          echo 'Success to send email';   
        }
    }    
/////////////////////////////////////////////////////////////////////////Selesai Bagian Notifikasi Email/////////////////////////////////////////////////////////////////////////


    /**
    //Method yang digunakan untuk proses reopen ticket dengan parameter (id_ticket)
    public function reopen($id)
    {
        //Mengambil session admin
        $id_user    = $this->session->userdata('id_user');

        //Melakukan update data ticket dengan mengubah status ticket menjadi 1, data ditampung ke dalam array '$data' yang nanti akan diupdate dengan query
        $data = array(
          'status'     => 1
        );

        //Melakukan insert data tracking ticket bahwa ticket di-reopen oleh admin, data tracking ke dalam array '$datatracking' yang nanti akan di-insert dengan query
        $datatracking = array(
          'id_ticket'  => $id,
          'tanggal'    => date("Y-m-d  H:i:s"),
          'status'     => "Ticket Reopened",
          'deskripsi'  => "",
          'id_user'    => $id_user
        );

        //Query untuk melakukan update data ticket sesuai dengan array '$data' ke tabel ticket
        $this->db->where('id_ticket', $id);
        $this->db->update('ticket', $data);

        //Query untuk melakukan insert data tracking ticket sesuai dengan array '$datatracking' ke tabel tracking
        $this->db->insert('tracking', $datatracking);
    }
    **/

    //Method untuk mendapatkan semua ticket yang akan ditugaskan ke teknisi
    /**
    public function pilih_teknisi()
    {
        //Query untuk mendapatkan semua ticket dengan status 2 (Ticket Received) dengan diurutkan berdasarkan tanggal ticket dibuat
        $query = $this->db->query("SELECT D.nama, F.nama_dept, A.status, A.id_ticket, A.tanggal, A.id_kondisi, A.deadline,
                                   A.problem_detail, A.problem_summary, A.filefoto, B.nama_sub_kategori, C.nama_kategori, 
                                   G.nama_kondisi, G.warna, H.lokasi, I.nama_jabatan FROM ticket A 
                                   LEFT JOIN sub_kategori B ON B.id_sub_kategori = A.id_sub_kategori
                                   LEFT JOIN kategori C ON C.id_kategori = B.id_kategori
                                   LEFT JOIN karyawan D ON D.nik = A.reported
                                   LEFT JOIN bagian_departemen E ON E.id_bagian_dept = D.id_bagian_dept
                                   LEFT JOIN departemen F ON F.id_dept = E.id_dept
                                   LEFT JOIN kondisi G ON G.id_kondisi = A.id_kondisi
                                   LEFT JOIN lokasi H ON H.id_lokasi = A.id_lokasi
                                   LEFT JOIN jabatan I ON I.id_jabatan = D.id_jabatan
                                   WHERE A.status IN (2,7) ORDER BY A.tanggal DESC");
        return $query;
    }
    **/

}
