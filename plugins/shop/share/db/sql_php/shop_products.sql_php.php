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
		'name' => array(
			'name' => 'name',
			'type' => 'varchar',
			'length' => 255,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => '',
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'url' => array(
			'name' => 'url',
			'type' => 'varchar',
			'length' => 255,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => '',
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'image' => array(
			'name' => 'image',
			'type' => 'tinyint',
			'length' => 1,
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
		'description' => array(
			'name' => 'description',
			'type' => 'text',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'features' => array(
			'name' => 'features',
			'type' => 'text',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'meta_keywords' => array(
			'name' => 'meta_keywords',
			'type' => 'text',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'meta_desc' => array(
			'name' => 'meta_desc',
			'type' => 'text',
			'length' => NULL,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'external_url' => array(
			'name' => 'external_url',
			'type' => 'varchar',
			'length' => 255,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => '',
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'cat_id' => array(
			'name' => 'cat_id',
			'type' => 'int',
			'length' => 11,
			'decimals' => NULL,
			'unsigned' => false,
			'nullable' => false,
			'default' => NULL,
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'model' => array(
			'name' => 'model',
			'type' => 'varchar',
			'length' => 64,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'sku' => array(
			'name' => 'sku',
			'type' => 'varchar',
			'length' => 64,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'quantity' => array(
			'name' => 'quantity',
			'type' => 'int',
			'length' => 10,
			'decimals' => NULL,
			'unsigned' => false,
			'nullable' => false,
			'default' => '100',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'stock_status_id' => array(
			'name' => 'stock_status_id',
			'type' => 'int',
			'length' => 10,
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
		'manufacturer_id' => array(
			'name' => 'manufacturer_id',
			'type' => 'int',
			'length' => 10,
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
		'supplier_id' => array(
			'name' => 'supplier_id',
			'type' => 'int',
			'length' => 10,
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
		'price' => array(
			'name' => 'price',
			'type' => 'decimal',
			'length' => 8,
			'decimals' => '2',
			'unsigned' => false,
			'nullable' => false,
			'default' => '0.00',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'price_promo' => array(
			'name' => 'price_promo',
			'type' => 'decimal',
			'length' => 8,
			'decimals' => '2',
			'unsigned' => false,
			'nullable' => false,
			'default' => '0.00',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'price_partner' => array(
			'name' => 'price_partner',
			'type' => 'decimal',
			'length' => 8,
			'decimals' => '2',
			'unsigned' => false,
			'nullable' => false,
			'default' => '0.00',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'price_raw' => array(
			'name' => 'price_raw',
			'type' => 'decimal',
			'length' => 8,
			'decimals' => '2',
			'unsigned' => false,
			'nullable' => false,
			'default' => '0.00',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'old_price' => array(
			'name' => 'old_price',
			'type' => 'decimal',
			'length' => 8,
			'decimals' => '2',
			'unsigned' => false,
			'nullable' => false,
			'default' => '0.00',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'currency' => array(
			'name' => 'currency',
			'type' => 'tinyint',
			'length' => 3,
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => '0',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'add_date' => array(
			'name' => 'add_date',
			'type' => 'int',
			'length' => 10,
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => '0',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'update_date' => array(
			'name' => 'update_date',
			'type' => 'int',
			'length' => 10,
			'decimals' => NULL,
			'unsigned' => true,
			'nullable' => false,
			'default' => '0',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'last_viewed_date' => array(
			'name' => 'last_viewed_date',
			'type' => 'int',
			'length' => 10,
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
		'featured' => array(
			'name' => 'featured',
			'type' => 'tinyint',
			'length' => 1,
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
		'active' => array(
			'name' => 'active',
			'type' => 'tinyint',
			'length' => 1,
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
		'viewed' => array(
			'name' => 'viewed',
			'type' => 'int',
			'length' => 10,
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
		'sold' => array(
			'name' => 'sold',
			'type' => 'int',
			'length' => 10,
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
		'status' => array(
			'name' => 'status',
			'type' => 'int',
			'length' => 10,
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
		'articul' => array(
			'name' => 'articul',
			'type' => 'varchar',
			'length' => 32,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => '',
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'origin_url' => array(
			'name' => 'origin_url',
			'type' => 'varchar',
			'length' => 255,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
			'charset' => NULL,
			'collate' => NULL,
			'auto_inc' => false,
			'primary' => false,
			'unique' => false,
			'values' => NULL,
		),
		'source' => array(
			'name' => 'source',
			'type' => 'varchar',
			'length' => 255,
			'decimals' => NULL,
			'unsigned' => NULL,
			'nullable' => false,
			'default' => NULL,
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
		'cat_id' => array(
			'name' => 'cat_id',
			'type' => 'index',
			'columns' => array(
				'cat_id' => 'cat_id',
			),
		),
		'active' => array(
			'name' => 'active',
			'type' => 'index',
			'columns' => array(
				'active' => 'active',
			),
		),
		'viewed' => array(
			'name' => 'viewed',
			'type' => 'index',
			'columns' => array(
				'viewed' => 'viewed',
			),
		),
		'sold' => array(
			'name' => 'sold',
			'type' => 'index',
			'columns' => array(
				'sold' => 'sold',
			),
		),
		'active_cat_id' => array(
			'name' => 'active_cat_id',
			'type' => 'index',
			'columns' => array(
				'active' => 'active',
				'cat_id' => 'cat_id',
			),
		),
		'add_date' => array(
			'name' => 'add_date',
			'type' => 'index',
			'columns' => array(
				'add_date' => 'add_date',
			),
		),
		'update_date' => array(
			'name' => 'update_date',
			'type' => 'index',
			'columns' => array(
				'update_date' => 'update_date',
			),
		),
		'manufacturer_id' => array(
			'name' => 'manufacturer_id',
			'type' => 'index',
			'columns' => array(
				'manufacturer_id' => 'manufacturer_id',
			),
		),
		'supplier_id' => array(
			'name' => 'supplier_id',
			'type' => 'index',
			'columns' => array(
				'supplier_id' => 'supplier_id',
			),
		),
	),
	'foreign_keys' => array(
	),
	'options' => array(
	),
);
