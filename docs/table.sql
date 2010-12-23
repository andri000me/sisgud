-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 21, 2010 at 02:01 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `sisgud`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `cat_code` varchar(3) NOT NULL,
  `cat_name` varchar(128) NOT NULL,
  `cat_desc` text NOT NULL,
  `op_code` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  PRIMARY KEY (`cat_code`),
  KEY `op_code` (`op_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `item_code` varchar(128) NOT NULL,
  `item_name` varchar(128) NOT NULL,
  `item_hm` int(11) NOT NULL,
  `item_hp` int(11) NOT NULL,
  `item_hj` int(11) NOT NULL,
  `item_qty_total` int(11) NOT NULL,
  `item_qty_stock` int(11) NOT NULL,
  `cat_code` varchar(3) NOT NULL,
  `sup_code` varchar(128) NOT NULL,
  `op_code` int(11) NOT NULL,
  PRIMARY KEY (`item_code`),
  KEY `cat_code` (`cat_code`),
  KEY `sup_code` (`sup_code`,`op_code`),
  KEY `op_code` (`op_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_distribution`
--

CREATE TABLE IF NOT EXISTS `item_distribution` (
  `dist_code` int(11) NOT NULL,
  `item_code` varchar(128) NOT NULL,
  `shop_code` varchar(128) NOT NULL,
  `dist_out` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `item_disc` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `export` int(11) NOT NULL,
  KEY `item_code` (`item_code`),
  KEY `shop_code` (`shop_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_mutasi`
--

CREATE TABLE IF NOT EXISTS `item_mutasi` (
  `kode_mutasi` varchar(128) NOT NULL,
  `item_code` varchar(128) NOT NULL,
  `sup_code` varchar(128) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_entry` date NOT NULL,
  `date_bon` date NOT NULL,
  `status_print_mutasi` tinyint(4) NOT NULL,
  KEY `item_code` (`item_code`),
  KEY `sup_code``` (`sup_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_retur`
--

CREATE TABLE IF NOT EXISTS `item_retur` (
  `retur_code` int(11) NOT NULL,
  `retur_date` date NOT NULL,
  `item_code` varchar(128) NOT NULL,
  `quantity` int(11) NOT NULL,
  `shop_code` varchar(128) NOT NULL,
  `op_code` int(11) NOT NULL,
  KEY `item_code` (`item_code`),
  KEY `shop_code` (`shop_code`),
  KEY `op_code` (`op_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE IF NOT EXISTS `pengguna` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_username` varchar(128) NOT NULL,
  `p_passwd` varchar(128) NOT NULL,
  `p_role` enum('admin','supervisor','operator','user') DEFAULT NULL,
  `p_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`p_id`),
  UNIQUE KEY `p_username` (`p_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `shop_code` varchar(128) NOT NULL,
  `shop_name` varchar(128) NOT NULL,
  `shop_initial` varchar(128) NOT NULL,
  `shop_address` varchar(128) NOT NULL,
  `shop_phone` varchar(128) NOT NULL,
  `shop_supervisor` varchar(128) NOT NULL,
  `shop_bonus` int(11) NOT NULL,
  `shop_target` int(11) NOT NULL,
  `ordered_by` int(11) NOT NULL,
  `shop_cat` enum('MODE','MODIS','NANA') NOT NULL,
  PRIMARY KEY (`shop_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`sup_code`),
  KEY `sup_code` (`sup_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  ADD CONSTRAINT `item_ibfk_4` FOREIGN KEY (`cat_code`) REFERENCES `category` (`cat_code`),
  ADD CONSTRAINT `item_ibfk_5` FOREIGN KEY (`sup_code`) REFERENCES `supplier` (`sup_code`),
  ADD CONSTRAINT `item_ibfk_6` FOREIGN KEY (`op_code`) REFERENCES `operator` (`op_id`);

--
-- Constraints for table `item_distribution`
--
ALTER TABLE `item_distribution`
  ADD CONSTRAINT `item_distribution_ibfk_3` FOREIGN KEY (`item_code`) REFERENCES `item` (`item_code`),
  ADD CONSTRAINT `item_distribution_ibfk_4` FOREIGN KEY (`shop_code`) REFERENCES `shop` (`shop_code`);

--
-- Constraints for table `item_mutasi`
--
ALTER TABLE `item_mutasi`
  ADD CONSTRAINT `item_mutasi_ibfk_1` FOREIGN KEY (`item_code`) REFERENCES `item` (`item_code`);

--
-- Constraints for table `item_retur`
--
ALTER TABLE `item_retur`
  ADD CONSTRAINT `item_retur_ibfk_2` FOREIGN KEY (`op_code`) REFERENCES `operator` (`op_id`),
  ADD CONSTRAINT `item_retur_ibfk_3` FOREIGN KEY (`item_code`) REFERENCES `item` (`item_code`);

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