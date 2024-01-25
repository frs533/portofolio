-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_herstyle.tbl_admin
CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_herstyle.tbl_admin: ~2 rows (approximately)
INSERT INTO `tbl_admin` (`id_admin`, `username`, `password`) VALUES
	(1, 'zhahira', '12345678'),
	(2, 'Anang', '1');

-- Dumping structure for table db_herstyle.tbl_coment
CREATE TABLE IF NOT EXISTS `tbl_coment` (
  `id_coment` int(11) NOT NULL AUTO_INCREMENT,
  `isi_coment` text NOT NULL,
  `tgl_coment` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  PRIMARY KEY (`id_coment`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_herstyle.tbl_coment: ~3 rows (approximately)
INSERT INTO `tbl_coment` (`id_coment`, `isi_coment`, `tgl_coment`, `id_user`, `id_barang`) VALUES
	(4, 'Keren', '2023-11-10', 1, 6),
	(5, 'Mantap', '2023-11-03', 2, 6);

-- Dumping structure for table db_herstyle.tbl_databarang
CREATE TABLE IF NOT EXISTS `tbl_databarang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `ft_barang` longblob NOT NULL,
  `material` varchar(100) NOT NULL,
  `ukuran` varchar(10) NOT NULL,
  `warna` varchar(100) NOT NULL,
  `tipe` varchar(100) NOT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_herstyle.tbl_databarang: ~2 rows (approximately)
INSERT INTO `tbl_databarang` (`id_barang`, `nama`, `harga`, `deskripsi`, `ft_barang`, `material`, `ukuran`, `warna`, `tipe`) VALUES
	(6, 'Serpenti Maxi Dress', 100000, ' Step into whimsy and charm with this fun ensemble crafted from luxe\r\n printed chiffon. The plunge neckline, double slits, and split-detail sleeves\r\n make it a Summer showstopper. Be the center of attention wherever you go.', _binary 0x75706c6f6164732f627267312e706e67, 'cotton', 'XS', 'hijau', 'Dresses'),
	(7, 'Serpenti Slip Dress', 200000, ' Step into whimsy and charm with this fun ensemble crafted from luxe\r\n printed chiffon. The plunge neckline, double slits, and split-detail sleeves\r\n make it a Summer showstopper. Be the center of attention wherever you go.', _binary 0x75706c6f6164732f627267322e706e67, 'cotton', 'S', 'Biru', 'Dresses'),
	(10, 'Serpenti Maxi Dress', 100000, ' Step into whimsy and charm with this fun ensemble crafted from luxe\r\n printed chiffon. The plunge neckline, double slits, and split-detail sleeves\r\n make it a Summer showstopper. Be the center of attention wherever you go.', _binary 0x75706c6f6164732f627267312e706e67, 'cotton', 'S', 'hijau', 'Dresses'),
	(11, 'Serpenti Slip Dress', 200000, ' Step into whimsy and charm with this fun ensemble crafted from luxe\r\n printed chiffon. The plunge neckline, double slits, and split-detail sleeves\r\n make it a Summer showstopper. Be the center of attention wherever you go.', _binary 0x75706c6f6164732f627267322e706e67, 'cotton', 'L', 'Biru', 'Dresses'),
	(12, 'barang 3', 100000, 'Step into whimsy and charm with this fun ensemble crafted from luxe printed chiffon. The plunge neckline, double slits, and split-detail sleeves make it a Summer showstopper. Be the center of attention wherever you go.', _binary 0x75706c6f6164732f64726573735374796c652e706e67, 'kulit', 'XS', 'merah', 'Tops');

-- Dumping structure for table db_herstyle.tbl_pesanan
CREATE TABLE IF NOT EXISTS `tbl_pesanan` (
  `id_pesanan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `total_biaya` int(11) DEFAULT NULL,
  `tgl_pesanan` date NOT NULL,
  `status_pesanan` varchar(100) DEFAULT NULL,
  `metode_pembayaran` enum('OVO','BRI') DEFAULT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  PRIMARY KEY (`id_pesanan`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_herstyle.tbl_pesanan: ~6 rows (approximately)
INSERT INTO `tbl_pesanan` (`id_pesanan`, `id_user`, `total_biaya`, `tgl_pesanan`, `status_pesanan`, `metode_pembayaran`, `id_barang`, `jumlah`) VALUES
	(7, 2, 500000, '2023-11-03', 'berhasil', 'OVO', 6, 5),
	(8, 2, 200000, '2023-11-03', 'berhasil', 'OVO', 7, 1),
	(9, 2, 100000, '2023-11-04', 'berhasil', 'OVO', 6, 1),
	(11, 2, 100000, '2023-11-04', 'berhasil', 'BRI', 6, 1),
	(12, 2, 100000, '2023-11-04', 'berhasil', 'OVO', 6, 1),
	(13, 2, 100000, '2023-11-04', 'berhasil', 'OVO', 6, 1),
	(17, 2, 100000, '2023-11-04', 'berhasil', 'OVO', 6, 1),
	(18, 2, 1000000, '2023-11-05', 'berhasil', 'BRI', 6, 10),
	(20, 2, 200000, '2023-11-05', 'menunggu', NULL, 6, 2),
	(21, 2, 200000, '2023-11-05', 'menunggu', NULL, 7, 1);

-- Dumping structure for table db_herstyle.tbl_transaksi
CREATE TABLE IF NOT EXISTS `tbl_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_transaksi` date NOT NULL,
  `no_rek` varchar(30) NOT NULL,
  `total_biaya` int(11) NOT NULL,
  `metode_pembayaran` enum('OVO','BRI') NOT NULL,
  `id_user` int(11) NOT NULL,
  `status_transaksi` enum('Belum','Sudah') NOT NULL DEFAULT 'Belum',
  `bukti_pembayaran` longblob NOT NULL,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_herstyle.tbl_transaksi: ~6 rows (approximately)
INSERT INTO `tbl_transaksi` (`id_transaksi`, `tgl_transaksi`, `no_rek`, `total_biaya`, `metode_pembayaran`, `id_user`, `status_transaksi`, `bukti_pembayaran`) VALUES
	(11, '2023-11-03', 'BRI20250', 500000, 'OVO', 2, 'Belum', _binary ''),
	(12, '2023-11-03', 'OVO2001', 300000, 'OVO', 2, 'Sudah', _binary 0x62756b74692f62756b74695f62617961725f6272696d6f2e6a7067),
	(13, '2023-11-03', '231 3214 1245 9098', 700000, 'BRI', 2, 'Sudah', _binary 0x62756b74692f62756b74695f62617961725f6272696d6f2e6a7067),
	(14, '2023-11-04', '231 3214 1245 9098', 100000, 'OVO', 2, 'Sudah', _binary 0x62756b74692f62617961725f6f766f2e706e67),
	(15, '2023-11-04', '231 3214 1245 9098', 100000, 'BRI', 2, 'Sudah', _binary 0x62756b74692f62617961725f6f766f2e706e67),
	(16, '2023-11-04', '231 3214 1245 9098', 100000, 'OVO', 2, 'Sudah', _binary 0x62756b74692f62617961725f6f766f2e706e67),
	(17, '2023-11-04', '1786339417', 100000, 'OVO', 2, 'Sudah', _binary 0x62756b74692f62617961725f6f766f2e706e67),
	(18, '2023-11-05', '5453751565', 100000, 'OVO', 2, 'Sudah', _binary 0x62756b74692f62617961725f6f766f2e706e67),
	(19, '2023-11-05', '6680296865', 1000000, 'BRI', 2, 'Sudah', _binary 0x62756b74692f62756b74695f62617961725f6272696d6f2e6a7067);

-- Dumping structure for table db_herstyle.tbl_ulasan
CREATE TABLE IF NOT EXISTS `tbl_ulasan` (
  `id_ulasan` int(11) NOT NULL AUTO_INCREMENT,
  `isi_ulasan` text NOT NULL,
  `tgl_ulasan` date NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_ulasan`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_herstyle.tbl_ulasan: ~1 rows (approximately)
INSERT INTO `tbl_ulasan` (`id_ulasan`, `isi_ulasan`, `tgl_ulasan`, `id_user`) VALUES
	(6, 'ulasan 1', '2023-11-01', 1),
	(7, 'keren', '2023-11-04', 2),
	(8, 'Mantab', '2023-11-05', 2);

-- Dumping structure for table db_herstyle.tbl_user
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama_pelanggan` varchar(200) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_herstyle.tbl_user: ~0 rows (approximately)
INSERT INTO `tbl_user` (`id_user`, `username`, `password`, `nama_pelanggan`, `alamat`) VALUES
	(1, 'dio', '1', 'dio reyhans', 'jl. bromo'),
	(2, 'lala123', '123', 'lala', 'Jl. Merbabu');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
