-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: website_cua_hang_ban_sach_online
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '6228c277-d591-11f0-94a5-183d2d6076c7:1-1026';

--
-- Table structure for table `lich_su_kho`
--

DROP TABLE IF EXISTS `lich_su_kho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lich_su_kho` (
  `id_lich_su` int NOT NULL AUTO_INCREMENT,
  `id_sach` int NOT NULL,
  `id_nguoi_thuc_hien` int DEFAULT NULL,
  `loai_giao_dich` enum('nhap_kho','xuat_kho','dieu_chinh') NOT NULL,
  `so_luong` int NOT NULL,
  `ton_truoc` int NOT NULL,
  `ton_sau` int NOT NULL,
  `thoi_gian` datetime DEFAULT CURRENT_TIMESTAMP,
  `ly_do` varchar(255) DEFAULT NULL,
  `ma_tham_chieu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_lich_su`),
  KEY `id_sach` (`id_sach`),
  KEY `id_nguoi_thuc_hien` (`id_nguoi_thuc_hien`),
  CONSTRAINT `lich_su_kho_ibfk_1` FOREIGN KEY (`id_sach`) REFERENCES `sach` (`id_sach`),
  CONSTRAINT `lich_su_kho_ibfk_2` FOREIGN KEY (`id_nguoi_thuc_hien`) REFERENCES `khach_hang` (`id_khach_hang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lich_su_kho`
--

LOCK TABLES `lich_su_kho` WRITE;
/*!40000 ALTER TABLE `lich_su_kho` DISABLE KEYS */;
/*!40000 ALTER TABLE `lich_su_kho` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-20 18:19:09
