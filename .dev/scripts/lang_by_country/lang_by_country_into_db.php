#!/usr/bin/php
<?php

require_once dirname(dirname(__FILE__)).'/scripts_init.php';

require dirname(__FILE__).'/langs_by_country.php';
if (!$data) {
	exit('Error: $data is missing');
}

// TODO

/**
lang char2
country char2
*/

$table = DB_PREFIX. 'geo_lang_to_country';
if ( ! db()->utils()->table_exists($table) || $force) {
	db()->utils()->drop_table($table);
	db()->utils()->create_table($table);
}
db()->insert_safe($table, $data) or print_r(db()->error());

echo 'Trying to get 2 first records: '.PHP_EOL;
print_r(db()->get_all('SELECT * FROM '.$table.' LIMIT 2'));
