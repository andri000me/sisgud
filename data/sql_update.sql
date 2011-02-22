ALTER TABLE `item_mutasi`  ADD `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,  ADD PRIMARY KEY (`id`);
ALTER TABLE `item_distribution`  ADD `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,  ADD PRIMARY KEY (`id`);
ALTER TABLE  `shop` CHANGE  `shop_cat`  `shop_cat` ENUM(  'MODE',  'MODIEST',  'OBRAL',  'RUSAK' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL