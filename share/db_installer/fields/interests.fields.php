<?php
$data = array (
	'fields' => 
	array (
		'user_id' => 
		array (
			'type' => 'int',
			'length' => '10',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '0',
			'auto_inc' => 0,
		),
		'keywords' => 
		array (
			'type' => 'text',
			'length' => '',
			'attrib' => NULL,
			'not_null' => 1,
			'default' => '',
			'auto_inc' => 0,
		),
	),
	'keys' => 
	array (
		'user_id' => 
		array (
			'fields' => 
			array (
				0 => 'user_id',
			),
			'type' => 'primary',
		),
	),
);
