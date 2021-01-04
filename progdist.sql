-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: localhost    Database: progdist
-- ------------------------------------------------------
-- Server version	5.7.31-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `areas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (1,'Dirección de Operaciones','OPE','2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,'Dirección Financiera','FIN','2021-01-04 20:06:19','2021-01-04 20:06:19'),(3,'Dirección Estratégica','DIRE','2021-01-04 20:06:19','2021-01-04 20:06:19'),(4,'Dirección de Personas','DP','2021-01-04 20:06:19','2021-01-04 20:06:19'),(5,'Dirección Comercial','COM','2021-01-04 20:06:19','2021-01-04 20:06:19'),(6,'Análisis de Decisiones','ADD','2021-01-04 20:06:19','2021-01-04 20:06:19'),(7,'Sistemas de Dirección y Control','SDC','2021-01-04 20:06:19','2021-01-04 20:06:19'),(8,'Análisis de Situación de Negocios','ASN','2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_support_persons`
--

DROP TABLE IF EXISTS `booking_support_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_support_persons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `support_person_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_support_persons_booking_id_foreign` (`booking_id`),
  KEY `booking_support_persons_support_person_id_foreign` (`support_person_id`),
  CONSTRAINT `booking_support_persons_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `booking_support_persons_support_person_id_foreign` FOREIGN KEY (`support_person_id`) REFERENCES `support_persons` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_support_persons`
--

LOCK TABLES `booking_support_persons` WRITE;
/*!40000 ALTER TABLE `booking_support_persons` DISABLE KEYS */;
INSERT INTO `booking_support_persons` VALUES (1,1,1,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,1,4,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(3,1,30,'2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `booking_support_persons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned DEFAULT NULL,
  `instructor_id` bigint(20) unsigned DEFAULT NULL,
  `virtual_meeting_link_id` bigint(20) unsigned DEFAULT NULL,
  `physical_room_id` bigint(20) unsigned DEFAULT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `requested_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_id` bigint(20) unsigned DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_program_id_foreign` (`program_id`),
  KEY `bookings_instructor_id_foreign` (`instructor_id`),
  KEY `bookings_virtual_meeting_link_id_foreign` (`virtual_meeting_link_id`),
  KEY `bookings_physical_room_id_foreign` (`physical_room_id`),
  KEY `bookings_area_id_foreign` (`area_id`),
  CONSTRAINT `bookings_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  CONSTRAINT `bookings_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`),
  CONSTRAINT `bookings_physical_room_id_foreign` FOREIGN KEY (`physical_room_id`) REFERENCES `physical_rooms` (`id`),
  CONSTRAINT `bookings_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  CONSTRAINT `bookings_virtual_meeting_link_id_foreign` FOREIGN KEY (`virtual_meeting_link_id`) REFERENCES `virtual_meeting_links` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,1,1,1,1,NULL,'2021-01-04 20:06:19','2021-01-04 20:06:19',NULL,3,NULL,'7.40','7.40'),(2,2,5,1,4,NULL,'2021-01-04 20:06:19','2021-01-04 20:06:19',NULL,4,NULL,'8.40','10.40'),(3,5,1,1,2,NULL,'2021-01-04 20:06:19','2021-01-04 20:06:19',NULL,5,NULL,'10.40','13.40');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campuses`
--

DROP TABLE IF EXISTS `campuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campuses`
--

LOCK TABLES `campuses` WRITE;
/*!40000 ALTER TABLE `campuses` DISABLE KEYS */;
INSERT INTO `campuses` VALUES (1,'Sede Guayaquil','GYE','Guayaquil','Km. 13 vía a la costa','2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,'Sede Quito','UIO','Quito','Nicolás López 518 y Marco Aguirre, sector Pinar Bajo.','2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `campuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor_areas`
--

DROP TABLE IF EXISTS `instructor_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructor_areas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `area_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `instructor_areas_instructor_id_foreign` (`instructor_id`),
  KEY `instructor_areas_area_id_foreign` (`area_id`),
  CONSTRAINT `instructor_areas_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  CONSTRAINT `instructor_areas_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor_areas`
--

LOCK TABLES `instructor_areas` WRITE;
/*!40000 ALTER TABLE `instructor_areas` DISABLE KEYS */;
INSERT INTO `instructor_areas` VALUES (1,1,1,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,1,8,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(3,2,2,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(4,3,3,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(5,4,4,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(6,5,2,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(7,6,5,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(8,7,6,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(9,8,7,'2021-01-04 20:06:19','2021-01-04 20:06:19'),(10,9,5,'2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `instructor_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructors`
--

DROP TABLE IF EXISTS `instructors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `instructors_mnemonic_unique` (`mnemonic`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructors`
--

LOCK TABLES `instructors` WRITE;
/*!40000 ALTER TABLE `instructors` DISABLE KEYS */;
INSERT INTO `instructors` VALUES (1,'Daniel Susaeta','DS','2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,'Jorge Monckeberg','JMB','2021-01-04 20:06:19','2021-01-04 20:06:19'),(3,'Josemaría Vázquez','JMV','2021-01-04 20:06:19','2021-01-04 20:06:19'),(4,'Roberto Estrada','RE','2021-01-04 20:06:19','2021-01-04 20:06:19'),(5,'Abel Defina','AD','2021-01-04 20:06:19','2021-01-04 20:06:19'),(6,'Raúl Moncayo','RM','2021-01-04 20:06:19','2021-01-04 20:06:19'),(7,'Antonio Villasís','AV','2021-01-04 20:06:19','2021-01-04 20:06:19'),(8,'Patricio Vergara','PV','2021-01-04 20:06:19','2021-01-04 20:06:19'),(9,'José Aulestia','JA','2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `instructors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2020_11_24_194707_create_instructors_table',1),(5,'2020_12_08_203159_create_areas_table',1),(6,'2020_12_08_215922_create_instructor_areas_table',1),(7,'2020_12_10_041114_create_programs_table',1),(8,'2020_12_14_202232_create_virtual_rooms',1),(9,'2020_12_14_203354_create_campuses',1),(10,'2020_12_14_222845_create_physical_rooms_table',1),(11,'2020_12_16_031655_create_virtual_meeting_links',1),(12,'2020_12_16_035637_create_support_persons',1),(13,'2020_12_16_035711_create_bookings',1),(14,'2020_12_16_040937_create_booking_support_persons',1),(15,'2020_12_16_191920_add_deault_virtual_meeiting_link_field',1),(16,'2020_12_16_201227_create_support_person_roles_table',1),(17,'2020_12_16_201603_add_support_person_role_id_field',1),(18,'2020_12_16_202343_drop_type_field',1),(19,'2020_12_16_203324_add_requested_by_field',1),(20,'2020_12_16_203605_add_area_id_field',1),(21,'2020_12_31_000114_add_link_field_to_virtual_meeting_links_table',1),(22,'2020_12_31_001241_drop_start_time_field_in_bookings_table',1),(23,'2020_12_31_001430_drop_end_time_field_in_bookings_table',1),(24,'2020_12_31_001612_add_booking_date_field_in_bookings_table',1),(25,'2020_12_31_001848_add_start_time_field_and_end_time_field_in_bookings_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physical_rooms`
--

DROP TABLE IF EXISTS `physical_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physical_rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campus_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `physical_rooms_campus_id_foreign` (`campus_id`),
  CONSTRAINT `physical_rooms_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physical_rooms`
--

LOCK TABLES `physical_rooms` WRITE;
/*!40000 ALTER TABLE `physical_rooms` DISABLE KEYS */;
INSERT INTO `physical_rooms` VALUES (1,1,'Aula 1 - Guayaquil','GYE-A1','34','2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,1,'Aula 2 - Guayaquil','GYE-A2','34','2021-01-04 20:06:19','2021-01-04 20:06:19'),(3,1,'Aula 3 - Guayaquil','GYE-A3','34','2021-01-04 20:06:19','2021-01-04 20:06:19'),(4,1,'Aula 4 - Guayaquil','GYE-A4','34','2021-01-04 20:06:19','2021-01-04 20:06:19'),(5,2,'Aula 1 - Quito','UIO-A1','34','2021-01-04 20:06:19','2021-01-04 20:06:19'),(6,2,'Aula 2 Quito','UIO-A2','34','2021-01-04 20:06:19','2021-01-04 20:06:19'),(7,2,'Aula 3 - Quito','UIO-A3','34','2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `physical_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `default_virtual_meeting_link_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programs_default_virtual_meeting_link_id_foreign` (`default_virtual_meeting_link_id`),
  CONSTRAINT `programs_default_virtual_meeting_link_id_foreign` FOREIGN KEY (`default_virtual_meeting_link_id`) REFERENCES `virtual_meeting_links` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
INSERT INTO `programs` VALUES (1,'Maestría en Dirección de Empresas 2019 Guayaquil Paralelo 1','MDE2019GYEP1',' MDE 2019 Guayaquil P1','2019-08-01','2021-11-24','2021-01-04 20:06:19','2021-01-04 20:06:19',NULL),(2,'Maestría en Dirección de Empresas 2019 Quito Paralelo 1','MDE2019UIOP1',' MDE 2019 Quito P1','2019-08-01','2021-11-24','2021-01-04 20:06:19','2021-01-04 20:06:19',NULL),(3,'Maestría en Dirección de Empresas 2019 Guayaquil Paralelo 2','MDE2019GYEP2',' MDE 2019 Guayaquil P2','2019-08-01','2021-11-24','2021-01-04 20:06:19','2021-01-04 20:06:19',NULL),(4,'Maestría en Dirección de Empresas 2019 Quito Paralelo 2','MDE2019UIOP2',' MDE 2019 Quito P2','2019-08-01','2021-11-24','2021-01-04 20:06:19','2021-01-04 20:06:19',NULL),(5,'Maestría en Dirección de Empresas 2021 Guayaquil Paralelo 1','MDE2021GYEP1',' MDE 2021 Guayaquil P1','2019-08-01','2021-11-24','2021-01-04 20:06:19','2021-01-04 20:06:19',NULL),(6,'Maestría en Dirección de Empresas 2021 Quito Paralelo 1','MDE2021UIOP1',' MDE 2021 Quito P1','2019-08-01','2021-11-24','2021-01-04 20:06:19','2021-01-04 20:06:19',NULL),(7,'Incompany FarmaEnlace','FarmaEnlace 2021',' Inco FarmaEnlace 2021','2019-08-01','2021-11-24','2021-01-04 20:06:19','2021-01-04 20:06:19',NULL);
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_person_roles`
--

DROP TABLE IF EXISTS `support_person_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_person_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `support_person_roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_person_roles`
--

LOCK TABLES `support_person_roles` WRITE;
/*!40000 ALTER TABLE `support_person_roles` DISABLE KEYS */;
INSERT INTO `support_person_roles` VALUES (1,'Coordinación Académica','2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,'Soporte Académico','2021-01-04 20:06:19','2021-01-04 20:06:19'),(3,'Soporte TI','2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `support_person_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_persons`
--

DROP TABLE IF EXISTS `support_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_persons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `support_person_role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `support_persons_support_person_role_id_foreign` (`support_person_role_id`),
  CONSTRAINT `support_persons_support_person_role_id_foreign` FOREIGN KEY (`support_person_role_id`) REFERENCES `support_person_roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_persons`
--

LOCK TABLES `support_persons` WRITE;
/*!40000 ALTER TABLE `support_persons` DISABLE KEYS */;
INSERT INTO `support_persons` VALUES (1,'Karina San Martín','KSM','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(2,'Karina San Martín','KSM','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(3,'Karina San Martín','KSM','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(4,'Martha Triana','MT','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(5,'Martha Triana','MT','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(6,'Martha Triana','MT','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(7,'Helen Cadena','HC','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(8,'Helen Cadena','HC','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(9,'Helen Cadena','HC','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(10,'María Fernanda Bustamante','MFB','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(11,'María Fernanda Bustamante','MFB','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(12,'María Fernanda Bustamante','MFB','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(13,'Soledad Crespo','SC','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(14,'Soledad Crespo','SC','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(15,'Soledad Crespo','SC','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(16,'Marco Salazar','MS','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(17,'Marco Salazar','MS','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(18,'Marco Salazar','MS','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(19,'Rafael Castillo','RC','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(20,'Rafael Castillo','RC','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(21,'Rafael Castillo','RC','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(22,'Stefany Acuña','SA','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(23,'Stefany Acuña','SA','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(24,'Stefany Acuña','SA','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(25,'Santiago Ullauri','SU','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(26,'Santiago Ullauri','SU','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(27,'Santiago Ullauri','SU','2021-01-04 20:06:19','2021-01-04 20:06:19',3),(28,'Xavier Dyer','XD','2021-01-04 20:06:19','2021-01-04 20:06:19',1),(29,'Xavier Dyer','XD','2021-01-04 20:06:19','2021-01-04 20:06:19',2),(30,'Xavier Dyer','XD','2021-01-04 20:06:19','2021-01-04 20:06:19',3);
/*!40000 ALTER TABLE `support_persons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `virtual_meeting_links`
--

DROP TABLE IF EXISTS `virtual_meeting_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtual_meeting_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `virtual_room_id` bigint(20) unsigned NOT NULL,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waiting_room` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `virtual_meeting_links_virtual_room_id_foreign` (`virtual_room_id`),
  CONSTRAINT `virtual_meeting_links_virtual_room_id_foreign` FOREIGN KEY (`virtual_room_id`) REFERENCES `virtual_rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtual_meeting_links`
--

LOCK TABLES `virtual_meeting_links` WRITE;
/*!40000 ALTER TABLE `virtual_meeting_links` DISABLE KEYS */;
INSERT INTO `virtual_meeting_links` VALUES (1,2,'Clases MDE 2019 GYE P1','MDE2021GYE',1,'2021-01-04 20:06:19','2021-01-04 20:06:19','https://zoom.us/j/407061210');
/*!40000 ALTER TABLE `virtual_meeting_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `virtual_rooms`
--

DROP TABLE IF EXISTS `virtual_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtual_rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zoom_account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtual_rooms`
--

LOCK TABLES `virtual_rooms` WRITE;
/*!40000 ALTER TABLE `virtual_rooms` DISABLE KEYS */;
INSERT INTO `virtual_rooms` VALUES (1,'Aula Virtual 1','AV1','aula.virtual1@ide.edu.ec','2021-01-04 20:06:19','2021-01-04 20:06:19'),(2,'Aula Virtual 2','AV2','aula.virtual2@ide.edu.ec','2021-01-04 20:06:19','2021-01-04 20:06:19'),(3,'Aula Virtual 3','AV3','aula.virtual3@ide.edu.ec','2021-01-04 20:06:19','2021-01-04 20:06:19'),(4,'Aula Virtual 4','AV4','aula.virtual4@ide.edu.ec','2021-01-04 20:06:19','2021-01-04 20:06:19'),(5,'Aula Virtual 5','AV5','aula.virtual5@ide.edu.ec','2021-01-04 20:06:19','2021-01-04 20:06:19'),(6,'Aula Virtual 6','AV6','aula.virtual6@ide.edu.ec','2021-01-04 20:06:19','2021-01-04 20:06:19'),(7,'Aula Virtual 7','AV7','aula.virtual7@ide.edu.ec','2021-01-04 20:06:19','2021-01-04 20:06:19');
/*!40000 ALTER TABLE `virtual_rooms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-01-04 20:18:01
