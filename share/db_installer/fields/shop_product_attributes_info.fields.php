<?php
$data = array (
	'fields' => 
	array (
		'id' => 
		array (
			'type' => 'int',
			'length' => '10',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '0',
			'auto_inc' => 1,
		),
		'category_id' => 
		array (
			'type' => 'int',
			'length' => '11',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '0',
			'auto_inc' => 0,
		),
		'name' => 
		array (
			'type' => 'varchar',
			'length' => '64',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '',
			'auto_inc' => 0,
		),
		'type' => 
		array (
			'type' => 'varchar',
			'length' => '64',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '',
			'auto_inc' => 0,
		),
		'value_list' => 
		array (
			'type' => 'text',
			'length' => '',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '',
			'auto_inc' => 0,
		),
		'default_value' => 
		array (
			'type' => 'text',
			'length' => '',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '',
			'auto_inc' => 0,
		),
		'order' => 
		array (
			'type' => 'int',
			'length' => '10',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '0',
			'auto_inc' => 0,
		),
		'active' => 
		array (
			'type' => 'enum',
			'length' => '\'1\',\'0\'',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '1',
			'auto_inc' => 0,
		),
	),
	'keys' => 
	array (
		'id' => 
		array (
			'fields' => 
			array (
				0 => 'id',
			),
			'type' => 'primary',
		),
	),
);
