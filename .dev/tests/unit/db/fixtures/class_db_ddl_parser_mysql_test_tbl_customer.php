<?php
return array(
	'name' => 'customer',
	'fields' => array(
		'customer_id' => array(
			'name' => 'customer_id',
			'type' => 'smallint',
			'length' => '5',
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => true,
			'primary' => true,
			'unique' => false,
			'values' => NULL,
			'raw' => '`customer_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT',
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
		'create_date' => array(
			'name' => 'create_date',
			'type' => 'datetime',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`create_date` datetime NOT NULL',
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
				'customer_id' => 'customer_id',
			),
			'raw' => 'PRIMARY KEY (`customer_id`)',
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
		'idx_last_name' => array(
			'name' => 'idx_last_name',
			'type' => 'index',
			'columns' => array(
				'last_name' => 'last_name',
			),
			'raw' => 'KEY `idx_last_name` (`last_name`)',
		),
	),
	'foreign_keys' => array(
		'fk_customer_address' => array(
			'name' => 'fk_customer_address',
			'columns' => array(
				'address_id' => 'address_id',
			),
			'ref_table' => 'address',
			'ref_columns' => array(
				'address_id' => 'address_id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => NULL,
			'raw' => 'CONSTRAINT `fk_customer_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`) ON UPDATE CASCADE',
		),
		'fk_customer_store' => array(
			'name' => 'fk_customer_store',
			'columns' => array(
				'store_id' => 'store_id',
			),
			'ref_table' => 'store',
			'ref_columns' => array(
				'store_id' => 'store_id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => NULL,
			'raw' => 'CONSTRAINT `fk_customer_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON UPDATE CASCADE',
		),
	),
	'options' => array(
		'engine' => 'InnoDB',
		'charset' => 'utf8',
	),
);