-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_itenas_resource_d1
CREATE DATABASE IF NOT EXISTS `db_itenas_resource_d1` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;
USE `db_itenas_resource_d1`;

-- Dumping structure for table db_itenas_resource_d1.assets
CREATE TABLE IF NOT EXISTS `assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lab_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `serial_number` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 1,
  `condition` enum('good','maintenance','broken') NOT NULL DEFAULT 'good',
  `rental_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assets_serial_number_unique` (`serial_number`),
  KEY `assets_lab_id_foreign` (`lab_id`),
  CONSTRAINT `assets_lab_id_foreign` FOREIGN KEY (`lab_id`) REFERENCES `labs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.assets: ~150 rows (approximately)
INSERT INTO `assets` (`id`, `lab_id`, `name`, `status`, `serial_number`, `description`, `image_path`, `stock`, `condition`, `rental_price`, `created_at`, `updated_at`) VALUES
	(1, 1, 'PC High Spec ROG - Unit 1', 'available', 'IF-42372', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(2, 1, 'PC High Spec ROG - Unit 2', 'available', 'IF-96811', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(3, 1, 'Monitor 24 Inch - Unit 1', 'available', 'IF-66375', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(4, 1, 'Monitor 24 Inch - Unit 2', 'available', 'IF-97540', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(5, 1, 'Server Rack - Unit 1', 'available', 'IF-72297', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(6, 1, 'Server Rack - Unit 2', 'available', 'IF-73033', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(7, 1, 'VR Headset - Unit 1', 'available', 'IF-67734', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(8, 1, 'VR Headset - Unit 2', 'available', 'IF-62364', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(9, 1, 'VR Headset - Unit 3', 'available', 'IF-39379', 'Aset inventaris Informatika', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(10, 2, 'Osiloskop Digital - Unit 1', 'available', 'EL-35582', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(11, 2, 'Osiloskop Digital - Unit 2', 'available', 'EL-40582', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(12, 2, 'Osiloskop Digital - Unit 3', 'available', 'EL-25735', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(13, 2, 'Solder Station - Unit 1', 'available', 'EL-40608', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(14, 2, 'Solder Station - Unit 2', 'available', 'EL-45927', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(15, 2, 'Solder Station - Unit 3', 'available', 'EL-42202', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(16, 2, 'Power Supply DC - Unit 1', 'available', 'EL-15294', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(17, 2, 'Power Supply DC - Unit 2', 'available', 'EL-32915', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(18, 2, 'Power Supply DC - Unit 3', 'available', 'EL-85599', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(19, 2, 'Multimeter Fluke - Unit 1', 'available', 'EL-68874', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(20, 2, 'Multimeter Fluke - Unit 2', 'available', 'EL-37948', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(21, 2, 'Multimeter Fluke - Unit 3', 'available', 'EL-60051', 'Aset inventaris Teknik Elektro', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(22, 3, 'Mesin Bubut - Unit 1', 'available', 'MS-19550', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(23, 3, 'Mesin Bubut - Unit 2', 'available', 'MS-86247', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(24, 3, 'Mesin CNC Milling - Unit 1', 'available', 'MS-86990', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(25, 3, 'Mesin CNC Milling - Unit 2', 'available', 'MS-57305', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(26, 3, 'Mesin CNC Milling - Unit 3', 'available', 'MS-10486', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(27, 3, 'Bor Duduk - Unit 1', 'available', 'MS-29608', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(28, 3, 'Bor Duduk - Unit 2', 'available', 'MS-33448', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(29, 3, 'Gerinda Tangan - Unit 1', 'available', 'MS-50786', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(30, 3, 'Gerinda Tangan - Unit 2', 'available', 'MS-81317', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(31, 3, 'Gerinda Tangan - Unit 3', 'available', 'MS-17988', 'Aset inventaris Teknik Mesin', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(32, 4, 'Kursi Antropometri - Unit 1', 'available', 'TI-45438', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(33, 4, 'Kursi Antropometri - Unit 2', 'available', 'TI-33540', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(34, 4, 'Kursi Antropometri - Unit 3', 'available', 'TI-31977', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(35, 4, 'Treadmill Test - Unit 1', 'available', 'TI-74955', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(36, 4, 'Treadmill Test - Unit 2', 'available', 'TI-89448', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(37, 4, 'Treadmill Test - Unit 3', 'available', 'TI-26619', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(38, 4, 'Sound Level Meter - Unit 1', 'available', 'TI-46335', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(39, 4, 'Sound Level Meter - Unit 2', 'available', 'TI-70237', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(40, 4, 'Sound Level Meter - Unit 3', 'available', 'TI-74734', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(41, 4, 'Lux Meter - Unit 1', 'available', 'TI-14850', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(42, 4, 'Lux Meter - Unit 2', 'available', 'TI-26548', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(43, 4, 'Lux Meter - Unit 3', 'available', 'TI-79152', 'Aset inventaris Teknik Industri', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(44, 5, 'Reaktor Kaca - Unit 1', 'available', 'TK-18535', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(45, 5, 'Reaktor Kaca - Unit 2', 'available', 'TK-60436', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(46, 5, 'Reaktor Kaca - Unit 3', 'available', 'TK-69467', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(47, 5, 'Gelas Ukur Pyrex - Unit 1', 'available', 'TK-65647', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(48, 5, 'Gelas Ukur Pyrex - Unit 2', 'available', 'TK-85324', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(49, 5, 'Gelas Ukur Pyrex - Unit 3', 'available', 'TK-46844', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(50, 5, 'Neraca Analitik - Unit 1', 'available', 'TK-20146', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(51, 5, 'Neraca Analitik - Unit 2', 'available', 'TK-26305', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(52, 5, 'Neraca Analitik - Unit 3', 'available', 'TK-29910', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(53, 5, 'Sentrifuse - Unit 1', 'available', 'TK-14608', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(54, 5, 'Sentrifuse - Unit 2', 'available', 'TK-28404', 'Aset inventaris Teknik Kimia', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(55, 6, 'PC All-in-One - Unit 1', 'available', 'SI-81425', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(56, 6, 'PC All-in-One - Unit 2', 'available', 'SI-85675', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(57, 6, 'PC All-in-One - Unit 3', 'available', 'SI-36836', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(58, 6, 'Smart TV Presentation - Unit 1', 'available', 'SI-34556', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(59, 6, 'Smart TV Presentation - Unit 2', 'available', 'SI-84477', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(60, 6, 'Smart TV Presentation - Unit 3', 'available', 'SI-34292', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(61, 6, 'Tablet Android - Unit 1', 'available', 'SI-24334', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(62, 6, 'Tablet Android - Unit 2', 'available', 'SI-84074', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(63, 6, 'Fingerprint Scanner - Unit 1', 'available', 'SI-37712', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(64, 6, 'Fingerprint Scanner - Unit 2', 'available', 'SI-65585', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(65, 6, 'Fingerprint Scanner - Unit 3', 'available', 'SI-82347', 'Aset inventaris Sistem Informasi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(66, 7, 'Mesin Uji Tekan Beton - Unit 1', 'available', 'SI-25472', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(67, 7, 'Mesin Uji Tekan Beton - Unit 2', 'available', 'SI-74109', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(68, 7, 'Mesin Uji Tekan Beton - Unit 3', 'available', 'SI-10625', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(69, 7, 'Theodolite Digital - Unit 1', 'available', 'SI-93771', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(70, 7, 'Theodolite Digital - Unit 2', 'available', 'SI-54488', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(71, 7, 'Waterpass - Unit 1', 'available', 'SI-74436', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(72, 7, 'Waterpass - Unit 2', 'available', 'SI-66401', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(73, 7, 'Hammer Test - Unit 1', 'available', 'SI-34886', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(74, 7, 'Hammer Test - Unit 2', 'available', 'SI-88148', 'Aset inventaris Teknik Sipil', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(75, 8, 'Drone Pemetaan DJI - Unit 1', 'available', 'GD-28139', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(76, 8, 'Drone Pemetaan DJI - Unit 2', 'available', 'GD-43605', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(77, 8, 'Drone Pemetaan DJI - Unit 3', 'available', 'GD-31777', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(78, 8, 'Total Station - Unit 1', 'available', 'GD-51242', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(79, 8, 'Total Station - Unit 2', 'available', 'GD-89034', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(80, 8, 'Total Station - Unit 3', 'available', 'GD-52417', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(81, 8, 'GPS Geodetik - Unit 1', 'available', 'GD-57776', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(82, 8, 'GPS Geodetik - Unit 2', 'available', 'GD-58955', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(83, 8, 'GPS Geodetik - Unit 3', 'available', 'GD-75491', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(84, 8, 'Kompas Geologi - Unit 1', 'available', 'GD-15255', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(85, 8, 'Kompas Geologi - Unit 2', 'available', 'GD-81837', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(86, 8, 'Kompas Geologi - Unit 3', 'available', 'GD-12307', 'Aset inventaris Teknik Geodesi', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(87, 9, 'Plotter A0 HP DesignJet - Unit 1', 'available', 'PWK-64581', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(88, 9, 'Plotter A0 HP DesignJet - Unit 2', 'available', 'PWK-39284', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(89, 9, 'Plotter A0 HP DesignJet - Unit 3', 'available', 'PWK-18844', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(90, 9, 'Workstation GIS - Unit 1', 'available', 'PWK-89930', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(91, 9, 'Workstation GIS - Unit 2', 'available', 'PWK-73538', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(92, 9, 'Peta Digital - Unit 1', 'available', 'PWK-25901', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(93, 9, 'Peta Digital - Unit 2', 'available', 'PWK-85229', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(94, 9, 'Peta Digital - Unit 3', 'available', 'PWK-70189', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(95, 9, 'Meja Diskusi - Unit 1', 'available', 'PWK-79016', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(96, 9, 'Meja Diskusi - Unit 2', 'available', 'PWK-28786', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(97, 9, 'Meja Diskusi - Unit 3', 'available', 'PWK-85272', 'Aset inventaris Perencanaan Wilayah dan Kota', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(98, 10, 'pH Meter Digital - Unit 1', 'available', 'TL-19949', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(99, 10, 'pH Meter Digital - Unit 2', 'available', 'TL-51905', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(100, 10, 'pH Meter Digital - Unit 3', 'available', 'TL-97337', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(101, 10, 'DO Meter - Unit 1', 'available', 'TL-52981', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(102, 10, 'DO Meter - Unit 2', 'available', 'TL-84920', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(103, 10, 'DO Meter - Unit 3', 'available', 'TL-32534', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(104, 10, 'Tabung Reaksi - Unit 1', 'available', 'TL-86153', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(105, 10, 'Tabung Reaksi - Unit 2', 'available', 'TL-25974', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(106, 10, 'Tabung Reaksi - Unit 3', 'available', 'TL-32917', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(107, 10, 'Mikroskop Binokuler - Unit 1', 'available', 'TL-81228', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(108, 10, 'Mikroskop Binokuler - Unit 2', 'available', 'TL-62955', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(109, 10, 'Mikroskop Binokuler - Unit 3', 'available', 'TL-28309', 'Aset inventaris Teknik Lingkungan', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(110, 11, 'Kamera Sony Alpha - Unit 1', 'available', 'DKV-77013', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(111, 11, 'Kamera Sony Alpha - Unit 2', 'available', 'DKV-89934', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(112, 11, 'Lighting Studio Set - Unit 1', 'available', 'DKV-68505', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(113, 11, 'Lighting Studio Set - Unit 2', 'available', 'DKV-51801', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(114, 11, 'Lighting Studio Set - Unit 3', 'available', 'DKV-89657', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(115, 11, 'Green Screen - Unit 1', 'available', 'DKV-98803', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(116, 11, 'Green Screen - Unit 2', 'available', 'DKV-73238', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(117, 11, 'Green Screen - Unit 3', 'available', 'DKV-22829', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(118, 11, 'MacBook Pro M2 - Unit 1', 'available', 'DKV-67322', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(119, 11, 'MacBook Pro M2 - Unit 2', 'available', 'DKV-72769', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(120, 11, 'MacBook Pro M2 - Unit 3', 'available', 'DKV-81858', 'Aset inventaris Desain Komunikasi Visual', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(121, 12, '3D Printer BambuLab - Unit 1', 'available', 'DP-95674', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(122, 12, '3D Printer BambuLab - Unit 2', 'available', 'DP-23901', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(123, 12, 'Scroll Saw - Unit 1', 'available', 'DP-85306', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(124, 12, 'Scroll Saw - Unit 2', 'available', 'DP-97785', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(125, 12, 'Scroll Saw - Unit 3', 'available', 'DP-81363', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(126, 12, 'Airbrush Kit - Unit 1', 'available', 'DP-92671', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(127, 12, 'Airbrush Kit - Unit 2', 'available', 'DP-74088', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(128, 12, 'Heat Gun - Unit 1', 'available', 'DP-66144', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(129, 12, 'Heat Gun - Unit 2', 'available', 'DP-17486', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(130, 12, 'Heat Gun - Unit 3', 'available', 'DP-79623', 'Aset inventaris Desain Produk', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(131, 13, 'Meja Gambar Arsitek - Unit 1', 'available', 'AR-59053', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(132, 13, 'Meja Gambar Arsitek - Unit 2', 'available', 'AR-47390', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(133, 13, 'Maket Cutter - Unit 1', 'available', 'AR-74882', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(134, 13, 'Maket Cutter - Unit 2', 'available', 'AR-75858', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(135, 13, 'Maket Cutter - Unit 3', 'available', 'AR-84605', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(136, 13, 'Laser Distance Meter - Unit 1', 'available', 'AR-94699', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(137, 13, 'Laser Distance Meter - Unit 2', 'available', 'AR-67617', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(138, 13, 'Lampu Meja LED - Unit 1', 'available', 'AR-17592', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(139, 13, 'Lampu Meja LED - Unit 2', 'available', 'AR-88337', 'Aset inventaris Arsitektur', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(140, 14, 'Sampel HPL - Unit 1', 'available', 'DI-71959', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(141, 14, 'Sampel HPL - Unit 2', 'available', 'DI-44389', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(142, 14, 'Sampel HPL - Unit 3', 'available', 'DI-50581', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(143, 14, 'Mesin Jahit Industri - Unit 1', 'available', 'DI-61748', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(144, 14, 'Mesin Jahit Industri - Unit 2', 'available', 'DI-31312', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(145, 14, 'Mesin Jahit Industri - Unit 3', 'available', 'DI-20261', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 25000.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(146, 14, 'Laser Cutting Mini - Unit 1', 'available', 'DI-45854', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(147, 14, 'Laser Cutting Mini - Unit 2', 'available', 'DI-94270', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(148, 14, 'Laser Cutting Mini - Unit 3', 'available', 'DI-80717', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(149, 14, 'Pantone Color Guide - Unit 1', 'available', 'DI-76542', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(150, 14, 'Pantone Color Guide - Unit 2', 'available', 'DI-89910', 'Aset inventaris Desain Interior', 'assets/dummy.jpg', 1, 'good', 0.00, '2025-12-21 02:49:31', '2025-12-21 02:49:31');

-- Dumping structure for table db_itenas_resource_d1.audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.audit_logs: ~6 rows (approximately)
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
	(1, 2, 'POST', 'User mengakses URL: http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-21 02:50:01', '2025-12-21 02:50:01'),
	(2, 2, 'POST', 'User mengakses URL: http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-21 02:57:01', '2025-12-21 02:57:01'),
	(3, 2, 'POST', 'User mengakses URL: http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-23 05:30:35', '2025-12-23 05:30:35'),
	(4, 2, 'POST', 'User mengakses URL: http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-23 06:16:23', '2025-12-23 06:16:23'),
	(5, 2, 'POST', 'User mengakses URL: http://127.0.0.1:8000/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-23 06:22:42', '2025-12-23 06:22:42'),
	(6, 2, 'PUT', 'User mengakses URL: http://127.0.0.1:8000/password', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-23 07:24:21', '2025-12-23 07:24:21');

-- Dumping structure for table db_itenas_resource_d1.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.cache: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.cache_locks: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.categories: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.jobs: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.job_batches: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.labs
CREATE TABLE IF NOT EXISTS `labs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `building_name` varchar(255) NOT NULL,
  `room_number` varchar(255) DEFAULT NULL,
  `capacity` int(11) NOT NULL DEFAULT 30,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `labs_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `labs_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.labs: ~14 rows (approximately)
INSERT INTO `labs` (`id`, `prodi_id`, `name`, `building_name`, `room_number`, `capacity`, `latitude`, `longitude`, `description`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Lab Rekayasa Perangkat Lunak', 'Gedung 14 Lt.3', '103', 20, -6.88752000, 107.63594000, 'Fasilitas praktikum untuk mahasiswa Informatika', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(2, 2, 'Lab Dasar Elektronika', 'Gedung 14 Lt.2', '408', 35, -6.88064000, 107.63103000, 'Fasilitas praktikum untuk mahasiswa Teknik Elektro', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(3, 3, 'Lab Produksi & CNC', 'Gedung 10', '104', 35, -6.88273000, 107.63917000, 'Fasilitas praktikum untuk mahasiswa Teknik Mesin', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(4, 4, 'Lab Ergonomi & APK', 'Gedung 10 Lt.3', '204', 25, -6.88973000, 107.63789000, 'Fasilitas praktikum untuk mahasiswa Teknik Industri', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(5, 5, 'Lab Operasi Teknik Kimia', 'Gedung 19', '107', 39, -6.88838000, 107.63777000, 'Fasilitas praktikum untuk mahasiswa Teknik Kimia', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(6, 6, 'Lab Enterprise System', 'Gedung 14 Lt.3', '305', 34, -6.88633000, 107.63882000, 'Fasilitas praktikum untuk mahasiswa Sistem Informasi', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(7, 7, 'Lab Uji Bahan & Beton', 'Gedung 12', '102', 36, -6.88910000, 107.63893000, 'Fasilitas praktikum untuk mahasiswa Teknik Sipil', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(8, 8, 'Lab Fotogrametri', 'Gedung 12', '107', 28, -6.88861000, 107.63263000, 'Fasilitas praktikum untuk mahasiswa Teknik Geodesi', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(9, 9, 'Studio Perencanaan Wilayah (GIS)', 'Gedung 18', '201', 29, -6.88483000, 107.63936000, 'Fasilitas praktikum untuk mahasiswa Perencanaan Wilayah dan Kota', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(10, 10, 'Lab Kualitas Air', 'Gedung 18', '207', 32, -6.88280000, 107.63218000, 'Fasilitas praktikum untuk mahasiswa Teknik Lingkungan', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(11, 11, 'Studio Fotografi & Multimedia', 'Gedung 20', '209', 31, -6.88746000, 107.63590000, 'Fasilitas praktikum untuk mahasiswa Desain Komunikasi Visual', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(12, 12, 'Workshop Desain Produk', 'Gedung 20', '401', 20, -6.88071000, 107.63260000, 'Fasilitas praktikum untuk mahasiswa Desain Produk', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(13, 13, 'Studio Perancangan Arsitektur', 'Gedung 5', '301', 30, -6.88972000, 107.63927000, 'Fasilitas praktikum untuk mahasiswa Arsitektur', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(14, 14, 'Lab Material Interior', 'Gedung 5', '106', 37, -6.88753000, 107.63169000, 'Fasilitas praktikum untuk mahasiswa Desain Interior', '2025-12-21 02:49:31', '2025-12-21 02:49:31');

-- Dumping structure for table db_itenas_resource_d1.maintenances
CREATE TABLE IF NOT EXISTS `maintenances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenances_asset_id_foreign` (`asset_id`),
  CONSTRAINT `maintenances_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.maintenances: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.maintenance_logs
CREATE TABLE IF NOT EXISTS `maintenance_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint(20) unsigned NOT NULL,
  `reported_by` bigint(20) unsigned NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','in_progress','fixed','unrepairable') NOT NULL DEFAULT 'pending',
  `repair_cost` decimal(10,2) DEFAULT NULL,
  `completed_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_logs_asset_id_foreign` (`asset_id`),
  KEY `maintenance_logs_reported_by_foreign` (`reported_by`),
  CONSTRAINT `maintenance_logs_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `maintenance_logs_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.maintenance_logs: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.migrations: ~23 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_21_030927_create_permission_tables', 1),
	(5, '2025_12_21_031356_create_prodis_table', 1),
	(6, '2025_12_21_031357_create_labs_table', 1),
	(7, '2025_12_21_031358_create_assets_table', 1),
	(8, '2025_12_21_031359_create_reservations_table', 1),
	(9, '2025_12_21_031400_create_payments_table', 1),
	(10, '2025_12_21_031400_create_reservation_items_table', 1),
	(11, '2025_12_21_031401_create_maintenance_logs_table', 1),
	(12, '2025_12_21_031402_create_audit_logs_table', 1),
	(13, '2025_12_21_051205_add_columns_to_tables', 1),
	(14, '2025_12_21_052905_create_categories_table', 1),
	(15, '2025_12_21_052906_create_maintenances_table', 1),
	(16, '2025_12_21_054201_add_room_booking_to_reservations', 1),
	(17, '2025_12_21_064643_add_coordinates_to_labs_table', 1),
	(18, '2025_12_21_073245_add_google_id_to_users_table', 1),
	(19, '2025_12_21_084142_add_penalty_to_reservations_table', 1),
	(20, '2025_12_21_091023_add_penalty_columns_to_reservations_table', 1),
	(21, '2025_12_21_091548_add_maintenance_status_to_assets', 1),
	(22, '2025_12_23_143805_add_avatar_to_users_table', 2),
	(23, '2025_12_23_145154_add_penalty_columns_to_reservations_table', 2);

-- Dumping structure for table db_itenas_resource_d1.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.model_has_roles: ~5 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3),
	(3, 'App\\Models\\User', 4),
	(3, 'App\\Models\\User', 5);

-- Dumping structure for table db_itenas_resource_d1.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `transaction_status` varchar(255) NOT NULL DEFAULT 'pending',
  `snap_token` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_reservation_id_foreign` (`reservation_id`),
  CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.payments: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.permissions: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.prodis
CREATE TABLE IF NOT EXISTS `prodis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `kaprodi_name` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `head_of_prodi` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `location_office` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prodis_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.prodis: ~14 rows (approximately)
INSERT INTO `prodis` (`id`, `name`, `kaprodi_name`, `code`, `faculty`, `head_of_prodi`, `contact_email`, `location_office`, `created_at`, `updated_at`) VALUES
	(1, 'Informatika', NULL, 'IF-619', 'FTI', NULL, 'if@itenas.ac.id', 'Gedung 14 Lt.3', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(2, 'Teknik Elektro', NULL, 'EL-167', 'FTI', NULL, 'el@itenas.ac.id', 'Gedung 14 Lt.2', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(3, 'Teknik Mesin', NULL, 'MS-304', 'FTI', NULL, 'ms@itenas.ac.id', 'Gedung 10', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(4, 'Teknik Industri', NULL, 'TI-838', 'FTI', NULL, 'ti@itenas.ac.id', 'Gedung 10 Lt.3', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(5, 'Teknik Kimia', NULL, 'TK-698', 'FTI', NULL, 'tk@itenas.ac.id', 'Gedung 19', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(6, 'Sistem Informasi', NULL, 'SI-582', 'FTI', NULL, 'si@itenas.ac.id', 'Gedung 14 Lt.3', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(7, 'Teknik Sipil', NULL, 'SI-639', 'FTSP', NULL, 'si@itenas.ac.id', 'Gedung 12', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(8, 'Teknik Geodesi', NULL, 'GD-128', 'FTSP', NULL, 'gd@itenas.ac.id', 'Gedung 12', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(9, 'Perencanaan Wilayah dan Kota', NULL, 'PWK-918', 'FTSP', NULL, 'pwk@itenas.ac.id', 'Gedung 18', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(10, 'Teknik Lingkungan', NULL, 'TL-980', 'FTSP', NULL, 'tl@itenas.ac.id', 'Gedung 18', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(11, 'Desain Komunikasi Visual', NULL, 'DKV-205', 'FAD', NULL, 'dkv@itenas.ac.id', 'Gedung 20', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(12, 'Desain Produk', NULL, 'DP-820', 'FAD', NULL, 'dp@itenas.ac.id', 'Gedung 20', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(13, 'Arsitektur', NULL, 'AR-146', 'FAD', NULL, 'ar@itenas.ac.id', 'Gedung 5', '2025-12-21 02:49:31', '2025-12-21 02:49:31'),
	(14, 'Desain Interior', NULL, 'DI-368', 'FAD', NULL, 'di@itenas.ac.id', 'Gedung 5', '2025-12-21 02:49:31', '2025-12-21 02:49:31');

-- Dumping structure for table db_itenas_resource_d1.reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT 'asset',
  `user_id` bigint(20) unsigned NOT NULL,
  `lab_id` bigint(20) unsigned DEFAULT NULL,
  `transaction_code` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected','returned','overdue') NOT NULL DEFAULT 'pending',
  `penalty_amount` int(11) NOT NULL DEFAULT 0,
  `penalty_status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `rejection_note` text DEFAULT NULL,
  `proposal_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `penalty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('none','unpaid','paid') NOT NULL DEFAULT 'none',
  `payment_method` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reservations_transaction_code_unique` (`transaction_code`),
  KEY `reservations_user_id_foreign` (`user_id`),
  KEY `reservations_lab_id_foreign` (`lab_id`),
  CONSTRAINT `reservations_lab_id_foreign` FOREIGN KEY (`lab_id`) REFERENCES `labs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.reservations: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.reservation_items
CREATE TABLE IF NOT EXISTS `reservation_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `asset_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_items_reservation_id_foreign` (`reservation_id`),
  KEY `reservation_items_asset_id_foreign` (`asset_id`),
  CONSTRAINT `reservation_items_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_items_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.reservation_items: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.roles: ~4 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Superadmin', 'web', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(2, 'Laboran', 'web', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(3, 'Mahasiswa', 'web', '2025-12-21 02:49:30', '2025-12-21 02:49:30'),
	(4, 'Dosen', 'web', '2025-12-21 02:49:30', '2025-12-21 02:49:30');

-- Dumping structure for table db_itenas_resource_d1.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.role_has_permissions: ~0 rows (approximately)

-- Dumping structure for table db_itenas_resource_d1.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('cdtPP6vwzgUT3BRK9lngd5Jfbr1afANfmlDAFVlc', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOEhLeWVaNklCZXRacUFGVXEyNjBPMldKWlpXY2NtM1NGeUxYa3QxSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yb29tcyI7czo1OiJyb3V0ZSI7czoxMToicm9vbXMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1766502737);

-- Dumping structure for table db_itenas_resource_d1.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `prodi_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_prodi_id_index` (`prodi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_itenas_resource_d1.users: ~5 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `avatar`, `email_verified_at`, `password`, `prodi_id`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin Sarpras Itenas', 'admin@itenas.ac.id', NULL, NULL, NULL, '$2y$12$5qK3YV3SpBHB5MXEWvdpsuYXIp0fDBZAqrSHV6MIwPeQxovp9MOWe', NULL, NULL, '2025-12-21 02:49:32', '2025-12-21 02:49:32'),
	(2, 'Laboran Informatika', 'laboran_if@itenas.ac.id', NULL, NULL, NULL, '$2y$12$N4EWbSebfllV5F7ObKQKo.ORkUf7.ljpJkrE/UGTEC.QVCEjTkc6u', 1, NULL, '2025-12-21 02:49:32', '2025-12-21 02:49:32'),
	(3, 'Ari Ferdiana (Ketua D1)', 'ari.ferdiana@mhs.itenas.ac.id', NULL, NULL, NULL, '$2y$12$gOfQw3cByff4G.Oq9DMf6uG2UBVIOOFk6LBSJRRzWiq4l/x/crY/q', 1, NULL, '2025-12-21 02:49:33', '2025-12-21 02:49:33'),
	(4, 'Mahasiswa Teknik Kimia', 'mhs_lain@mhs.itenas.ac.id', NULL, NULL, NULL, '$2y$12$mX5EIildmVyNB0D1aDytfeT97NKWp2haijvUtSNfttFappra7rthi', 5, NULL, '2025-12-21 02:49:33', '2025-12-21 02:49:33'),
	(5, 'Ari Ferdiana', 'arif.ferdiana2005@gmail.com', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocKCafYToduMk_k51lGoJ4BVc_VpukkCwwhUvxXvsIaBOi14VI78=s96-c', NULL, '$2y$12$1bohLN77CMNVSCgxfV5zjeytLdzWfogsmDDPSnT61bPLtQYohJkf.', NULL, NULL, '2025-12-23 06:15:01', '2025-12-23 06:15:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
