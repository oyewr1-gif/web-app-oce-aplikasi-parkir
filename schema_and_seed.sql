-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.20.0.7320
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `parkir_db` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `parkir_db`;

-- Dumping structure for table parkir_db.android_user
CREATE TABLE IF NOT EXISTS `android_user` (
  `id` varchar(500) NOT NULL,
  `gcm_id` text NOT NULL,
  `email` text NOT NULL,
  `device` varchar(60) NOT NULL,
  `status_update` int(11) NOT NULL,
  `status_update_apk` int(11) NOT NULL,
  `tgl_masuk` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.billing_member
CREATE TABLE IF NOT EXISTS `billing_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notrx` varchar(250) DEFAULT NULL,
  `idkartu` int(11) DEFAULT NULL,
  `nokartu` varchar(250) DEFAULT NULL,
  `jnkartu` varchar(250) DEFAULT NULL,
  `idperusahaan` int(11) DEFAULT NULL,
  `nama_perusahaan` varchar(250) DEFAULT NULL,
  `nama_member` varchar(250) DEFAULT NULL,
  `kendaraan` varchar(250) DEFAULT NULL,
  `noplat` varchar(250) DEFAULT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `lantai` varchar(250) DEFAULT NULL,
  `blok` varchar(250) DEFAULT NULL,
  `no_blok` varchar(250) DEFAULT NULL,
  `tgl_perpanjangan` date DEFAULT NULL,
  `expired` date DEFAULT NULL,
  `biaya` int(11) DEFAULT NULL,
  `status_bayar` varchar(60) DEFAULT 'BELUM LUNAS',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.booking
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtrx` varchar(60) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `nopol` varchar(250) DEFAULT NULL,
  `tglPesan` datetime DEFAULT NULL,
  `tglPemesanan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tglExpired` datetime DEFAULT NULL,
  `iddevice` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.bulan
CREATE TABLE IF NOT EXISTS `bulan` (
  `id` varchar(2) DEFAULT NULL,
  `bulan` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for procedure parkir_db.cari_transaksi
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `cari_transaksi`(
	notrx VARCHAR(100)
)
BEGIN
    SELECT * 
    FROM jurnal_transaksi
    WHERE idtrx = notrx;
END//
DELIMITER ;

-- Dumping structure for table parkir_db.data_kartu
CREATE TABLE IF NOT EXISTS `data_kartu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idperusahaan` int(11) DEFAULT 0,
  `idjenisKartu` int(11) DEFAULT NULL,
  `saldoawal` int(11) DEFAULT NULL,
  `saldoakhir` int(11) DEFAULT 1000,
  `nokartu` varchar(60) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `nohp` varchar(60) DEFAULT NULL,
  `perusahaan` varchar(250) DEFAULT NULL,
  `tgldaftar` date DEFAULT '0000-00-00',
  `tglexpired` date DEFAULT '0000-00-00',
  `idtrx` varchar(60) DEFAULT NULL,
  `statusTrx` int(11) DEFAULT 1,
  `statusAktif` int(11) DEFAULT 1,
  `nopol1` varchar(60) DEFAULT NULL,
  `nopol2` varchar(60) DEFAULT NULL,
  `nopol3` varchar(60) DEFAULT NULL,
  `nopol` varchar(60) DEFAULT NULL,
  `kat` varchar(60) DEFAULT NULL,
  `jenisTrx` varchar(60) DEFAULT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `kdkendaraan` varchar(2) DEFAULT NULL,
  `namaKendaraan` varchar(60) DEFAULT NULL,
  `noidentitas` varchar(60) DEFAULT '-',
  `jatuhtempo` varchar(60) DEFAULT 'Expired',
  `statusAP` int(11) DEFAULT 1,
  `awalPakai` int(11) DEFAULT 0,
  `lantai` varchar(60) DEFAULT '-',
  `blok` varchar(60) DEFAULT '-',
  `no_blok` varchar(60) DEFAULT '-',
  `tgl_perpanjangan` date DEFAULT '0000-00-00',
  `in_billing` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.data_kartu_temp
CREATE TABLE IF NOT EXISTS `data_kartu_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idperusahaan` int(11) DEFAULT NULL,
  `idjenisKartu` int(11) DEFAULT NULL,
  `saldoawal` int(11) DEFAULT NULL,
  `saldoakhir` int(11) DEFAULT NULL,
  `nokartu` varchar(60) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `nohp` varchar(60) DEFAULT NULL,
  `perusahaan` varchar(250) DEFAULT NULL,
  `tgldaftar` date DEFAULT '0000-00-00',
  `tglexpired` date DEFAULT '0000-00-00',
  `idtrx` varchar(60) DEFAULT NULL,
  `statusTrx` int(11) DEFAULT NULL,
  `statusAktif` int(11) DEFAULT NULL,
  `nopol1` varchar(60) DEFAULT NULL,
  `nopol2` varchar(60) DEFAULT NULL,
  `nopol3` varchar(60) DEFAULT NULL,
  `nopol` varchar(60) DEFAULT NULL,
  `kat` varchar(60) DEFAULT NULL,
  `jenisTrx` varchar(60) DEFAULT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `kdkendaraan` varchar(2) DEFAULT NULL,
  `namaKendaraan` varchar(60) DEFAULT NULL,
  `noidentitas` varchar(60) DEFAULT NULL,
  `jatuhtempo` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.data_setoran
CREATE TABLE IF NOT EXISTS `data_setoran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_setoran` varchar(60) DEFAULT NULL,
  `waktu` datetime DEFAULT '0000-00-00 00:00:00',
  `iduser_penerima` int(11) DEFAULT NULL,
  `iduser_kasir` int(11) DEFAULT NULL,
  `nama_kasir` varchar(60) DEFAULT NULL,
  `shift` varchar(60) DEFAULT NULL,
  `pintu` varchar(60) DEFAULT NULL,
  `tglsetoran` date DEFAULT '0000-00-00',
  `login` datetime DEFAULT '0000-00-00 00:00:00',
  `logout` datetime DEFAULT '0000-00-00 00:00:00',
  `jumuang` int(11) DEFAULT 0,
  `fisik` int(11) DEFAULT 0,
  `jummasalah` int(11) DEFAULT 0,
  `jumuangmasalah` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.detail_setoran
CREATE TABLE IF NOT EXISTS `detail_setoran` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `no_setoran` varchar(60) DEFAULT NULL,
  `nama_kendaraan` varchar(60) DEFAULT NULL,
  `tunai` int(11) DEFAULT NULL,
  `qris` int(11) DEFAULT NULL,
  `prepaid` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.jenis_kendaraan
CREATE TABLE IF NOT EXISTS `jenis_kendaraan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jn_kendaraan` varchar(2) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `terpakai` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Seed jenis_kendaraan
TRUNCATE TABLE `jenis_kendaraan`;
INSERT INTO `jenis_kendaraan` (`id`, `jn_kendaraan`, `nama`, `kapasitas`, `terpakai`) VALUES
(1, '01', 'Sepeda Motor', 200, 3),
(2, '02', 'Mobil / Roda 4', 100, 2),
(3, '03', 'Bus / Truk', 30, 0);

-- Dumping structure for table parkir_db.tarif_awal
CREATE TABLE IF NOT EXISTS `tarif_awal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kendaraan` varchar(2) DEFAULT NULL,
  `durasi` varchar(60) DEFAULT '1 Jam',
  `rupiah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

TRUNCATE TABLE `tarif_awal`;
INSERT INTO `tarif_awal` (`id`, `kode_kendaraan`, `durasi`, `rupiah`) VALUES
(1, '01', '1 Jam', 2000),
(2, '02', '1 Jam', 5000),
(3, '03', '1 Jam', 10000);

-- Dumping structure for table parkir_db.tarif_berjalan
CREATE TABLE IF NOT EXISTS `tarif_berjalan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kendaraan` varchar(2) DEFAULT NULL,
  `rupiah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

TRUNCATE TABLE `tarif_berjalan`;
INSERT INTO `tarif_berjalan` (`id`, `kode_kendaraan`, `rupiah`) VALUES
(1, '01', 1000),
(2, '02', 2000),
(3, '03', 5000);

-- Dumping structure for table parkir_db.jurnal_transaksi
CREATE TABLE IF NOT EXISTS `jurnal_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtrx` varchar(60) NOT NULL DEFAULT '0',
  `nopol` varchar(11) DEFAULT NULL,
  `jn_kendaraan` varchar(60) DEFAULT NULL,
  `tgl_masuk` varchar(60) DEFAULT NULL,
  `jam_masuk` varchar(60) DEFAULT NULL,
  `tgl_keluar` varchar(60) DEFAULT NULL,
  `jam_keluar` varchar(60) DEFAULT NULL,
  `tarif` int(11) NOT NULL DEFAULT 0,
  `denda` int(11) DEFAULT 0,
  `jenis` varchar(60) DEFAULT NULL,
  `id_user` varchar(2) DEFAULT NULL,
  `shift` varchar(60) DEFAULT NULL,
  `status` varchar(1) DEFAULT 'B',
  `jenis_transaksi` varchar(60) DEFAULT NULL,
  `idfoto` int(11) DEFAULT 0,
  `tgltransaksi` date DEFAULT '0000-00-00',
  `gate` varchar(60) NOT NULL DEFAULT 'GATE-IN-01',
  `bulan` varchar(2) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  `gateout` varchar(250) NOT NULL DEFAULT 'GATE-OUT-01',
  `waktuMasuk` datetime DEFAULT CURRENT_TIMESTAMP,
  `waktuKeluar` datetime DEFAULT NULL,
  `durasi` varchar(60) DEFAULT '00:00:00',
  `status_tiket` int(1) DEFAULT 1,
  `status_bayar` int(1) DEFAULT 1,
  `jam_transaksi` varchar(10) DEFAULT '00:00:00',
  `stnk` varchar(250) DEFAULT NULL,
  `kode_produk_kartu` varchar(50) DEFAULT NULL,
  `jenis_tarif` varchar(50) DEFAULT NULL,
  `idkartu` varchar(60) DEFAULT NULL,
  `waktubatal` datetime DEFAULT NULL,
  `ketbatal` varchar(250) DEFAULT NULL,
  `iduser_pembatalan` int(11) DEFAULT NULL,
  `jntrx_ex_pembatalan` varchar(60) DEFAULT 'N',
  `status_ex_pembatalan` varchar(1) DEFAULT 'N',
  `waktu_batal_pembatalan` datetime DEFAULT NULL,
  `iduser_batal_pembatalan` int(11) DEFAULT NULL,
  `ketbatalpembatalan` varchar(250) DEFAULT NULL,
  `waktu_batal_status_keluar` datetime DEFAULT NULL,
  `iduser_batal_status_keluar` int(11) DEFAULT NULL,
  `ket_batal_status_keluar` varchar(250) DEFAULT NULL,
  `nohp` varchar(60) DEFAULT NULL,
  `nostnk` varchar(60) DEFAULT NULL,
  `noktp` varchar(60) DEFAULT NULL,
  `nama` varchar(60) DEFAULT NULL,
  `tarif_awal` bigint(20) DEFAULT NULL,
  `cara_bayar` varchar(250) DEFAULT NULL,
  `tarif_pilah` bigint(20) DEFAULT NULL,
  `refbayar` varchar(250) DEFAULT NULL,
  `jenis_identitas` varchar(250) DEFAULT NULL,
  `CardType` varchar(250) DEFAULT NULL,
  `MID` varchar(250) DEFAULT NULL,
  `TID` varchar(250) DEFAULT NULL,
  `transDate` varchar(250) DEFAULT NULL,
  `cardNo` varchar(250) DEFAULT NULL,
  `amount` bigint(20) DEFAULT NULL,
  `balance` bigint(20) DEFAULT NULL,
  `transCounter` varchar(250) DEFAULT NULL,
  `transLog` text DEFAULT NULL,
  `status_satt` int(1) DEFAULT 0,
  `nama_bank` varchar(60) DEFAULT NULL,
  `cek1` int(11) DEFAULT 0,
  `cek2` int(11) DEFAULT 0,
  `resp_reader` text DEFAULT NULL,
  `discount` int(11) DEFAULT 0,
  `voucher` int(11) DEFAULT 0,
  `kode_voucher` varchar(60) DEFAULT '0',
  `bayar` int(11) DEFAULT 0,
  `kembalian` int(11) DEFAULT 0,
  `no_laporan` varchar(60) DEFAULT '-',
  PRIMARY KEY (`id`,`idtrx`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

TRUNCATE TABLE `jurnal_transaksi`;
INSERT INTO `jurnal_transaksi` (`id`, `idtrx`, `nopol`, `jn_kendaraan`, `tgl_masuk`, `jam_masuk`, `tgl_keluar`, `jam_keluar`, `tarif`, `status`, `waktuMasuk`, `waktuKeluar`, `durasi`, `status_bayar`, `bayar`, `kembalian`) VALUES
(1, 'TRX20260818001', 'B 1234 ABC', 'Sepeda Motor', '2026-08-18', '08:00:00', '2026-08-18', '10:30:00', 4000, 'S', '2026-08-18 08:00:00', '2026-08-18 10:30:00', '02:30:00', 1, 5000, 1000),
(2, 'TRX20260818002', 'B 5678 XYZ', 'Mobil / Roda 4', '2026-08-18', '09:15:00', '2026-08-18', '11:15:00', 7000, 'S', '2026-08-18 09:15:00', '2026-08-18 11:15:00', '02:00:00', 1, 10000, 3000),
(3, 'TRX20260818003', 'B 9999 EFG', 'Sepeda Motor', '2026-08-18', '12:00:00', NULL, NULL, 0, 'B', NOW(), NULL, '00:00:00', 0, 0, 0),
(4, 'TRX20260818004', 'D 4321 MNO', 'Sepeda Motor', '2026-08-18', '12:30:00', NULL, NULL, 0, 'B', NOW(), NULL, '00:00:00', 0, 0, 0),
(5, 'TRX20260818005', 'F 7777 LMN', 'Mobil / Roda 4', '2026-08-18', '13:00:00', NULL, NULL, 0, 'B', NOW(), NULL, '00:00:00', 0, 0, 0);

-- Dumping structure for table parkir_db.level_user
CREATE TABLE IF NOT EXISTS `level_user` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

TRUNCATE TABLE `level_user`;
INSERT INTO `level_user` (`id`, `nama`) VALUES
(1, 'Administrator'),
(2, 'Kasir / Petugas Parkir');

-- Dumping structure for table parkir_db.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `jabatan` varchar(60) NOT NULL,
  `level` int(11) NOT NULL,
  `id_posisi_kerja` int(11) NOT NULL DEFAULT 1,
  `tempat_kerja` varchar(60) NOT NULL DEFAULT 'Pos 1',
  `foto` varchar(250) DEFAULT NULL,
  `shift` varchar(60) DEFAULT 'Shift 1',
  `notlp` varchar(250) DEFAULT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

TRUNCATE TABLE `user`;
INSERT INTO `user` (`id`, `username`, `password`, `nama`, `jabatan`, `level`, `id_posisi_kerja`, `tempat_kerja`, `shift`, `email`) VALUES
(1, 'admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1fSgVf4H1jW2t.lE9l/5y1t6KkF0E.a', 'Administrator Systems', 'Manager Parkir', 1, 1, 'Pos 1', 'Shift 1', 'admin@parkir.local'),
(2, 'kasir', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1fSgVf4H1jW2t.lE9l/5y1t6KkF0E.a', 'Petugas Kasir 1', 'Operator Gate', 2, 1, 'Pos 1', 'Shift 1', 'kasir@parkir.local');

-- Dumping structure for table parkir_db.member
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` varchar(60) DEFAULT NULL,
  `jn_kendaraan` varchar(60) DEFAULT NULL,
  `nopol` varchar(10) DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_akhir` date DEFAULT NULL,
  `nama` varchar(60) DEFAULT NULL,
  `alamat` mediumtext DEFAULT NULL,
  `no_tlp` varchar(60) DEFAULT NULL,
  `jn_identitas` varchar(10) DEFAULT NULL,
  `no_identitas` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `nama_kendaraan` varchar(60) DEFAULT NULL,
  `status_kartu` varchar(11) DEFAULT NULL,
  `status_parkir` varchar(11) DEFAULT NULL,
  `idtrx` varchar(60) DEFAULT NULL,
  `tolerasi` date DEFAULT NULL,
  `kd_produk` varchar(60) DEFAULT NULL,
  `kat_kartu` varchar(60) DEFAULT NULL,
  `kd_perusahaan` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

TRUNCATE TABLE `member`;
INSERT INTO `member` (`id`, `id_member`, `jn_kendaraan`, `nopol`, `tgl_mulai`, `tgl_akhir`, `nama`, `alamat`, `no_tlp`, `status`) VALUES
(1, 'MBR-001', 'Sepeda Motor', 'B 3333 MBR', '2026-01-01', '2026-12-31', 'Budi Santoso', 'Jl. Merdeka No. 10', '081234567890', 1),
(2, 'MBR-002', 'Mobil / Roda 4', 'B 8888 MBR', '2026-01-01', '2026-12-31', 'Siti Aminah', 'Jl. Sudirman No. 45', '085678901234', 1);

-- Dumping structure for table parkir_db.pos_kasir
CREATE TABLE IF NOT EXISTS `pos_kasir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `pos_kasir` (`id`, `nama`) VALUES (1, 'POS 1'), (2, 'POS 2');

-- Dumping structure for table parkir_db.gate_online
CREATE TABLE IF NOT EXISTS `gate_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gate` varchar(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.gatemanual
CREATE TABLE IF NOT EXISTS `gatemanual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(60) DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `foto` mediumblob DEFAULT NULL,
  `gate` varchar(11) DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `nopol` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.history_login
CREATE TABLE IF NOT EXISTS `history_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `w_login` datetime DEFAULT NULL,
  `w_logout` datetime DEFAULT NULL,
  `lokasi` varchar(60) DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.voucher_tarif
CREATE TABLE IF NOT EXISTS `voucher_tarif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu_buat` datetime DEFAULT NULL,
  `kode_voucher` varchar(60) DEFAULT NULL,
  `kode_trx` varchar(60) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL,
  `masa_berlaku` date DEFAULT NULL,
  `waktu_terpakai` datetime DEFAULT NULL,
  `status_terpakai` varchar(60) DEFAULT 'BELUM',
  `status_aktif` varchar(60) DEFAULT 'AKTIF',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping structure for table parkir_db.work_station
CREATE TABLE IF NOT EXISTS `work_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(60) DEFAULT NULL,
  `lokasi` varchar(250) DEFAULT NULL,
  `type` varchar(60) DEFAULT NULL,
  `code` varchar(60) DEFAULT NULL,
  `lat` varchar(250) DEFAULT NULL,
  `lng` varchar(250) DEFAULT NULL,
  `pengelolaid` varchar(250) DEFAULT NULL,
  `nama_pengelola` varchar(250) DEFAULT NULL,
  `prov` varchar(250) DEFAULT NULL,
  `kota` varchar(250) DEFAULT NULL,
  `r2` int(11) DEFAULT NULL,
  `r4` int(11) DEFAULT NULL,
  `jn_tarif` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
