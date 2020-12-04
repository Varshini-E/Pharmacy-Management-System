-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2020 at 06:39 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `EXPIRY` ()  NO SQL
BEGIN
SELECT p_id,sup_id,med_id,p_qty,p_cost,pur_date,mfg_date,exp_date FROM purchase where exp_date between CURDATE() and DATE_SUB(CURDATE(), INTERVAL -6 MONTH);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SEARCH_INVENTORY` (IN `search` VARCHAR(255))  NO SQL
BEGIN
DECLARE mid DECIMAL(6);
DECLARE mname VARCHAR(50);
DECLARE mqty INT;
DECLARE mcategory VARCHAR(20);
DECLARE mprice DECIMAL(6,2);
DECLARE location VARCHAR(30);
DECLARE exit_loop BOOLEAN DEFAULT FALSE;
DECLARE MED_CURSOR CURSOR FOR SELECT MED_ID,MED_NAME,MED_QTY,CATEGORY,MED_PRICE,LOCATION_RACK FROM MEDS;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET exit_loop=TRUE;
CREATE TEMPORARY TABLE IF NOT EXISTS T1 (medid decimal(6),medname varchar(50),medqty int,medcategory varchar(20),medprice decimal(6,2),medlocation varchar(30));
OPEN MED_CURSOR;
med_loop: LOOP
FETCH FROM MED_CURSOR INTO mid,mname,mqty,mcategory,mprice,location;
IF exit_loop THEN
LEAVE med_loop;
END IF;

IF(CONCAT(mid,mname,mcategory,location) LIKE CONCAT('%',search,'%')) THEN
INSERT INTO T1(medid,medname,medqty,medcategory,medprice,medlocation)
VALUES(mid,mname,mqty,mcategory,mprice,location);
END IF;
END LOOP med_loop;
CLOSE MED_CURSOR;
SELECT medid,medname,medqty,medcategory,medprice,medlocation FROM T1; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `STOCK` ()  NO SQL
BEGIN
SELECT med_id, med_name,med_qty,category,med_price,location_rack FROM meds where med_qty<=50;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TOTAL_AMT` (IN `ID` INT, OUT `AMT` DECIMAL(8,2))  NO SQL
BEGIN
UPDATE SALES SET S_DATE=SYSDATE(),S_TIME=CURRENT_TIMESTAMP(),TOTAL_AMT=(SELECT SUM(TOT_PRICE) FROM SALES_ITEMS WHERE SALES_ITEMS.SALE_ID=ID) WHERE SALES.SALE_ID=ID;
SELECT TOTAL_AMT INTO AMT FROM SALES WHERE SALE_ID=ID;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `P_AMT` (`start` DATE, `end` DATE) RETURNS DECIMAL(8,2) NO SQL
    DETERMINISTIC
BEGIN
DECLARE PAMT DECIMAL(8,2) DEFAULT 0.0;
SELECT SUM(P_COST) INTO PAMT FROM PURCHASE WHERE PUR_DATE >= start AND PUR_DATE<= end;
RETURN PAMT;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `S_AMT` (`start` DATE, `end` DATE) RETURNS DECIMAL(8,2) NO SQL
BEGIN
DECLARE SAMT DECIMAL(8,2) DEFAULT 0.0;
SELECT SUM(TOTAL_AMT) INTO SAMT FROM SALES WHERE S_DATE >= start AND S_DATE<= end;
RETURN SAMT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` decimal(7,0) NOT NULL,
  `A_USERNAME` varchar(50) NOT NULL,
  `A_PASSWORD` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `A_USERNAME`, `A_PASSWORD`) VALUES
('1', 'admin', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `C_ID` decimal(6,0) NOT NULL,
  `C_FNAME` varchar(30) NOT NULL,
  `C_LNAME` varchar(30) DEFAULT NULL,
  `C_AGE` int(11) NOT NULL,
  `C_SEX` varchar(6) NOT NULL,
  `C_PHNO` decimal(10,0) NOT NULL,
  `C_MAIL` varchar(40) DEFAULT NULL
) ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`C_ID`, `C_FNAME`, `C_LNAME`, `C_AGE`, `C_SEX`, `C_PHNO`, `C_MAIL`) VALUES
('987101', 'Safia', 'Malik', 22, 'Female', '9632587415', 'safia@gmail.com'),
('987102', 'Varun', 'Ilango', 24, 'Male', '9987565423', 'varun@gmail.com'),
('987103', 'Suja', 'Suresh', 45, 'Female', '7896541236', 'suja@hotmail.com'),
('987104', 'Agatha', 'Elizabeth', 30, 'Female', '7845129635', 'agatha@gmail.com'),
('987105', 'Zayed', 'Shah', 40, 'Male', '6789541235', 'zshah@hotmail.com'),
('987106', 'Vijay', 'Kumar', 60, 'Male', '8996574123', 'vijayk@yahoo.com'),
('987107', 'Meera', 'Das', 35, 'Female', '7845963259', 'meera@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `emplogin`
--

CREATE TABLE `emplogin` (
  `E_ID` decimal(7,0) NOT NULL,
  `E_USERNAME` varchar(20) NOT NULL,
  `E_PASS` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emplogin`
--

INSERT INTO `emplogin` (`E_ID`, `E_USERNAME`, `E_PASS`) VALUES
('4567005', 'amaya', 'pass1'),
('4567002', 'anita', 'pass2'),
('4567010', 'daniel', 'pass3'),
('4567003', 'harish', 'pass4'),
('4567009', 'shayla', 'pass5'),
('4567006', 'shoaib', 'pass6'),
('4567001', 'varshini', 'pass7');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `E_ID` decimal(7,0) NOT NULL,
  `E_FNAME` varchar(30) NOT NULL,
  `E_LNAME` varchar(30) DEFAULT NULL,
  `BDATE` date NOT NULL,
  `E_AGE` int(11) NOT NULL,
  `E_SEX` varchar(6) NOT NULL,
  `E_TYPE` varchar(20) NOT NULL,
  `E_JDATE` date NOT NULL,
  `E_SAL` decimal(8,2) NOT NULL,
  `E_PHNO` decimal(10,0) NOT NULL,
  `E_MAIL` varchar(40) DEFAULT NULL,
  `E_ADD` varchar(40) DEFAULT NULL
) ;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`E_ID`, `E_FNAME`, `E_LNAME`, `BDATE`, `E_AGE`, `E_SEX`, `E_TYPE`, `E_JDATE`, `E_SAL`, `E_PHNO`, `E_MAIL`, `E_ADD`) VALUES
('1', 'Admin', '-', '1989-05-24', 30, 'Female', 'Admin', '2009-06-24', '95000.00', '9874563219', 'admin@pharmacia.com', 'Chennai'),
('4567001', 'Varshini', 'Elangovan', '1995-10-05', 25, 'Female', 'Pharmacist', '2017-11-12', '25000.00', '9967845123', 'evarsh@hotmail.com', 'Thiruvanmiyur'),
('4567002', 'Anita', 'Shree', '2000-10-03', 20, 'Female', 'Pharmacist', '2012-10-06', '45000.00', '8546123566', 'anita@gmail.com', 'Adyar'),
('4567003', 'Harish', 'Raja', '1998-02-01', 22, 'Male', 'Pharmacist', '2019-07-06', '21000.00', '7854123694', 'harishraja@live.com', 'T.Nagar'),
('4567005', 'Amaya', 'Singh', '1992-01-02', 28, 'Female', 'Pharmacist', '2017-05-16', '32000.00', '7894532165', 'amaya@gmail.com', 'Kottivakkam'),
('4567006', 'Shoaib', 'Ahmed', '1999-12-11', 20, 'Male', 'Pharmacist', '2018-09-05', '28000.00', '7896541234', 'shoaib@hotmail.com', 'Porur'),
('4567009', 'Shayla', 'Hussain', '1980-02-28', 40, 'Female', 'Manager', '2010-05-06', '80000.00', '7854123695', 'shaylah@gmail.com', 'Adyar'),
('4567010', 'Daniel', 'James', '1993-04-05', 27, 'Male', 'Pharmacist', '2016-01-05', '30000.00', '7896541235', 'daniels@gmail.com', 'Kodambakkam');

-- --------------------------------------------------------

--
-- Table structure for table `meds`
--

CREATE TABLE `meds` (
  `MED_ID` decimal(6,0) NOT NULL,
  `MED_NAME` varchar(50) NOT NULL,
  `MED_QTY` int(11) NOT NULL,
  `CATEGORY` varchar(20) DEFAULT NULL,
  `MED_PRICE` decimal(6,2) NOT NULL,
  `LOCATION_RACK` varchar(30) DEFAULT NULL
) ;

--
-- Dumping data for table `meds`
--

INSERT INTO `meds` (`MED_ID`, `MED_NAME`, `MED_QTY`, `CATEGORY`, `MED_PRICE`, `LOCATION_RACK`) VALUES
('123001', 'Dolo 650 MG', 625, 'Tablet', '1.00', 'rack 5'),
('123002', 'Panadol Cold & Flu', 90, 'Tablet', '2.50', 'rack 6'),
('123003', 'Livogen', 25, 'Capsule', '5.00', 'rack 3'),
('123004', 'Gelusil', 440, 'Tablet', '1.25', 'rack 4'),
('123005', 'Cyclopam', 120, 'Tablet', '6.00', 'rack 2'),
('123006', 'Benadryl 200 ML', 35, 'Syrup', '50.00', 'rack 10'),
('123007', 'Lopamide', 15, 'Capsule', '5.00', 'rack 7'),
('123008', 'Vitamic C', 90, 'Tablet', '3.00', 'rack 8'),
('123009', 'Omeprazole', 35, 'Capsule', '4.00', 'rack 3'),
('123010', 'Concur 5 MG', 600, 'Tablet', '3.50', 'rack 9'),
('123011', 'Augmentin 250 ML', 115, 'Syrup', '80.00', 'rack 7');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `P_ID` decimal(4,0) NOT NULL,
  `SUP_ID` decimal(3,0) NOT NULL,
  `MED_ID` decimal(6,0) NOT NULL,
  `P_QTY` int(11) NOT NULL,
  `P_COST` decimal(8,2) NOT NULL,
  `PUR_DATE` date NOT NULL,
  `MFG_DATE` date NOT NULL,
  `EXP_DATE` date NOT NULL
) ;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`P_ID`, `SUP_ID`, `MED_ID`, `P_QTY`, `P_COST`, `PUR_DATE`, `MFG_DATE`, `EXP_DATE`) VALUES
('1001', '136', '123010', 200, '1500.50', '2020-03-01', '2019-05-05', '2021-05-10'),
('1002', '123', '123002', 1000, '3000.00', '2020-02-01', '2018-06-01', '2020-12-05'),
('1003', '145', '123006', 20, '800.00', '2020-04-22', '2017-02-05', '2020-07-01'),
('1004', '156', '123004', 250, '1000.00', '2020-04-02', '2020-05-06', '2023-05-06'),
('1005', '123', '123005', 200, '1200.00', '2020-02-01', '2019-08-02', '2021-04-01'),
('1006', '162', '123010', 500, '1500.00', '2019-04-22', '2018-01-01', '2020-05-02'),
('1007', '123', '123001', 500, '450.00', '2020-01-02', '2019-01-05', '2022-03-06');

--
-- Triggers `purchase`
--
DELIMITER $$
CREATE TRIGGER `QTYDELETE` AFTER DELETE ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-old.P_QTY WHERE meds.MED_ID=old.MED_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `QTYINSERT` AFTER INSERT ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY+new.P_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `QTYUPDATE` AFTER UPDATE ON `purchase` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-old.P_QTY WHERE meds.MED_ID=new.MED_ID;
UPDATE meds SET MED_QTY=MED_QTY+new.P_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SALE_ID` int(11) NOT NULL,
  `C_ID` decimal(6,0) NOT NULL,
  `S_DATE` date DEFAULT NULL,
  `S_TIME` time DEFAULT NULL,
  `TOTAL_AMT` decimal(8,2) DEFAULT NULL,
  `E_ID` decimal(7,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`SALE_ID`, `C_ID`, `S_DATE`, `S_TIME`, `TOTAL_AMT`, `E_ID`) VALUES
(1, '987101', '2020-04-15', '13:23:03', '180.00', '4567009'),
(2, '987106', '2020-04-21', '20:19:31', '585.00', '1'),
(3, '987103', '2020-04-15', '11:23:53', '120.00', '4567010'),
(4, '987104', '2020-04-14', '18:20:00', '955.00', '4567006'),
(5, '987103', '2020-04-21', '15:24:43', '45.00', '1'),
(6, '987102', '2020-03-11', '10:24:43', '140.00', '4567001'),
(7, '987105', '2020-04-24', '00:25:54', '350.00', '1'),
(8, '987104', '2020-04-24', '00:47:47', '35.00', '4567001'),
(12, '987103', '2020-04-24', '19:33:16', '60.00', '1'),
(13, '987104', '2020-04-24', '21:15:56', '62.50', '4567001'),
(15, '987107', '2020-12-04', '18:39:46', '420.00', '1'),
(16, '987106', '2020-12-04', '18:52:21', '30.00', '1'),
(17, '987103', '2020-12-04', '19:35:56', '57.50', '1'),
(18, '987105', '2020-12-04', '19:36:56', '160.00', '4567001'),
(20, '987103', '2020-12-04', '22:53:18', '150.00', '4567001');

--
-- Triggers `sales`
--
DELIMITER $$
CREATE TRIGGER `SALE_ID_DELETE` BEFORE DELETE ON `sales` FOR EACH ROW BEGIN
DELETE from sales_items WHERE sales_items.SALE_ID=old.SALE_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `SALE_ID` int(11) NOT NULL,
  `MED_ID` decimal(6,0) NOT NULL,
  `SALE_QTY` int(11) NOT NULL,
  `TOT_PRICE` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`SALE_ID`, `MED_ID`, `SALE_QTY`, `TOT_PRICE`) VALUES
(1, '123001', 20, '20.00'),
(1, '123011', 2, '160.00'),
(2, '123003', 75, '225.00'),
(2, '123005', 60, '360.00'),
(3, '123008', 40, '120.00'),
(4, '123010', 250, '875.00'),
(4, '123011', 1, '80.00'),
(5, '123001', 45, '45.00'),
(6, '123006', 2, '100.00'),
(6, '123009', 10, '40.00'),
(7, '123001', 100, '100.00'),
(7, '123003', 50, '250.00'),
(8, '123001', 10, '10.00'),
(8, '123002', 10, '25.00'),
(12, '123005', 10, '60.00'),
(13, '123002', 25, '62.50'),
(15, '123005', 45, '270.00'),
(15, '123006', 3, '150.00'),
(16, '123008', 10, '30.00'),
(17, '123004', 10, '12.50'),
(17, '123007', 5, '25.00'),
(17, '123009', 5, '20.00'),
(18, '123011', 2, '160.00'),
(20, '123005', 25, '150.00');

--
-- Triggers `sales_items`
--
DELIMITER $$
CREATE TRIGGER `SALEDELETE` AFTER DELETE ON `sales_items` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY+old.SALE_QTY WHERE meds.MED_ID=old.MED_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `SALEINSERT` AFTER INSERT ON `sales_items` FOR EACH ROW BEGIN
UPDATE meds SET MED_QTY=MED_QTY-new.SALE_QTY WHERE meds.MED_ID=new.MED_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SUP_ID` decimal(3,0) NOT NULL,
  `SUP_NAME` varchar(25) NOT NULL,
  `SUP_ADD` varchar(30) NOT NULL,
  `SUP_PHNO` decimal(10,0) NOT NULL,
  `SUP_MAIL` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SUP_ID`, `SUP_NAME`, `SUP_ADD`, `SUP_PHNO`, `SUP_MAIL`) VALUES
('123', 'XYZ Pharmaceuticals', 'Chennai, Tamil Nadu', '8745632145', 'xyz@xyzpharma.com'),
('136', 'ABC PharmaSupply', 'Trichy', '7894561235', 'abc@pharmsupp.com'),
('145', 'Daily Pharma Ltd', 'Hyderabad', '7854699321', 'daily@dpharma.com'),
('156', 'MedAll', 'Chennai', '9874585236', 'mainid@medall.com'),
('162', 'MedHead Pharmaceuticals', 'Trichy', '7894561335', 'abc@pharmsupp.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`A_USERNAME`),
  ADD UNIQUE KEY `USERNAME` (`A_USERNAME`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`C_ID`),
  ADD UNIQUE KEY `C_PHNO` (`C_PHNO`),
  ADD UNIQUE KEY `C_MAIL` (`C_MAIL`);

--
-- Indexes for table `emplogin`
--
ALTER TABLE `emplogin`
  ADD PRIMARY KEY (`E_USERNAME`),
  ADD KEY `E_ID` (`E_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`E_ID`);

--
-- Indexes for table `meds`
--
ALTER TABLE `meds`
  ADD PRIMARY KEY (`MED_ID`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`P_ID`,`MED_ID`),
  ADD KEY `SUP_ID` (`SUP_ID`),
  ADD KEY `MED_ID` (`MED_ID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SALE_ID`),
  ADD KEY `C_ID` (`C_ID`),
  ADD KEY `E_ID` (`E_ID`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`SALE_ID`,`MED_ID`),
  ADD KEY `MED_ID` (`MED_ID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SUP_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `SALE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `employee` (`E_ID`);

--
-- Constraints for table `emplogin`
--
ALTER TABLE `emplogin`
  ADD CONSTRAINT `emplogin_ibfk_1` FOREIGN KEY (`E_ID`) REFERENCES `employee` (`E_ID`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`SUP_ID`) REFERENCES `suppliers` (`SUP_ID`),
  ADD CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`MED_ID`) REFERENCES `meds` (`MED_ID`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`C_ID`) REFERENCES `customer` (`C_ID`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`E_ID`) REFERENCES `employee` (`E_ID`);

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_ibfk_1` FOREIGN KEY (`SALE_ID`) REFERENCES `sales` (`SALE_ID`),
  ADD CONSTRAINT `sales_items_ibfk_2` FOREIGN KEY (`MED_ID`) REFERENCES `meds` (`MED_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
