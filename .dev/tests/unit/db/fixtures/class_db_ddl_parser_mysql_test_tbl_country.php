<?php
return array(
	'name' => 'country',
	'fields' => array(
		'country_id' => array(
			'name' => 'country_id',
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
			'raw' => '`country_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT',
		),
		'country' => array(
			'name' => 'country',
			'type' => 'varchar',
			'length' => '50',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
			'raw' => '`country` varchar(50) NOT NULL',
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
				'country_id' => 'country_id',
			),
			'raw' => 'PRIMARY KEY (`country_id`)',
		),
	),
	'foreign_keys' => array(
	),
	'options' => array(
		'engine' => 'InnoDB',
		'charset' => 'utf8',
	),
);