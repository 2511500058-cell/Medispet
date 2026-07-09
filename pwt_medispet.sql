-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 09, 2026 at 04:17 AM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pwt_medispet`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_obat`
--

CREATE TABLE `detail_obat` (
  `ID_Detail_Obat` int(11) NOT NULL,
  `ID_Kunjungan` int(11) NOT NULL,
  `ID_Obat` int(11) NOT NULL,
  `Jumlah` int(11) NOT NULL,
  `Total_Harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_obat`
--

INSERT INTO `detail_obat` (`ID_Detail_Obat`, `ID_Kunjungan`, `ID_Obat`, `Jumlah`, `Total_Harga`) VALUES
(2, 2, 1, 1, 100000.00);

-- --------------------------------------------------------

--
-- Table structure for table `detail_tindakan`
--

CREATE TABLE `detail_tindakan` (
  `ID_Detail` int(11) NOT NULL,
  `ID_Kunjungan` int(11) DEFAULT NULL,
  `ID_Tindakan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_tindakan`
--

INSERT INTO `detail_tindakan` (`ID_Detail`, `ID_Kunjungan`, `ID_Tindakan`) VALUES
(1, 2, 8),
(2, 2, 3),
(3, 3, 6),
(4, 3, 2),
(5, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `ID_Dokter` int(11) NOT NULL,
  `Password` varchar(10) NOT NULL,
  `Nama_Dokter` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`ID_Dokter`, `Password`, `Nama_Dokter`) VALUES
(1, 'Reyn123', 'Reynanda Oktarian M.Si'),
(2, 'Asep123', 'Asep Orlandos'),
(4, 'Agus123', 'Agus Down'),
(5, 'Tirta123', 'Dr. Tirta');

-- --------------------------------------------------------

--
-- Table structure for table `hewan`
--

CREATE TABLE `hewan` (
  `ID_Hewan` int(11) NOT NULL,
  `ID_Pemilik` int(11) DEFAULT NULL,
  `Nama_Hewan` varchar(100) NOT NULL,
  `Spesies` varchar(50) DEFAULT NULL,
  `Ras` varchar(50) DEFAULT NULL,
  `Jenis_Kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `Tanggal_Lahir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hewan`
--

INSERT INTO `hewan` (`ID_Hewan`, `ID_Pemilik`, `Nama_Hewan`, `Spesies`, `Ras`, `Jenis_Kelamin`, `Tanggal_Lahir`) VALUES
(1, 1, 'Miku', 'Kucing', 'Anggora', 'Betina', '2024-11-22'),
(2, 1, 'Rehan', 'Anjing', 'Golden Retriever', 'Jantan', '2023-02-22'),
(3, 2, 'Farhan', 'Anjing', 'Golden Retriever', 'Jantan', '2024-03-22');

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `ID_Kunjungan` int(11) NOT NULL,
  `ID_Hewan` int(11) DEFAULT NULL,
  `ID_Dokter` int(11) DEFAULT NULL,
  `Tanggal_Kunjungan` date NOT NULL,
  `Keluhan` text,
  `Diagnosa` text,
  `Catatan_Medis` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kunjungan`
--

INSERT INTO `kunjungan` (`ID_Kunjungan`, `ID_Hewan`, `ID_Dokter`, `Tanggal_Kunjungan`, `Keluhan`, `Diagnosa`, `Catatan_Medis`) VALUES
(1, 1, 5, '2026-06-27', 'Demam', '', ''),
(2, 2, 1, '2026-06-27', 'Muntah', 'Kemungkinan terkena penyakit serius', 'Bla bla bla ble ble ble blu blu blu'),
(3, 3, 2, '2026-06-27', 'Muntah', 'nkdjokeojkdnfm', 'srjfsojfweoiwh');

-- --------------------------------------------------------

--
-- Table structure for table `medispet`
--

CREATE TABLE `medispet` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dokter','pasien') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medispet`
--

INSERT INTO `medispet` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin123', 'admin'),
(2, 'dokter', 'dokter123', 'dokter'),
(3, 'pasien', 'pasien123', 'pasien'),
(4, 'dandadan', '1234', 'pasien'),
(5, 'aseporlando', 'asep111', 'dokter'),
(6, 'agusdown', 'Agus123', 'dokter'),
(7, 'agusdown', 'Agus123', 'dokter'),
(8, 'reynandaoktarianmsi', 'Reyn123', 'dokter'),
(9, 'aseporlandos', 'Asep123', 'dokter'),
(10, 'drtirta', 'Tirta123', 'dokter'),
(11, 'agusdown', 'Agus123', 'dokter'),
(12, 'reynandaoktarianmsi', 'Reyn123', 'dokter'),
(13, 'reynandaoktarianmsi', 'Reyn123', 'dokter'),
(14, 'reynandatarianmsi', 'Reyn123', 'dokter'),
(15, 'reynandaoktarianmsi', 'Reyn123', 'dokter'),
(16, 'reynandaoktarianmsi', 'Reyn123', 'dokter');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `ID_Obat` int(11) NOT NULL,
  `Nama_Obat` varchar(100) NOT NULL,
  `Harga` decimal(10,2) NOT NULL DEFAULT '0.00',
  `Stok` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`ID_Obat`, `Nama_Obat`, `Harga`, `Stok`) VALUES
(1, 'Paracetamol', 100000.00, 0),
(2, 'Antibiotik', 50000.00, 0),
(3, 'Anthelmentika', 75000.00, 0),
(4, 'Vitamin dan Suplemen', 45000.00, 0),
(5, 'Obat Kutu & Parasit', 30000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pemilik`
--

CREATE TABLE `pemilik` (
  `ID_Pemilik` int(11) NOT NULL,
  `Nama_Pemilik` varchar(100) NOT NULL,
  `No_Telepon` varchar(20) DEFAULT NULL,
  `Alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemilik`
--

INSERT INTO `pemilik` (`ID_Pemilik`, `Nama_Pemilik`, `No_Telepon`, `Alamat`) VALUES
(1, 'Tio', '081369741911', 'Celuak'),
(2, 'Ronal', '081300000000', 'SL');

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `ID_Resep` int(11) NOT NULL,
  `ID_Kunjungan` int(11) DEFAULT NULL,
  `ID_Obat` int(11) DEFAULT NULL,
  `Dosis` varchar(50) DEFAULT NULL,
  `Jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tindakan`
--

CREATE TABLE `tindakan` (
  `ID_Tindakan` int(11) NOT NULL,
  `Nama_Tindakan` varchar(150) NOT NULL,
  `Biaya` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tindakan`
--

INSERT INTO `tindakan` (`ID_Tindakan`, `Nama_Tindakan`, `Biaya`) VALUES
(1, 'Pemeriksaan Umum & Konsultasi Dokter', 50000.00),
(2, 'Suntik Vitamin / Suplemen Hewan', 30000.00),
(3, 'Suntik Antibiotik / Obat Radang', 45000.00),
(4, 'Vaksinasi Kucing Tahunan (Feline Chlamydia)', 175000.00),
(5, 'Vaksinasi Anjing Tahunan (Rabies & Parvo)', 200000.00),
(6, 'Operasi Steril Kucing Jantan (Kastrasi)', 350000.00),
(7, 'Operasi Steril Kucing Betina (OH)', 550000.00),
(8, 'Rawat Inap Non-Infeksius (Per Hari)', 75000.00),
(9, 'Pembersihan Karang Gigi (Scaling)', 250000.00),
(10, 'Treatment Jamur & Kutu (Grooming Medis)', 85000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_obat`
--
ALTER TABLE `detail_obat`
  ADD PRIMARY KEY (`ID_Detail_Obat`),
  ADD KEY `ID_Kunjungan` (`ID_Kunjungan`),
  ADD KEY `ID_Obat` (`ID_Obat`);

--
-- Indexes for table `detail_tindakan`
--
ALTER TABLE `detail_tindakan`
  ADD PRIMARY KEY (`ID_Detail`),
  ADD KEY `ID_Kunjungan` (`ID_Kunjungan`),
  ADD KEY `ID_Tindakan` (`ID_Tindakan`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`ID_Dokter`);

--
-- Indexes for table `hewan`
--
ALTER TABLE `hewan`
  ADD PRIMARY KEY (`ID_Hewan`),
  ADD KEY `ID_Pemilik` (`ID_Pemilik`);

--
-- Indexes for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`ID_Kunjungan`),
  ADD KEY `ID_Hewan` (`ID_Hewan`),
  ADD KEY `ID_Dokter` (`ID_Dokter`);

--
-- Indexes for table `medispet`
--
ALTER TABLE `medispet`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`ID_Obat`);

--
-- Indexes for table `pemilik`
--
ALTER TABLE `pemilik`
  ADD PRIMARY KEY (`ID_Pemilik`);

--
-- Indexes for table `resep`
--
ALTER TABLE `resep`
  ADD PRIMARY KEY (`ID_Resep`),
  ADD KEY `ID_Kunjungan` (`ID_Kunjungan`),
  ADD KEY `ID_Obat` (`ID_Obat`);

--
-- Indexes for table `tindakan`
--
ALTER TABLE `tindakan`
  ADD PRIMARY KEY (`ID_Tindakan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_obat`
--
ALTER TABLE `detail_obat`
  MODIFY `ID_Detail_Obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_tindakan`
--
ALTER TABLE `detail_tindakan`
  MODIFY `ID_Detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `ID_Dokter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hewan`
--
ALTER TABLE `hewan`
  MODIFY `ID_Hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `ID_Kunjungan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `medispet`
--
ALTER TABLE `medispet`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `ID_Obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pemilik`
--
ALTER TABLE `pemilik`
  MODIFY `ID_Pemilik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `ID_Resep` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tindakan`
--
ALTER TABLE `tindakan`
  MODIFY `ID_Tindakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_tindakan`
--
ALTER TABLE `detail_tindakan`
  ADD CONSTRAINT `detail_tindakan_ibfk_1` FOREIGN KEY (`ID_Kunjungan`) REFERENCES `kunjungan` (`ID_Kunjungan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_tindakan_ibfk_2` FOREIGN KEY (`ID_Tindakan`) REFERENCES `tindakan` (`ID_Tindakan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hewan`
--
ALTER TABLE `hewan`
  ADD CONSTRAINT `hewan_ibfk_1` FOREIGN KEY (`ID_Pemilik`) REFERENCES `pemilik` (`ID_Pemilik`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD CONSTRAINT `kunjungan_ibfk_1` FOREIGN KEY (`ID_Hewan`) REFERENCES `hewan` (`ID_Hewan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kunjungan_ibfk_2` FOREIGN KEY (`ID_Dokter`) REFERENCES `dokter` (`ID_Dokter`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `resep`
--
ALTER TABLE `resep`
  ADD CONSTRAINT `resep_ibfk_1` FOREIGN KEY (`ID_Kunjungan`) REFERENCES `kunjungan` (`ID_Kunjungan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resep_ibfk_2` FOREIGN KEY (`ID_Obat`) REFERENCES `obat` (`ID_Obat`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
