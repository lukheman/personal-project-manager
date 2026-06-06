/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: personal_project_manager
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `referred_by_client_id` bigint(20) unsigned DEFAULT NULL,
  `referral_credit_used` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_referred_by_client_id_foreign` (`referred_by_client_id`),
  CONSTRAINT `clients_referred_by_client_id_foreign` FOREIGN KEY (`referred_by_client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `clients` VALUES
(1,'Yani Nurdiyanti','(+62) 950 5353 1725',NULL,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:03','2026-01-16 03:40:03'),
(2,'Ayu Restu Susanti','0548 0738 769',NULL,200000.00,'2026-01-16 03:18:57','2026-01-16 03:40:05','2026-01-16 03:40:05'),
(3,'Wawan Mahdi Marpaung S.Kom','0852 7283 078',1,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:07','2026-01-16 03:40:07'),
(4,'Yusuf Budiyanto S.E.','(+62) 996 7286 602',1,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:09','2026-01-16 03:40:09'),
(5,'Danuja Maryanto Hutasoit','0559 7684 302',1,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:10','2026-01-16 03:40:10'),
(6,'Dimas Damanik','(+62) 839 8659 758',2,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:12','2026-01-16 03:40:12'),
(7,'Farhunnisa Amalia Riyanti','0853 0044 0188',2,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:14','2026-01-16 03:40:14'),
(8,'Hadi Saptono','0826 235 673',3,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:16','2026-01-16 03:40:16'),
(9,'Hesti Namaga','0711 7841 1819',NULL,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:18','2026-01-16 03:40:18'),
(10,'Chandra Hakim S.Kom','0359 0440 2842',NULL,0.00,'2026-01-16 03:18:57','2026-01-16 03:40:21','2026-01-16 03:40:21');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_01_11_000001_create_price_categories_table',1),
(5,'2026_01_11_000002_create_clients_table',1),
(6,'2026_01_11_000003_create_projects_table',1),
(7,'2026_01_11_000004_create_project_features_table',1),
(8,'2026_01_11_000005_create_payments_table',1),
(9,'2026_01_11_122107_add_public_token_to_projects_table',1),
(10,'2026_01_14_000001_add_soft_deletes_and_improvements',1),
(11,'2026_01_14_000002_create_notifications_table',1),
(12,'2026_01_14_000003_create_project_attachments_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `project_id` bigint(20) unsigned DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_project_id_foreign` (`project_id`),
  KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  CONSTRAINT `notifications_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `payment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_project_id_foreign` (`project_id`),
  CONSTRAINT `payments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `payments` VALUES
(1,1,57964.00,'ewallet','DP','2026-01-13','2026-01-16 03:18:57','2026-01-16 03:18:57',NULL),
(2,1,157036.00,'transfer','Pelunasan','2026-01-12','2026-01-16 03:18:57','2026-01-16 03:18:57',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `price_categories`
--

DROP TABLE IF EXISTS `price_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `price_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `base_price` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price_categories`
--

LOCK TABLES `price_categories` WRITE;
/*!40000 ALTER TABLE `price_categories` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `price_categories` VALUES
(1,'CRUD Sederhana',30000.00,'Fitur CRUD standar untuk master data','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(2,'CRUD Kompleks',50000.00,'CRUD dengan relasi dan validasi kompleks','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(3,'Implementasi Algoritma',350000.00,'Algoritma SAW, AHP, TOPSIS, dll','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(4,'Integrasi API',150000.00,'Integrasi dengan API eksternal','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(5,'Export PDF',75000.00,'Fitur export laporan ke PDF','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(6,'Export Excel',50000.00,'Fitur export data ke Excel','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(7,'Dashboard & Statistik',100000.00,'Dashboard dengan chart dan statistik','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(8,'Authentikasi',40000.00,'Login, register, reset password','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(9,'Upload File',35000.00,'Fitur upload dan manajemen file','2026-01-16 03:18:57','2026-01-16 03:18:57'),
(10,'Notifikasi Email',60000.00,'Pengiriman email notifikasi','2026-01-16 03:18:57','2026-01-16 03:18:57');
/*!40000 ALTER TABLE `price_categories` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `project_attachments`
--

DROP TABLE IF EXISTS `project_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_attachments_project_id_index` (`project_id`),
  CONSTRAINT `project_attachments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_attachments`
--

LOCK TABLES `project_attachments` WRITE;
/*!40000 ALTER TABLE `project_attachments` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `project_attachments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `project_features`
--

DROP TABLE IF EXISTS `project_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `price_category_id` bigint(20) unsigned NOT NULL,
  `description` text DEFAULT NULL,
  `custom_price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_features_project_id_foreign` (`project_id`),
  KEY `project_features_price_category_id_foreign` (`price_category_id`),
  CONSTRAINT `project_features_price_category_id_foreign` FOREIGN KEY (`price_category_id`) REFERENCES `price_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_features_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_features`
--

LOCK TABLES `project_features` WRITE;
/*!40000 ALTER TABLE `project_features` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `project_features` VALUES
(1,1,2,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(2,1,5,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(3,1,6,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(4,1,8,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(5,2,2,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(6,2,3,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(7,2,4,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(8,2,9,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(9,2,10,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(10,3,2,NULL,76985.00,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(11,3,7,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(12,4,1,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(13,4,2,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(14,4,4,NULL,79712.00,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(15,4,5,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(16,4,6,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(17,5,5,NULL,61358.00,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(18,5,10,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(19,6,2,NULL,65334.00,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(20,6,5,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(21,6,6,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(22,6,10,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(23,7,2,NULL,55051.00,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(24,7,4,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(25,7,5,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(26,8,1,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57'),
(27,8,4,NULL,NULL,'2026-01-16 03:18:57','2026-01-16 03:18:57');
/*!40000 ALTER TABLE `project_features` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `status` enum('pending','in_progress','completed','paid') NOT NULL DEFAULT 'pending',
  `final_price` decimal(12,2) DEFAULT NULL,
  `discount_applied` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `public_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_public_token_unique` (`public_token`),
  KEY `projects_client_id_foreign` (`client_id`),
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `projects` VALUES
(1,1,'Sistem Informasi Penjualan','paid',215000.00,0.00,NULL,NULL,'0gEaN05v6VpmY7SQY5TwsKKoUiJixP9r','2026-01-16 03:18:57','2026-01-16 03:43:55','2026-01-16 03:43:55'),
(2,2,'Aplikasi Inventory','completed',445000.00,200000.00,NULL,NULL,'sgLs1Ej4rv5uTM1DLQy16efuyEQTtggc','2026-01-16 03:18:57','2026-01-16 03:43:57','2026-01-16 03:43:57'),
(3,3,'SPK Pemilihan Karyawan Terbaik','in_progress',NULL,0.00,'Deadline bulan depan',NULL,'wEERoAUppA6wt68PfOD9vvFCQBVAhYqf','2026-01-16 03:18:57','2026-01-16 03:44:03','2026-01-16 03:44:03'),
(4,4,'Website Company Profile','in_progress',NULL,0.00,'Deadline bulan depan',NULL,'HLwTgvcuLIZxeg0MD6MUJG2PPaOUxszk','2026-01-16 03:18:57','2026-01-16 03:44:10','2026-01-16 03:44:10'),
(5,5,'E-Commerce Toko Online','pending',NULL,0.00,'Deadline bulan depan',NULL,'Tk67cEuVY0vkG4EeispGgZRXvbUmdiIL','2026-01-16 03:18:57','2026-01-16 03:44:05','2026-01-16 03:44:05'),
(6,6,'Sistem Pakar Diagnosa Penyakit','in_progress',NULL,0.00,'Deadline bulan depan',NULL,'WAHDLjlQC6Ynsv5zCTJNzDGRL5XS0fxo','2026-01-16 03:18:57','2026-01-16 03:44:07','2026-01-16 03:44:07'),
(7,7,'Aplikasi Kasir POS','in_progress',NULL,0.00,NULL,NULL,'oYZUgmR1vznNo4lsyQs3rYLeValLLydp','2026-01-16 03:18:57','2026-01-16 03:44:08','2026-01-16 03:44:08'),
(8,8,'Sistem Manajemen Perpustakaan','completed',180000.00,0.00,NULL,NULL,'DSczbYWK3uOh5pUhXM8buquZpw1Y8ZMa','2026-01-16 03:18:57','2026-01-16 03:44:12','2026-01-16 03:44:12');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions` VALUES
('giTQtv5uPqPr4L4Sa3MxOdGBTL9882r31b5pTVR8',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMlpFOGN0MDBOT0Roc1JsZkxEbmxMQWZzNVEwUDdvVE5LTkZmSGQwOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9wcm9qZWN0cyI7czo1OiJyb3V0ZSI7czoxNDoiYWRtaW4ucHJvamVjdHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1768563882),
('sbQqOxGf3oYlDTGjvlwSP0WbRcodo76jgqhZpJox',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/60.5 Safari/605.1.15','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZTVlRkJtR3RMZ3FQVjQydXNTTXZxREI2NXZZOEs1YU9jRzd2M1VCeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6NTAwMDAvYWRtaW4vcHJpY2UtY2F0ZWdvcmllcyI7czo1OiJyb3V0ZSI7czoyMjoiYWRtaW4ucHJpY2UtY2F0ZWdvcmllcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1768562388);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Admin','admin@gmail.com','2026-01-16 03:18:57','$2y$12$Wc/pe7OFNdXfIG0n.vJMWO.VD1wx2kc8GsfpE0zbZzQf6i1POFnEm','5FSZhztFOw','2026-01-16 03:18:57','2026-01-16 03:18:57');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-01-16 20:15:25
