#!/usr/bin/php
<?php

define('YF_PATH', dirname(dirname(dirname(__FILE__))).'/');

// TODO: glob(PROJECT_PATH.'share/db_installer/sql/sys_*.sql.php')
// TODO: glob(PROJECT_PATH.'plugins/*/share/db_installer/sql/sys_*.sql.php')
$paths = array(
	YF_PATH.'share/db_installer/sql/sys_*.sql.php',
	YF_PATH.'priority2/share/db_installer/sql/sys_*.sql.php',
	YF_PATH.'plugins/*/share/db_installer/sql/sys_*.sql.php',
);
$sys_tables = array();
foreach ($paths as $glob) {
	foreach (glob($glob) as $f) {
		$name = substr(basename($f), strlen('sys_'), -strlen('.sql.php'));
		$sys_tables[$name] = $name;
	}
}
ksort($sys_tables);
$content = '<?'.'php'.PHP_EOL.'// Do not edit this file directly, run '.__FILE__.' instead!'.PHP_EOL.'return '.var_export($sys_tables, 1).';'.PHP_EOL;
file_put_contents(YF_PATH. 'share/db_sys_prefix_tables.php', $content);
