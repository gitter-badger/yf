<?php
return array(
	'fields' => array(
		'id' => array(
			'name' => 'id',
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
		),
		'code' => array(
			'name' => 'code',
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
		),
		'url' => array(
			'name' => 'url',
			'type' => 'varchar',
			'length' => '100',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'emoticon' => array(
			'name' => 'emoticon',
			'type' => 'varchar',
			'length' => '75',
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => true,
			'default' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'emo_set' => array(
			'name' => 'emo_set',
			'type' => 'tinyint',
			'length' => '3',
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => '1',
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
	),
	'foreign_keys' => array(
	),
	'options' => array(
	),
);