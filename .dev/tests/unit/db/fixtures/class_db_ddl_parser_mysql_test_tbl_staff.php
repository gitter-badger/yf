<?php
return array(
	'name' => 'staff',
	'fields' => array(
		'staff_id' => array(
			'name' => 'staff_id',
			'type' => 'tinyint',
			'length' => '3',
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => true,
			'primary' => true,
			'unique' => false,
			'values' => NULL,
			'raw' => '`staff_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT',
		),
		'first_name' => array(
			'name' => 'first_name',
			'type' => 'varchar',
			'length' => '45',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`first_name` varchar(45) NOT NULL',
		),
		'last_name' => array(
			'name' => 'last_name',
			'type' => 'varchar',
			'length' => '45',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`last_name` varchar(45) NOT NULL',
		),
		'address_id' => array(
			'name' => 'address_id',
			'type' => 'smallint',
			'length' => '5',
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`address_id` smallint(5) unsigned NOT NULL',
		),
		'picture' => array(
			'name' => 'picture',
			'type' => 'blob',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`picture` blob',
		),
		'email' => array(
			'name' => 'email',
			'type' => 'varchar',
			'length' => '50',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`email` varchar(50) DEFAULT NULL',
		),
		'store_id' => array(
			'name' => 'store_id',
			'type' => 'tinyint',
			'length' => '3',
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`store_id` tinyint(3) unsigned NOT NULL',
		),
		'active' => array(
			'name' => 'active',
			'type' => 'tinyint',
			'length' => '1',
			'decimals' => NULL,
			'unsigned' => false,
			'nullable' => false,
			'default' => '1',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`active` tinyint(1) NOT NULL DEFAULT \'1\'',
		),
		'username' => array(
			'name' => 'username',
			'type' => 'varchar',
			'length' => '16',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`username` varchar(16) NOT NULL',
		),
		'password' => array(
			'name' => 'password',
			'type' => 'varchar',
			'length' => '40',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`password` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL',
		),
		'last_update' => array(
			'name' => 'last_update',
			'type' => 'timestamp',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => 'CURRENT_TIMESTAMP',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		),
	),
	'indexes' => array(
		'PRIMARY' => array(
			'name' => 'PRIMARY',
			'type' => 'primary',
			'columns' => array(
				'staff_id' => 'staff_id',
			),
			'raw' => 'PRIMARY KEY (`staff_id`)',
		),
		'idx_fk_store_id' => array(
			'name' => 'idx_fk_store_id',
			'type' => 'index',
			'columns' => array(
				'store_id' => 'store_id',
			),
			'raw' => 'KEY `idx_fk_store_id` (`store_id`)',
		),
		'idx_fk_address_id' => array(
			'name' => 'idx_fk_address_id',
			'type' => 'index',
			'columns' => array(
				'address_id' => 'address_id',
			),
			'raw' => 'KEY `idx_fk_address_id` (`address_id`)',
		),
	),
	'foreign_keys' => array(
		'fk_staff_address' => array(
			'name' => 'fk_staff_address',
			'columns' => array(
				'address_id' => 'address_id',
			),
			'ref_table' => 'address',
			'ref_columns' => array(
				'address_id' => 'address_id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => NULL,
			'raw' => 'CONSTRAINT `fk_staff_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`) ON UPDATE CASCADE',
		),
		'fk_staff_store' => array(
			'name' => 'fk_staff_store',
			'columns' => array(
				'store_id' => 'store_id',
			),
			'ref_table' => 'store',
			'ref_columns' => array(
				'store_id' => 'store_id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => NULL,
			'raw' => 'CONSTRAINT `fk_staff_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON UPDATE CASCADE',
		),
	),
	'options' => array(
		'engine' => 'InnoDB',
		'charset' => 'utf8',
	),
);