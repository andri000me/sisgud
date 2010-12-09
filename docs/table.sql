-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 10, 2009 at 09:56 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `sisgud`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `cat_code` int(2) NOT NULL,
  `cat_name` varchar(128) NOT NULL,
  `cat_desc` text NOT NULL,
  `op_code` int(11) NOT NULL,
  PRIMARY KEY (`cat_code`),
  KEY `op_code` (`op_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_code`, `cat_name`, `cat_desc`, `op_code`) VALUES
(10, 'Celana Tigaperempat', '', 1),
(11, 'Celana Pendek', '', 1),
(12, 'Celana Panjang', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `item_code` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(128) NOT NULL,
  `item_hm` int(11) NOT NULL,
  `item_hj` int(11) NOT NULL,
  `item_qty_first` int(11) NOT NULL,
  `item_qty_stock` int(11) NOT NULL,
  `op_code` int(11) NOT NULL,
  `cat_code` int(11) NOT NULL,
  `sup_code` varchar(128) NOT NULL,
  PRIMARY KEY (`item_code`),
  KEY `op_code` (`op_code`),
  KEY `cat_code` (`cat_code`),
  KEY `sup_code` (`sup_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `item`
--


-- --------------------------------------------------------

--
-- Table structure for table `item_distribution`
--

CREATE TABLE IF NOT EXISTS `item_distribution` (
  `dist_code` varchar(128) NOT NULL,
  `item_code` int(11) NOT NULL,
  `shop_code` int(11) NOT NULL,
  `dist_out` date NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`dist_code`),
  KEY `item_code` (`item_code`),
  KEY `shop_code` (`shop_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_distribution`
--


-- --------------------------------------------------------

--
-- Table structure for table `item_retur`
--

CREATE TABLE IF NOT EXISTS `item_retur` (
  `retur_code` int(11) NOT NULL,
  `retur_date` date DEFAULT NULL,
  `item_code` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `shop_code` int(2) NOT NULL,
  `op_code` int(11) NOT NULL,
  PRIMARY KEY (`retur_code`),
  KEY `item_code` (`item_code`),
  KEY `shop_code` (`shop_code`),
  KEY `op_code` (`op_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_retur`
--


-- --------------------------------------------------------

--
-- Table structure for table `log_transaksi`
--

CREATE TABLE IF NOT EXISTS `log_transaksi` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_name` varchar(128) NOT NULL,
  `log_time` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `p_id` (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `log_transaksi`
--

INSERT INTO `log_transaksi` (`log_id`, `trans_name`, `log_time`, `p_id`, `keterangan`) VALUES
(1, 'Memasukan data supplier', 838, 1, ''),
(2, 'Memasukan data supplier', 838, 1, ''),
(3, 'Memasukan data supplier', 1248364335, 1, ''),
(4, 'Memasukan data supplier', 1248364376, 1, ''),
(5, 'Menambah kelompok barang', 1248768539, 1, ''),
(6, 'Menambah kelompok barang', 1248768552, 1, ''),
(7, 'Menambah kelompok barang', 1248768655, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `operator`
--

CREATE TABLE IF NOT EXISTS `operator` (
  `op_id` int(11) NOT NULL AUTO_INCREMENT,
  `op_name` varchar(128) NOT NULL,
  `op_phone` varchar(128) NOT NULL,
  `op_address` varchar(128) NOT NULL,
  PRIMARY KEY (`op_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `operator`
--

INSERT INTO `operator` (`op_id`, `op_name`, `op_phone`, `op_address`) VALUES
(1, 'Purwanto', '0219976845', 'Jln. Kenari 13 Kota Cinta');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE IF NOT EXISTS `pengguna` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_username` varchar(128) NOT NULL,
  `p_passwd` varchar(128) NOT NULL,
  `p_role` enum('admin','manajer','operator') DEFAULT NULL,
  `p_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`p_id`),
  UNIQUE KEY `p_username` (`p_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`p_id`, `p_username`, `p_passwd`, `p_role`, `p_active`) VALUES
(1, 'purwa', 'e10adc3949ba59abbe56e057f20f883e', 'operator', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `shop_code` int(2) NOT NULL,
  `shop_name` varchar(128) NOT NULL,
  `shop_address` varchar(128) NOT NULL,
  `shop_phone` varchar(128) NOT NULL,
  `shop_supervisor` varchar(128) NOT NULL,
  `shop_bonus` int(11) NOT NULL,
  `shop_target` int(11) NOT NULL,
  PRIMARY KEY (`shop_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shop`
--


-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `sup_code` varchar(128) NOT NULL,
  `sup_name` varchar(128) NOT NULL,
  `sup_address` varchar(128) NOT NULL,
  `sup_phone` varchar(128) NOT NULL,
  `op_code` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  PRIMARY KEY (`sup_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`sup_code`, `sup_name`, `sup_address`, `sup_phone`, `op_code`, `entry_date`) VALUES
('A01', 'Alamak', 'Bandung', '02211228855', 0, '0000-00-00'),
('A05', 'Ferry', 'Bandung', '022331234', 0, '0000-00-00'),
('A11', 'Humvee', 'Bandung', '0223455667', 0, '0000-00-00'),
('A12', 'Eksray', 'Bandung', '022113451', 0, '0000-00-00'),
('A13', 'Eksray-X', 'Bandung', '022113451', 0, '0000-00-00'),
('A16', 'Airborne', 'Bandung', '022123416', 0, '0000-00-00'),
('B12', 'Batik Keren', 'Jogja', '025733214', 0, '0000-00-00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`op_code`) REFERENCES `operator` (`op_id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`op_code`) REFERENCES `operator` (`op_id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`cat_code`) REFERENCES `category` (`cat_code`),
  ADD CONSTRAINT `item_ibfk_3` FOREIGN KEY (`sup_code`) REFERENCES `supplier` (`sup_code`);

--
-- Constraints for table `item_distribution`
--
ALTER TABLE `item_distribution`
  ADD CONSTRAINT `item_distribution_ibfk_1` FOREIGN KEY (`item_code`) REFERENCES `item` (`item_code`),
  ADD CONSTRAINT `item_distribution_ibfk_2` FOREIGN KEY (`shop_code`) REFERENCES `shop` (`shop_code`);

--
-- Constraints for table `item_retur`
--
ALTER TABLE `item_retur`
  ADD CONSTRAINT `item_retur_ibfk_1` FOREIGN KEY (`item_code`) REFERENCES `item` (`item_code`),
  ADD CONSTRAINT `item_retur_ibfk_2` FOREIGN KEY (`shop_code`) REFERENCES `shop` (`shop_code`),
  ADD CONSTRAINT `item_retur_ibfk_3` FOREIGN KEY (`op_code`) REFERENCES `operator` (`op_id`);

--
-- Constraints for table `log_transaksi`
--
ALTER TABLE `log_transaksi`
  ADD CONSTRAINT `log_transaksi_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `pengguna` (`p_id`);

--
-- Constraints for table `operator`
--
ALTER TABLE `operator`
  ADD CONSTRAINT `operator_ibfk_1` FOREIGN KEY (`op_id`) REFERENCES `pengguna` (`p_id`);
