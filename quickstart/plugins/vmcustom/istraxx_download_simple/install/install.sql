CREATE TABLE IF NOT EXISTS `#__virtuemart_product_custom_plg_istraxx_download_simple` (
	`id` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT,
	`virtuemart_order_item_id` INT(1) UNSIGNED NOT NULL,
	`client_ip` CHAR(42),
	`errorcode` TINYINT(1),
	`message` CHAR(255),
	`virtuemart_product_id` INT(1) UNSIGNED NOT NULL,
	`created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11) NOT NULL DEFAULT '0',
	`modified_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` INT(11) NOT NULL DEFAULT '0',
	`locked_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`locked_by` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `virtuemart_order_item_id` (`virtuemart_order_item_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;