<?php

define('STORAGE_PATH', __DIR__.'/tests_tmp_storage/');
if (!defined('YF_PATH')) {
	ob_start();
	define('YF_PATH', dirname(dirname(__DIR__)).'/');
	require YF_PATH.'classes/yf_main.class.php';
	new yf_main('user', $no_db_connect = 1, $auto_init_all = 0);
	date_default_timezone_set('Europe/Kiev');
	ini_set('display_errors', 'on');
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
}