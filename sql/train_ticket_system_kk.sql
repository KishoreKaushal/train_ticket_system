-- MySQL dump 10.17  Distrib 10.3.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: train_ticket_system
-- ------------------------------------------------------
-- Server version	10.3.13-MariaDB-1:10.3.13+maria~stretch

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `neighbours`
--

DROP TABLE IF EXISTS `neighbours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `neighbours` (
  `st_a` varchar(5) NOT NULL,
  `st_b` varchar(5) NOT NULL,
  `distance` double unsigned NOT NULL,
  PRIMARY KEY (`st_a`,`st_b`),
  KEY `st_b` (`st_b`),
  CONSTRAINT `neighbours_ibfk_1` FOREIGN KEY (`st_a`) REFERENCES `station` (`station_code`),
  CONSTRAINT `neighbours_ibfk_2` FOREIGN KEY (`st_b`) REFERENCES `station` (`station_code`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`distance` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `neighbours`
--

LOCK TABLES `neighbours` WRITE;
/*!40000 ALTER TABLE `neighbours` DISABLE KEYS */;
INSERT INTO `neighbours` VALUES ('ADI','JL',540),('ADI','UDZ',633),('ALD','BPL',677),('ALD','BSB',124),('ALD','LKO',200),('BBS','KOAA',440),('BBS','RNC',555),('BBS','VSKP',442),('BNC','CBE',376),('BNC','ED',276),('BNC','HYB',636),('BPL','ALD',677),('BPL','JHS',290),('BPL','NGP',395),('BPL','UJN',183),('BSB','ALD',124),('BSB','LKO',283),('BSB','PNBE',228),('BZA','MAS',430),('BZA','VSKP',350),('BZA','WL',207),('CAPE','MDU',245),('CAPE','TVC',87),('CBE','BNC',376),('CBE','ED',82),('CBE','MDU',232),('CBE','PGT',55),('CSMT','JL',419),('CSMT','PUNE',169),('ED','BNC',276),('ED','CBE',82),('ED','MAS',395),('ED','TPJ',141),('HYB','BNC',636),('HYB','WL',151),('JHS','BPL',290),('JHS','LKO',293),('JHS','NZM',409),('JL','ADI',540),('JL','CSMT',419),('JL','NGP',416),('JP','KOTA',239),('JP','UDZ',430),('KOAA','BBS',440),('KOAA','PNBE',534),('KOTA','JP',239),('KOTA','NZM',308),('KOTA','UJN',280),('LKO','ALD',200),('LKO','BSB',283),('LKO','JHS',293),('MAS','BZA',430),('MAS','ED',395),('MAS','TPJ',337),('MDU','CAPE',245),('MDU','CBE',232),('MDU','TPJ',160),('NDLS','NZM',5),('NGP','BPL',395),('NGP','JL',416),('NGP','WL',453),('NZM','JHS',409),('NZM','KOTA',308),('NZM','NDLS',5),('PGT','CBE',55),('PGT','TVC',356),('PNBE','BSB',228),('PNBE','KOAA',534),('PNBE','RNC',407),('PUNE','CSMT',169),('RNC','BBS',555),('RNC','PNBE',407),('TPJ','ED',141),('TPJ','MAS',337),('TPJ','MDU',160),('TVC','CAPE',87),('TVC','PGT',356),('UDZ','ADI',633),('UDZ','JP',430),('UDZ','UJN',399),('UJN','BPL',183),('UJN','KOTA',280),('UJN','UDZ',399),('VSKP','BBS',442),('VSKP','BZA',350),('WL','BZA',207),('WL','HYB',151),('WL','NGP',453);
/*!40000 ALTER TABLE `neighbours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `path`
--

DROP TABLE IF EXISTS `path`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `path` (
  `train_no` int(10) unsigned NOT NULL,
  `station_code` varchar(5) NOT NULL,
  `sched_arr` time NOT NULL,
  `sched_dept` time NOT NULL,
  `stoppage_idx` int(11) NOT NULL,
  `distance` double unsigned NOT NULL,
  PRIMARY KEY (`train_no`,`station_code`),
  UNIQUE KEY `train_no` (`train_no`,`stoppage_idx`),
  KEY `station_code` (`station_code`),
  CONSTRAINT `path_ibfk_1` FOREIGN KEY (`train_no`) REFERENCES `train` (`train_no`),
  CONSTRAINT `path_ibfk_2` FOREIGN KEY (`station_code`) REFERENCES `station` (`station_code`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`sched_arr` < `sched_dept` and `stoppage_idx` >= 0 and `distance` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `path`
--

LOCK TABLES `path` WRITE;
/*!40000 ALTER TABLE `path` DISABLE KEYS */;
INSERT INTO `path` VALUES (12457,'CAPE','10:55:00','11:00:00',1,87),(12457,'MAS','19:00:00','19:30:00',4,829),(12457,'MDU','13:35:00','13:40:00',2,332),(12457,'TPJ','15:10:00','15:15:00',3,492),(12457,'TVC','10:00:00','10:20:00',0,0),(12698,'CBE','07:45:00','07:50:00',2,411),(12698,'ED','08:45:00','08:50:00',3,493),(12698,'MAS','11:55:00','12:25:00',4,888),(12698,'PGT','07:15:00','07:20:00',1,356),(12698,'TVC','03:30:00','03:50:00',0,0);
/*!40000 ALTER TABLE `path` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger check_path_insert
before insert on path
for each row
begin
    if(new.stoppage_idx = 0) then
        if(not exists (select *
                       from train as T
                       where (T.train_no = new.train_no))) then
            signal sqlstate '45000' set message_text = 'No such train exist';
        elseif(new.station_code <> (select source_st from train as T where T.train_no = new.train_no)) then
            signal sqlstate '45000' set message_text = 'Source station invalid';
        else 
            set new.distance = 0;
        end if;
    else
        if(not exists (select *
                       from path as T
                       where T.train_no = new.train_no and T.stoppage_idx = (new.stoppage_idx - 1) and
                             exists (select *
                                     from neighbours as U
                                     where U.st_a = T.station_code and U.st_b = new.station_code))) then
            signal sqlstate '45000' set message_text = 'No previous station exist for this train';
        else
            set new.distance = ((select T.distance
                                 from path as T
                                 where (T.train_no = new.train_no and T.stoppage_idx = (new.stoppage_idx - 1)))
                                 +
                                (select U.distance
                                 from neighbours as U
                                 where (U.st_b = new.station_code and U.st_a = (select T.station_code from
                                                                                      path as T
                                                                                      where T.train_no = new.train_no and T.stoppage_idx = (new.stoppage_idx - 1)))));
        end if;
    end if;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservation` (
  `train_no` int(10) unsigned NOT NULL,
  `seat_no` int(10) unsigned NOT NULL,
  `journey_date` date NOT NULL,
  `pnr` bigint(20) unsigned NOT NULL,
  `src_idx` int(11) NOT NULL,
  `dest_idx` int(11) NOT NULL,
  PRIMARY KEY (`train_no`,`seat_no`,`journey_date`,`src_idx`),
  UNIQUE KEY `pnr` (`pnr`),
  KEY `train_no` (`train_no`,`src_idx`),
  KEY `train_no_2` (`train_no`,`dest_idx`),
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`train_no`) REFERENCES `train` (`train_no`),
  CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`train_no`, `src_idx`) REFERENCES `path` (`train_no`, `stoppage_idx`),
  CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`train_no`, `dest_idx`) REFERENCES `path` (`train_no`, `stoppage_idx`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`seat_no` > 0 and `src_idx` < `dest_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservation` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger check_reservation_insert
before insert on reservation
for each row
begin
    if(exists (select *
               from reservation as T
               where T.train_no = new.train_no and T.journey_date = new.journey_date and T.seat_no = new.seat_no and
                     ((T.src_idx < new.dest_idx and T.src_idx > new.src_idx) or (T.dest_idx < new.dest_idx and T.dest_idx > new.src_idx) or
                      (T.src_idx <= new.src_idx and T.dest_idx >= new.dest_idx)))) then
        signal sqlstate '45000' set message_text = 'Seat already reserved';
    end if;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `station`
--

DROP TABLE IF EXISTS `station`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `station` (
  `station_code` varchar(5) NOT NULL,
  `station_name` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`station_code`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `station`
--

LOCK TABLES `station` WRITE;
/*!40000 ALTER TABLE `station` DISABLE KEYS */;
INSERT INTO `station` VALUES ('ADI','Ahmedabad','Ahmedabad'),('ALD','Allahabad Junction','Allahabad'),('BBS','Bhubaneswar Junction','Bhubaneswar'),('BNC','Bangalore Junction','Bangalore'),('BPL','Bhopal Junction','Bhopal'),('BSB','Varanasi Junction','Varanasi'),('BZA','Vijayawada Junction','Vijayawada'),('CAPE','Kanyakumari Junction','Kanyakumari'),('CBE','Coimbatore Junction','Coimbatore'),('CSMT','Chhatrapati Shivaji Maharaj Terminus','Mumbai'),('ED','Erode Junction','Erode'),('HYB','Hyderabad','Hyderabad'),('JHS','Jhansi','Jhansi'),('JL','Jalgaon','Jalgaon'),('JP','Jaipur','Jaipur'),('KOAA','Kolkata','Kolkata'),('KOTA','Kota','Kota'),('LKO','Lucknow','Lucknow'),('MAS','Chennai Central','Chennai'),('MDU','Madurai','Madurai'),('NDLS','New Delhi','Delhi'),('NGP','Nagpur Junction','Nagpur'),('NZM','Hazrat Nizamuddin','Delhi'),('PGT','Palakkad Junction','Palakkad'),('PNBE','Patna Junction','Patna'),('PUNE','Pune','Pune'),('RNC','Ranchi','Ranchi'),('TPJ','Tiruchchirapalli Junction','Tiruchchirapalli'),('TVC','Thiruvananthapuram Central','Thiruvananthapuram'),('UDZ','Udaipur Junction','Udaipur'),('UJN','Ujjain Junction','Ujjain'),('VSKP','Visakhapatnam','Visakhapatnam'),('WL','Warangal','Warangal');
/*!40000 ALTER TABLE `station` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `pnr` bigint(20) unsigned NOT NULL,
  `userid` varchar(50) NOT NULL,
  `source` varchar(5) NOT NULL,
  `dest` varchar(5) NOT NULL,
  `status` enum('CONFIRM','WAITLISTED','CANCELLED') DEFAULT 'CONFIRM',
  `train_no` int(10) unsigned NOT NULL,
  `date_resv` date NOT NULL,
  `time_resv` time NOT NULL,
  `date_journey` date NOT NULL,
  `seat_no` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`pnr`),
  KEY `userid` (`userid`),
  KEY `source` (`source`),
  KEY `dest` (`dest`),
  KEY `train_no` (`train_no`),
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`),
  CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`source`) REFERENCES `station` (`station_code`),
  CONSTRAINT `ticket_ibfk_3` FOREIGN KEY (`dest`) REFERENCES `station` (`station_code`),
  CONSTRAINT `ticket_ibfk_4` FOREIGN KEY (`train_no`) REFERENCES `train` (`train_no`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`date_resv` < `date_journey` and `pnr` > 0 and (`seat_no` is not null and `seat_no` <= 20 and `seat_no` > 0 or `status` <> 'CONFIRM' and `seat_no` is null))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger check_ticket_insert
before insert on ticket
for each row
begin
    declare src_idx int unsigned;
    declare dest_idx int unsigned;
    set new.date_resv = (select current_date);
    set new.time_resv = (select current_time);
    set src_idx = station_code_index(new.train_no, new.source);
    set dest_idx = station_code_index(new.train_no, new.dest);
    if(src_idx is null or dest_idx is null or src_idx >= dest_idx) then 
        signal sqlstate '45000' set message_text = "Invalid source/destination/train no";
    elseif (new.seat_no is null) then
        set new.status = 'WAITLISTED';
    else
        insert into reservation(train_no, seat_no, journey_date, pnr, src_idx, dest_idx)
        values (new.train_no, new.seat_no, new.date_journey, new.pnr, src_idx, dest_idx);
    end if;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger check_ticket_update
before update on ticket
for each row
begin
    declare src_idx int unsigned;
    declare dest_idx int unsigned;
    if(new.status = 'CONFIRM' and old.status = 'WAITLISTED') then
        set src_idx = station_code_index(new.train_no, new.source);
        set dest_idx = station_code_index(new.train_no, new.dest);
        insert into reservation(train_no, seat_no, journey_date, pnr, src_idx, dest_idx)
        values (new.train_no, new.seat_no, new.date_journey, new.pnr, src_idx, dest_idx);
    elseif(new.status = 'CANCELLED' and old.status <> 'CANCELLED') then
        delete from reservation
        where reservation.pnr = new.pnr;
    else 
        signal sqlstate '45000' set message_text = 'Action not allowed';
    end if;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `train`
--

DROP TABLE IF EXISTS `train`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `train` (
  `train_no` int(10) unsigned NOT NULL,
  `train_name` varchar(50) NOT NULL,
  `source_st` varchar(5) NOT NULL,
  `dest_st` varchar(5) NOT NULL,
  `fare_per_km` double unsigned NOT NULL,
  PRIMARY KEY (`train_no`),
  UNIQUE KEY `train_name` (`train_name`),
  KEY `source_st` (`source_st`),
  KEY `dest_st` (`dest_st`),
  CONSTRAINT `train_ibfk_1` FOREIGN KEY (`source_st`) REFERENCES `station` (`station_code`),
  CONSTRAINT `train_ibfk_2` FOREIGN KEY (`dest_st`) REFERENCES `station` (`station_code`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`source_st` <> `dest_st` and `fare_per_km` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `train`
--

LOCK TABLES `train` WRITE;
/*!40000 ALTER TABLE `train` DISABLE KEYS */;
INSERT INTO `train` VALUES (12457,'TVC-CAPE-MAS Superfast Express','TVC','MAS',2.3),(12698,'TVC-MAS Express','TVC','MAS',1.4);
/*!40000 ALTER TABLE `train` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `userid` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `aadhar_no` bigint(20) unsigned NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `aadhar_no` (`aadhar_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-21 22:58:53
