-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2016 at 07:19 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `az_kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
`idconfig` int(11) NOT NULL,
  `key` varchar(100) DEFAULT NULL,
  `value` text,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`idconfig`, `key`, `value`, `created`, `createdby`, `updated`, `updatedby`) VALUES
(1, 'prefix_barcode', 'AZT', '2016-03-14 00:00:00', 'system', NULL, NULL),
(2, 'prefix_nota', 'AZPJ', '2016-04-19 00:00:00', 'system', NULL, NULL),
(3, 'store_name', 'Toko Jakarta', '2016-05-03 00:00:00', 'System', NULL, NULL),
(4, 'store_description', 'Jl. Raya Indonesia Raya', '2016-05-03 00:00:00', 'System', NULL, NULL),
(5, 'store_phone', '08123123123', '2016-06-09 00:00:00', 'system', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
`idcustomer` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `idsales` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_price`
--

CREATE TABLE IF NOT EXISTS `customer_price` (
`idcustomer_price` int(11) NOT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `idproduct` bigint(20) unsigned DEFAULT NULL,
  `price` bigint(20) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
`idgroup` int(11) NOT NULL,
  `group_name` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(200) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`idgroup`, `group_name`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES
(1, 'administrator', 'Administrator', '2016-04-26 00:04:00', 'System', NULL, NULL),
(2, 'kasir', 'Kasir', '2016-04-26 00:05:00', 'System', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
`idproduct` bigint(11) unsigned NOT NULL,
  `idproduct_unit` bigint(11) unsigned DEFAULT NULL,
  `idproduct_category` bigint(11) unsigned DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `name` varchar(300) DEFAULT NULL,
  `price` bigint(11) unsigned DEFAULT NULL,
  `stock` bigint(11) DEFAULT '0',
  `description` varchar(300) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
`idproduct_category` bigint(11) unsigned NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_stock`
--

CREATE TABLE IF NOT EXISTS `product_stock` (
`idproduct_stock` bigint(11) unsigned NOT NULL,
  `idproduct` bigint(11) unsigned DEFAULT NULL,
  `idtransaction_group` bigint(20) unsigned DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `idsupplier` bigint(11) unsigned DEFAULT NULL,
  `stock_date` datetime DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `detail` varchar(100) DEFAULT NULL,
  `detail2` varchar(300) DEFAULT NULL,
  `total` bigint(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

--
-- Triggers `product_stock`
--
DELIMITER //
CREATE TRIGGER `delete_product_stock` AFTER DELETE ON `product_stock`
 FOR EACH ROW BEGIN
	IF OLD.type = 'stok_masuk' THEN
    	BEGIN
        	UPDATE product set stock = stock - OLD.total 
  			WHERE idproduct = OLD.idproduct;
        END;
    END IF;
    IF OLD.type = 'stok_keluar' THEN
    	BEGIN
        	UPDATE product set stock = stock + OLD.total 
  			WHERE idproduct = OLD.idproduct;
        END;
    END IF;
    
END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `insert_product_stock` AFTER INSERT ON `product_stock`
 FOR EACH ROW BEGIN
	IF NEW.type = 'stok_masuk' THEN
    	BEGIN
        	UPDATE product set stock = stock + NEW.total 
  			WHERE idproduct = NEW.idproduct;
        END;
    END IF;
    IF NEW.type = 'stok_keluar' THEN
    	BEGIN
        	UPDATE product set stock = stock - NEW.total 
  			WHERE idproduct = NEW.idproduct;
        END;
    END IF;
    
END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `update_product_stock` AFTER UPDATE ON `product_stock`
 FOR EACH ROW BEGIN
	IF OLD.type = 'stok_masuk' THEN
    	BEGIN
            UPDATE product set stock = stock - OLD.total WHERE idproduct = OLD.idproduct; 
     	END;
    END IF;
    IF OLD.type = 'stok_keluar' THEN
    	BEGIN
        	UPDATE product set stock = stock + OLD.total WHERE idproduct = OLD.idproduct;
        END;
    END IF;
    IF NEW.type = 'stok_masuk' THEN
    	BEGIN
        	UPDATE product set stock = stock + NEW.total 
  			WHERE idproduct = NEW.idproduct;
        END;
    END IF;
    IF NEW.type = 'stok_keluar' THEN
    	BEGIN
        	UPDATE product set stock = stock - NEW.total 
  			WHERE idproduct = NEW.idproduct;
        END;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product_unit`
--

CREATE TABLE IF NOT EXISTS `product_unit` (
`idproduct_unit` bigint(11) unsigned NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
`idsupplier` bigint(11) unsigned NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
`idtransaction` bigint(20) NOT NULL,
  `idtransaction_group` bigint(20) unsigned DEFAULT NULL,
  `idproduct` bigint(20) unsigned DEFAULT NULL,
  `qty` bigint(11) DEFAULT NULL,
  `discount` bigint(20) unsigned DEFAULT NULL,
  `sell_price` bigint(20) unsigned DEFAULT NULL,
  `final_price` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_group`
--

CREATE TABLE IF NOT EXISTS `transaction_group` (
`idtransaction_group` bigint(20) unsigned NOT NULL,
  `idcustomer` int(11) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `code` varchar(200) DEFAULT NULL,
  `total_discount` bigint(20) unsigned DEFAULT NULL,
  `total_sell_price` bigint(20) unsigned DEFAULT NULL,
  `total_final_price` bigint(20) unsigned DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `total_cash` bigint(20) unsigned DEFAULT NULL,
  `total_change` bigint(20) DEFAULT NULL,
  `payment_type` varchar(20) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL COMMENT 'PROCESS, PENDING, OK',
  `note` text,
  `session` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`iduser` int(11) NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(200) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `username`, `password`, `name`, `created`, `createdby`, `updated`, `updatedby`) VALUES
(1, 'administrator', '5f4dcc3b5aa765d61d8327deb882cf99', 'Administrator', '2016-04-26 00:08:00', 'System', NULL, NULL),
(2, 'kasir1', '81dc9bdb52d04dc20036dbd8313ed055', 'Mas Kasir', '2016-04-26 00:09:00', 'System', '2016-04-28 20:55:27', 'administrator'),
(3, 'kasir2', '698d51a19d8a121ce581499d7b701668', 'Mbak Kasir', '2016-04-26 00:09:00', 'System', '2016-04-28 20:55:49', 'administrator'),
(8, 'kasir3', '81dc9bdb52d04dc20036dbd8313ed055', 'Kasir 3', '2016-05-03 19:44:39', NULL, '2016-05-03 19:44:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
`iduser_group` int(11) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `idgroup` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(200) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`iduser_group`, `iduser`, `idgroup`, `created`, `createdby`, `updated`, `updatedby`) VALUES
(1, 1, 1, '2016-04-26 00:10:00', 'System', NULL, NULL),
(2, 2, 2, '2016-04-26 00:11:00', 'System', NULL, NULL),
(3, 3, 2, '2016-04-26 00:12:00', 'System', NULL, NULL),
(6, 8, 2, '2016-05-03 19:44:39', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`idconfig`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
 ADD PRIMARY KEY (`idcustomer`);

--
-- Indexes for table `customer_price`
--
ALTER TABLE `customer_price`
 ADD PRIMARY KEY (`idcustomer_price`), ADD KEY `idcustomer` (`idcustomer`,`idproduct`), ADD KEY `idproduct` (`idproduct`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
 ADD PRIMARY KEY (`idgroup`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
 ADD PRIMARY KEY (`idproduct`), ADD UNIQUE KEY `barcode` (`barcode`), ADD KEY `idsupplier` (`idproduct_category`), ADD KEY `idproduct_unit` (`idproduct_unit`), ADD KEY `idkategori` (`idproduct_category`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
 ADD PRIMARY KEY (`idproduct_category`);

--
-- Indexes for table `product_stock`
--
ALTER TABLE `product_stock`
 ADD PRIMARY KEY (`idproduct_stock`), ADD KEY `idproduct` (`idproduct`), ADD KEY `idtransaction_group` (`idtransaction_group`), ADD KEY `iduser` (`iduser`), ADD KEY `idsupplier` (`idsupplier`);

--
-- Indexes for table `product_unit`
--
ALTER TABLE `product_unit`
 ADD PRIMARY KEY (`idproduct_unit`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
 ADD PRIMARY KEY (`idsupplier`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
 ADD PRIMARY KEY (`idtransaction`), ADD KEY `idproduct` (`idproduct`), ADD KEY `idtransaction_group` (`idtransaction_group`);

--
-- Indexes for table `transaction_group`
--
ALTER TABLE `transaction_group`
 ADD PRIMARY KEY (`idtransaction_group`), ADD UNIQUE KEY `code` (`code`), ADD KEY `idcustomer` (`idcustomer`), ADD KEY `iduser` (`iduser`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`iduser`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
 ADD PRIMARY KEY (`iduser_group`), ADD KEY `iduser` (`iduser`,`idgroup`), ADD KEY `idgroup` (`idgroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
MODIFY `idconfig` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
MODIFY `idcustomer` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `customer_price`
--
ALTER TABLE `customer_price`
MODIFY `idcustomer_price` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
MODIFY `idgroup` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
MODIFY `idproduct` bigint(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
MODIFY `idproduct_category` bigint(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `product_stock`
--
ALTER TABLE `product_stock`
MODIFY `idproduct_stock` bigint(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `product_unit`
--
ALTER TABLE `product_unit`
MODIFY `idproduct_unit` bigint(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
MODIFY `idsupplier` bigint(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
MODIFY `idtransaction` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `transaction_group`
--
ALTER TABLE `transaction_group`
MODIFY `idtransaction_group` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=246;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
MODIFY `iduser_group` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_price`
--
ALTER TABLE `customer_price`
ADD CONSTRAINT `customer_price_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customer` (`idcustomer`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `customer_price_ibfk_2` FOREIGN KEY (`idproduct`) REFERENCES `product` (`idproduct`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`idproduct_unit`) REFERENCES `product_unit` (`idproduct_unit`),
ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`idproduct_category`) REFERENCES `product_category` (`idproduct_category`);

--
-- Constraints for table `product_stock`
--
ALTER TABLE `product_stock`
ADD CONSTRAINT `product_stock_ibfk_1` FOREIGN KEY (`idproduct`) REFERENCES `product` (`idproduct`),
ADD CONSTRAINT `product_stock_ibfk_2` FOREIGN KEY (`idtransaction_group`) REFERENCES `transaction_group` (`idtransaction_group`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `product_stock_ibfk_3` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`),
ADD CONSTRAINT `product_stock_ibfk_4` FOREIGN KEY (`idsupplier`) REFERENCES `supplier` (`idsupplier`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`idproduct`) REFERENCES `product` (`idproduct`),
ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`idtransaction_group`) REFERENCES `transaction_group` (`idtransaction_group`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction_group`
--
ALTER TABLE `transaction_group`
ADD CONSTRAINT `transaction_group_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customer` (`idcustomer`),
ADD CONSTRAINT `transaction_group_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`);

--
-- Constraints for table `user_group`
--
ALTER TABLE `user_group`
ADD CONSTRAINT `user_group_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `user_group_ibfk_2` FOREIGN KEY (`idgroup`) REFERENCES `group` (`idgroup`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
