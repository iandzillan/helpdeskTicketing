-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2020 at 06:50 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.1.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `bagian_departemen`
--

CREATE TABLE `bagian_departemen` (
  `id_bagian_dept` int(11) NOT NULL,
  `nama_bagian_dept` varchar(100) NOT NULL,
  `id_dept` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bagian_departemen`
--

INSERT INTO `bagian_departemen` (`id_bagian_dept`, `nama_bagian_dept`, `id_dept`) VALUES
(20, 'Sub Departemen 01', 11),
(21, 'Sub Departemen 02', 11),
(22, 'Sub Departemen 03', 11),
(23, 'Sub Departemen 04', 12),
(24, 'Sub Departemen 05', 12),
(25, 'Sub Departemen 06', 12),
(26, 'Sub Departemen 07', 13),
(27, 'Sub Departemen 08', 13),
(28, 'Sub Departemen 09', 13),
(30, 'Sub Departemen 11', 11);

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `id_dept` int(11) NOT NULL,
  `nama_dept` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`id_dept`, `nama_dept`) VALUES
(11, 'Departemen 01'),
(12, 'Departemen 02'),
(13, 'Departemen 03');

-- --------------------------------------------------------

--
-- Table structure for table `informasi`
--

CREATE TABLE `informasi` (
  `id_informasi` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `subject` varchar(35) NOT NULL,
  `pesan` varchar(250) NOT NULL,
  `id_user` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `informasi`
--

INSERT INTO `informasi` (`id_informasi`, `tanggal`, `subject`, `pesan`, `id_user`) VALUES
(3, '2020-04-03 21:57:03', 'Change the password', 'Please change your password after the first login to secure your account', 'A000000');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(124, 'Posisi 01'),
(125, 'Posisi 02'),
(126, 'Posisi 03'),
(127, 'Posisi 04'),
(128, 'Posisi 05'),
(129, 'Posisi 06'),
(130, 'Posisi 07'),
(131, 'Posisi 08'),
(132, 'Posisi 09');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `nik` varchar(7) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_bagian_dept` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama`, `email`, `id_bagian_dept`, `id_jabatan`) VALUES
('A000000', 'Admin 01', 'iandzillanm@gmail.com', 20, 124),
('A100000', 'Teknisi 01', 'Teknisi01@gmail.com', 23, 125),
('A200000', 'Muhammad Rafidan ', 'Mrafidan@gmail.com', 21, 127),
('A300000', 'Muhammad Farhan Zuhdy', 'farhanzuhdy@gmail.com', 24, 128),
('A400000', 'Arrasyid Kamil', '-', 27, 129),
('A500000', 'User 01', 'User01@gmail.com', 26, 126);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Hardware'),
(2, 'Software'),
(3, 'Request'),
(4, 'Service');

-- --------------------------------------------------------

--
-- Table structure for table `kondisi`
--

CREATE TABLE `kondisi` (
  `id_kondisi` int(11) NOT NULL,
  `nama_kondisi` varchar(30) NOT NULL,
  `waktu_respon` int(11) NOT NULL,
  `warna` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kondisi`
--

INSERT INTO `kondisi` (`id_kondisi`, `nama_kondisi`, `waktu_respon`, `warna`) VALUES
(1, 'High', 1, '#B14145'),
(2, 'Medium', 3, '#FC8500'),
(3, 'Low', 5, '#FFB701');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id_lokasi` int(11) NOT NULL,
  `lokasi` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id_lokasi`, `lokasi`) VALUES
(1, 'Lokasi 01'),
(2, 'Lokasi 06'),
(5, 'Lokasi 07'),
(6, 'Lokasi 02'),
(7, 'Lokasi 03'),
(8, 'Lokasi 08'),
(9, 'Lokasi 04'),
(10, 'Lokasi 05');

-- --------------------------------------------------------

--
-- Table structure for table `sub_kategori`
--

CREATE TABLE `sub_kategori` (
  `id_sub_kategori` int(11) NOT NULL,
  `nama_sub_kategori` varchar(35) NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_kategori`
--

INSERT INTO `sub_kategori` (`id_sub_kategori`, `nama_sub_kategori`, `id_kategori`) VALUES
(1, 'Server', 1),
(2, 'Desktop', 1),
(3, 'Laptop', 1),
(4, 'Printer', 1),
(5, 'SAP', 2),
(6, 'Office \\ Productivity', 2),
(7, 'Nisoft', 2),
(8, 'PI', 2),
(9, 'GDMS', 2),
(10, 'Email Outlook', 2),
(11, 'Others', 2),
(12, 'Data Restore', 3),
(13, 'Password Reset', 3),
(14, 'New User / User Leaving', 3),
(15, 'User / equipment Move or Change', 3),
(16, 'New Software Request', 3),
(17, 'Email', 4),
(18, 'File Storage', 4),
(19, 'Printing', 4),
(20, 'Internet', 4),
(21, 'Intranet', 4),
(22, 'Document Management', 4),
(23, 'Telecommunications', 4),
(24, 'Networking', 4);

-- --------------------------------------------------------

--
-- Table structure for table `teknisi`
--

CREATE TABLE `teknisi` (
  `id_teknisi` varchar(5) NOT NULL,
  `nik` varchar(7) NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id_ticket` varchar(13) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  `tanggal_proses` datetime NOT NULL,
  `tanggal_solved` datetime NOT NULL,
  `reported` varchar(7) NOT NULL,
  `id_sub_kategori` int(11) NOT NULL,
  `problem_summary` varchar(50) NOT NULL,
  `problem_detail` text NOT NULL,
  `teknisi` varchar(7) NOT NULL,
  `status` int(11) NOT NULL,
  `progress` decimal(10,0) NOT NULL,
  `filefoto` text NOT NULL,
  `id_lokasi` int(11) NOT NULL,
  `id_kondisi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id_ticket`, `tanggal`, `deadline`, `last_update`, `tanggal_proses`, `tanggal_solved`, `reported`, `id_sub_kategori`, `problem_summary`, `problem_detail`, `teknisi`, `status`, `progress`, `filefoto`, `id_lokasi`, `id_kondisi`) VALUES
('T202005150002', '2020-05-15 11:31:33', '2020-05-16 18:31:33', '2020-05-16 02:42:44', '2020-05-16 02:42:44', '0000-00-00 00:00:00', 'A500000', 2, 'Tess', 'Tess1', 'A100000', 4, '0', 'Annotation_2020-05-06_1642592.png', 1, 1),
('T202005150003', '2020-05-15 13:32:02', '2020-05-18 20:32:02', '2020-05-16 02:02:04', '2020-05-16 02:01:24', '2020-05-16 02:02:04', 'A500000', 18, 'Test 2', 'Test', 'A100000', 6, '100', 'Screenshot_(758).png', 1, 2),
('T202005150004', '2020-05-15 13:43:05', '0000-00-00 00:00:00', '2020-05-15 21:18:57', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'A500000', 18, 'Tes 3', 'Test 3', '', 0, '0', 'Screenshot_(694).png', 6, 0),
('T202005150005', '2020-05-15 14:47:26', '2020-05-15 21:47:26', '2020-05-16 02:00:11', '2020-05-16 01:50:17', '2020-05-16 02:00:11', 'A500000', 16, 'Test4', 'Test4', 'A100000', 7, '100', 'Screenshot_(759).png', 1, 1),
('T202005160006', '2020-05-15 19:29:40', '2020-05-17 02:29:40', '2020-05-16 02:42:52', '2020-05-16 02:42:52', '0000-00-00 00:00:00', 'A500000', 18, 'Testing 02', 'Tess', 'A100000', 4, '0', 'MIH501788.pdf', 7, 1),
('T202005160007', '2020-05-15 19:37:41', '2020-05-19 02:37:41', '2020-05-16 02:43:00', '2020-05-16 02:43:00', '0000-00-00 00:00:00', 'A500000', 18, 'Tess hapus pegawai', 'Tess', 'A100000', 4, '0', 'adoc_tips_penerapan-model-utaut2-untuk-menjelaskan-minat-dan.pdf', 7, 2),
('T202005160008', '2020-05-15 19:44:42', '2020-05-21 02:44:42', '2020-05-16 02:50:19', '2020-05-16 02:50:19', '0000-00-00 00:00:00', 'A500000', 13, 'Tess', 'Tess', 'A100000', 4, '0', 'Screenshot_(762).png', 7, 3),
('T202005160009', '2020-05-15 19:45:10', '2020-05-21 02:45:10', '2020-05-16 02:50:29', '2020-05-16 02:50:29', '0000-00-00 00:00:00', 'A500000', 3, 'Tess', 'Tess', 'A100000', 4, '0', 'Screenshot_(754).png', 7, 3),
('T202005250010', '2020-05-25 03:44:18', '2020-05-28 10:44:18', '2020-05-25 11:27:39', '2020-05-25 11:24:45', '0000-00-00 00:00:00', 'A500000', 3, 'Blue Screen', 'Laptop saya tiba-tiba blue screen saat saya pakai', 'A100000', 4, '20', 'blue-screen.png', 1, 2),
('T202005250011', '2020-05-25 03:50:29', '0000-00-00 00:00:00', '2020-05-25 10:50:29', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'A500000', 16, 'Autocad 2021', 'Permintaan untuk software Autocad terbaru\r\nTerdapat fitur yang dapat membantu pekerjaan saya', '', 1, '0', 'Annotation_2020-05-25_105014.png', 1, 0),
('T202005250012', '2020-05-25 03:55:18', '2020-05-26 10:55:18', '2020-05-25 11:16:37', '2020-05-25 11:08:22', '0000-00-00 00:00:00', 'A500000', 24, 'Tidak bisa connect ke internet', 'Ternyata trouble bukan dari device, tetapi dari kabel LAN yang terputus ', '', 2, '10', 'Annotation_2020-05-25_105504.png', 7, 1),
('T202005250013', '2020-05-25 03:57:35', '0000-00-00 00:00:00', '2020-05-25 10:57:35', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'A500000', 6, 'Lisensi Office', 'Lisensi office saya sudah habis. Saya ingin meminta lisensi office yang baru', '', 1, '0', 'Annotation_2020-05-25_105714.png', 9, 0),
('T202005310014', '2020-05-31 04:49:36', '0000-00-00 00:00:00', '2020-05-31 11:49:36', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'A500000', 4, 'Testing', 'Testing ', '', 1, '0', 'Annotation_2020-05-25_112721.png', 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE `tracking` (
  `id_tracking` bigint(11) UNSIGNED NOT NULL,
  `id_ticket` varchar(13) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` text NOT NULL,
  `deskripsi` text NOT NULL,
  `id_user` varchar(7) NOT NULL,
  `filefoto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracking`
--

INSERT INTO `tracking` (`id_tracking`, `id_ticket`, `tanggal`, `status`, `deskripsi`, `id_user`, `filefoto`) VALUES
(52, 'T202005150002', '2020-05-15 18:31:33', 'Ticket Submited', '', 'A500000', ''),
(53, 'T202005150002', '2020-05-15 20:28:31', 'Ticket Received', 'Priority is set to Low', 'A000000', ''),
(54, 'T202005150003', '2020-05-15 20:32:02', 'Ticket Submited', '', 'A500000', ''),
(55, 'T202005150003', '2020-05-15 20:33:00', 'Ticket Received', 'Priority is set to Medium', 'A000000', ''),
(56, 'T202005150004', '2020-05-15 20:43:05', 'Ticket Submited', '', 'A500000', ''),
(57, 'T202005150004', '2020-05-15 21:18:57', 'Ticket Rejected', '', 'A000000', ''),
(58, 'T202005150005', '2020-05-15 21:47:26', 'Ticket Submited', '', 'A500000', ''),
(59, 'T202005150003', '2020-05-15 23:40:34', 'Technician Selected', 'Ticket is assigned to technician', 'A000000', ''),
(72, 'T202005150005', '2020-05-16 01:49:53', 'Ticket Received', 'Priority of the ticket is set to High and assigned to technician.', 'A000000', ''),
(73, 'T202005150005', '2020-05-16 01:50:17', 'On Process', '', 'A100000', ''),
(74, 'T202005150005', '2020-05-16 01:50:40', 'Ticket Closed. Progress: 100 %', 'Tess', 'A100000', 'Screenshot_(764)4.png'),
(75, 'T202005150005', '2020-05-16 01:58:09', 'Ticket Closed. Progress: 100 %', 'Tess', 'A100000', 'Screenshot_(765)1.png'),
(76, 'T202005150005', '2020-05-16 02:00:11', 'Ticket Closed. Progress: 100 %', 'Tess', 'A100000', 'Screenshot_(764)5.png'),
(77, 'T202005150003', '2020-05-16 02:00:59', 'Ticket Received', 'Priority of the ticket is set to Medium and assigned to technician.', 'A000000', ''),
(78, 'T202005150003', '2020-05-16 02:01:24', 'On Process', '', 'A100000', ''),
(79, 'T202005150003', '2020-05-16 02:01:50', 'Progress: 10 %', 'Tes', 'A100000', 'blue-screen1.png'),
(80, 'T202005150003', '2020-05-16 02:02:04', 'Ticket Closed. Progress: 100 %', 'Tess', 'A100000', 'Screenshot_(764)6.png'),
(81, 'T202005150002', '2020-05-16 02:07:23', 'Ticket Received', 'Priority of the ticket is set to High and assigned to technician.', 'A000000', ''),
(82, 'T202005160006', '2020-05-16 02:29:40', 'Ticket Submited', '', 'A500000', ''),
(83, 'T202005160007', '2020-05-16 02:37:41', 'Ticket Submited', '', 'A500000', ''),
(84, 'T202005160006', '2020-05-16 02:38:13', 'Ticket Received', 'Priority of the ticket is set to High and assigned to technician.', 'A000000', ''),
(85, 'T202005160007', '2020-05-16 02:38:34', 'Ticket Received', 'Priority of the ticket is set to Medium and assigned to technician.', 'A000000', ''),
(86, 'T202005150002', '2020-05-16 02:42:44', 'On Process', '', 'A100000', ''),
(87, 'T202005160006', '2020-05-16 02:42:52', 'On Process', '', 'A100000', ''),
(88, 'T202005160007', '2020-05-16 02:43:00', 'On Process', '', 'A100000', ''),
(89, 'T202005160008', '2020-05-16 02:44:42', 'Ticket Submited', '', 'A500000', ''),
(90, 'T202005160009', '2020-05-16 02:45:10', 'Ticket Submited', '', 'A500000', ''),
(91, 'T202005160008', '2020-05-16 02:48:58', 'Ticket Received', 'Priority of the ticket is set to Low and assigned to technician.', 'A000000', ''),
(92, 'T202005160009', '2020-05-16 02:49:44', 'Ticket Received', 'Priority of the ticket is set to Low and assigned to technician.', 'A000000', ''),
(93, 'T202005160008', '2020-05-16 02:50:19', 'On Process', '', 'A100000', ''),
(94, 'T202005160009', '2020-05-16 02:50:29', 'On Process', '', 'A100000', ''),
(95, 'T202005250010', '2020-05-25 10:44:18', 'Ticket Submited. Category: Hardware(Laptop)', 'Laptop saya tiba-tiba blue screen saat saya pakai', 'A500000', ''),
(96, 'T202005250011', '2020-05-25 10:50:29', 'Ticket Submited. Category: Request(New Software Request)', 'Permintaan untuk software Autocad terbaru\r\nTerdapat fitur yang dapat membantu pekerjaan saya', 'A500000', ''),
(97, 'T202005250012', '2020-05-25 10:55:18', 'Ticket Submited. Category: Service(Intranet)', 'PC saya tidak dapat terkoneksi ke internet.\r\nSaya sudah mencoba connect beberapa kali tetap tidak bisa.', 'A500000', ''),
(98, 'T202005250013', '2020-05-25 10:57:35', 'Ticket Submited. Category: Software(Office \\ Productivity)', 'Lisensi office saya sudah habis. Saya ingin meminta lisensi office yang baru', 'A500000', ''),
(99, 'T202005250012', '2020-05-25 11:08:01', 'Ticket Received', 'Priority of the ticket is set to High and assigned to technician.', 'A000000', ''),
(100, 'T202005250012', '2020-05-25 11:08:22', 'On Process', '', 'A100000', ''),
(101, 'T202005250012', '2020-05-25 11:12:39', 'Progress: 10 %', 'Sedang dilakukan pengecekan terhadap device yang digunakan ', 'A100000', 'Annotation_2020-05-25_105504.png'),
(102, 'T202005250012', '2020-05-25 11:16:37', 'Category Changed to Service(Networking)', 'Ternyata trouble bukan dari device, tetapi dari kabel LAN yang terputus ', 'A100000', 'Annotation_2020-05-25_111616.png'),
(103, 'T202005250010', '2020-05-25 11:17:42', 'Ticket Received', 'Priority of the ticket is set to Medium and assigned to technician.', 'A000000', ''),
(104, 'T202005250010', '2020-05-25 11:24:45', 'On Process', '', 'A100000', ''),
(105, 'T202005250010', '2020-05-25 11:27:39', 'Progress: 20 %', 'Sedang dilakukan pengecekan. Kemungkinan penyebab: \r\n1. Ram \r\n2. Hardisk', 'A100000', 'Annotation_2020-05-25_112721.png'),
(106, 'T202005310014', '2020-05-31 11:49:36', 'Ticket Submited. Category: Hardware(Printer)', 'Testing ', 'A500000', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` varchar(5) NOT NULL,
  `username` varchar(7) NOT NULL,
  `password` varchar(32) NOT NULL,
  `level` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `level`) VALUES
('U0253', 'A000000', 'd32a0d021f6318aa5005b56d179a6efe', 'Admin'),
('U0255', 'A200000', 'c316e4941ab042ac78a77854a8c3bec6', 'Technician'),
('U0257', 'A400000', '777512a52dc1fc5f85c3005a5f442666', 'User'),
('U0258', 'A500000', 'b36d9fa31d0572febdcf2d2b2f4d3063', 'User'),
('U0259', 'A100000', 'ad1cdcc5c42745659538bc4aa98df751', 'Technician');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bagian_departemen`
--
ALTER TABLE `bagian_departemen`
  ADD PRIMARY KEY (`id_bagian_dept`),
  ADD KEY `fk_id_dept` (`id_dept`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id_dept`);

--
-- Indexes for table `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`id_informasi`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik`),
  ADD KEY `fk_id_bagian_dept` (`id_bagian_dept`),
  ADD KEY `fk_id_jabatan` (`id_jabatan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kondisi`
--
ALTER TABLE `kondisi`
  ADD PRIMARY KEY (`id_kondisi`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- Indexes for table `sub_kategori`
--
ALTER TABLE `sub_kategori`
  ADD PRIMARY KEY (`id_sub_kategori`),
  ADD KEY `fk_id_kategori` (`id_kategori`);

--
-- Indexes for table `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`id_teknisi`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id_ticket`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking`
  ADD PRIMARY KEY (`id_tracking`),
  ADD KEY `fk_id_ticket` (`id_ticket`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `fk_nik` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bagian_departemen`
--
ALTER TABLE `bagian_departemen`
  MODIFY `id_bagian_dept` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id_dept` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `informasi`
--
ALTER TABLE `informasi`
  MODIFY `id_informasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kondisi`
--
ALTER TABLE `kondisi`
  MODIFY `id_kondisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id_lokasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sub_kategori`
--
ALTER TABLE `sub_kategori`
  MODIFY `id_sub_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tracking`
--
ALTER TABLE `tracking`
  MODIFY `id_tracking` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bagian_departemen`
--
ALTER TABLE `bagian_departemen`
  ADD CONSTRAINT `fk_id_dept` FOREIGN KEY (`id_dept`) REFERENCES `departemen` (`id_dept`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `fk_id_bagian_dept` FOREIGN KEY (`id_bagian_dept`) REFERENCES `bagian_departemen` (`id_bagian_dept`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_jabatan` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_kategori`
--
ALTER TABLE `sub_kategori`
  ADD CONSTRAINT `fk_id_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tracking`
--
ALTER TABLE `tracking`
  ADD CONSTRAINT `fk_id_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_nik` FOREIGN KEY (`username`) REFERENCES `karyawan` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
