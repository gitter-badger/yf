<?php
return array(
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'type' => 'int',
			'length' => 10,
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => NULL,
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => true,
			'primary' => true,
			'unique' => false,
			'values' => NULL,
		),
		'event_id' => array(
			'name' => 'event_id',
			'type' => 'int',
			'length' => 10,
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => NULL,
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'from_email' => array(
			'name' => 'from_email',
			'type' => 'varchar',
			'length' => 254,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => 'NULL',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'from_name' => array(
			'name' => 'from_name',
			'type' => 'varchar',
			'length' => 100,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => 'NULL',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'subject' => array(
			'name' => 'subject',
			'type' => 'varchar',
			'length' => 78,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => 'NULL',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'html' => array(
			'name' => 'html',
			'type' => 'text',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => NULL,
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'active' => array(
			'name' => 'active',
			'type' => 'tinyint',
			'length' => 4,
			'decimals' => NULL,
			'unsigned' => false,
			'nullable' => false,
			'default' => '0',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
	),
	'indexes' => array(
		'PRIMARY' => array(
			'name' => 'PRIMARY',
			'type' => 'primary',
			'columns' => array(
				'id' => 'id',
			),
		),
		'event_id' => array(
			'name' => 'event_id',
			'type' => 'index',
			'columns' => array(
				'event_id' => 'event_id',
			),
		),
		'event_id_active' => array(
			'name' => 'event_id_active',
			'type' => 'index',
			'columns' => array(
				'event_id' => 'event_id',
				'active' => 'active',
			),
		),
	),
	'foreign_keys' => array(
	),
	'options' => array(
	),
);