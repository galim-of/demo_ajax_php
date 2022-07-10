CREATE DATABASE  IF NOT EXISTS `test` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `test`;
--
-- Table structure for table `couriers`
--

DROP TABLE IF EXISTS `couriers`;
CREATE TABLE `couriers` (
  `id_courier` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(70) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id_courier`),
  UNIQUE KEY `id_courier_UNIQUE` (`id_courier`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_0900_ai_ci;

--
-- Dumping data for table `couriers`
--

LOCK TABLES `couriers` WRITE;
INSERT INTO `couriers` VALUES (1,'Петр','Петров','Петрович'),(2,'Игорь','Игорев','Игоревич'),(3,'Александр','Александров','Александрович'),(4,'Денис','Денисов','Денисович'),(5,'Федор','Федоров','Федорович'),(6,'Алексей','Алексеев','Алексеевич'),(7,'Роман','Романов','Романович'),(8,'Филат','Филатов','Филатович'),(9,'Юрий','Юров','Юрьевич'),(10,'Евгений','Евгенев','Евгеньевич');
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `id_region` int(11) NOT NULL AUTO_INCREMENT,
  `region` varchar(100) NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id_region`),
  UNIQUE KEY `id_region_UNIQUE` (`id_region`),
  UNIQUE KEY `region_UNIQUE` (`region`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_0900_ai_ci;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
INSERT INTO `regions` VALUES (2,'Санкт-Петербург','24:00:00'),(3,'Уфа','72:00:00'),(4,'Нижний Новгород','24:00:00'),(5,'Владимир','24:00:00'),(6,'Кострома','24:00:00'),(7,'Екатеринбург','96:00:00'),(8,'Ковров','24:00:00'),(9,'Воронеж','24:00:00'),(10,'Самара','48:00:00'),(11,'Астрахань','72:00:00');
UNLOCK TABLES;


--
-- Table structure for table `races`
--

DROP TABLE IF EXISTS `races`;
CREATE TABLE `races` (
  `id_race` int(11) NOT NULL AUTO_INCREMENT,
  `id_courier` int(11) NOT NULL,
  `id_region` int(11) NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  PRIMARY KEY (`id_race`),
  UNIQUE KEY `id_race_UNIQUE` (`id_race`),
  KEY `fk_id_courier_idx` (`id_courier`),
  KEY `fk_id_region_idx` (`id_region`),
  CONSTRAINT `fk_id_courier` FOREIGN KEY (`id_courier`) REFERENCES `couriers` (`id_courier`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_region` FOREIGN KEY (`id_region`) REFERENCES `regions` (`id_region`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_0900_ai_ci;

--
-- Dumping data for table `races`
--

DROP TABLE IF EXISTS `races`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `races` (
  `id_race` int NOT NULL AUTO_INCREMENT,
  `id_courier` int NOT NULL,
  `id_region` int NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  PRIMARY KEY (`id_race`),
  UNIQUE KEY `id_race_UNIQUE` (`id_race`),
  KEY `fk_id_courier_idx` (`id_courier`),
  KEY `fk_id_region_idx` (`id_region`),
  CONSTRAINT `fk_id_courier` FOREIGN KEY (`id_courier`) REFERENCES `couriers` (`id_courier`),
  CONSTRAINT `fk_id_region` FOREIGN KEY (`id_region`) REFERENCES `regions` (`id_region`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `races`
--

LOCK TABLES `races` WRITE;
/*!40000 ALTER TABLE `races` DISABLE KEYS */;
INSERT INTO `races` VALUES (50,1,2,'2022-07-10 00:00:00','2022-07-11 00:00:00'),(51,1,3,'2022-07-12 00:00:00','2022-07-15 00:00:00'),(52,1,7,'2022-07-16 00:00:00','2022-07-20 00:00:00'),(53,1,2,'2022-07-11 00:00:00','2022-07-12 00:00:00'),(54,9,7,'2022-07-05 00:00:00','2022-07-09 00:00:00'),(55,5,6,'2022-07-05 00:00:00','2022-07-06 00:00:00'),(56,4,6,'2022-07-05 00:00:00','2022-07-06 00:00:00'),(57,1,3,'2022-07-05 00:00:00','2022-07-08 00:00:00'),(58,6,10,'2022-07-13 00:00:00','2022-07-15 00:00:00'),(59,6,8,'2022-07-17 00:00:00','2022-07-18 00:00:00');
/*!40000 ALTER TABLE `races` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
