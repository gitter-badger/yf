<?php

require_once __DIR__.'/class_db_real_utils_mysql.Test.php';

/**
 * @requires extension sqlite3
 */
class class_db_real_utils_sqlite_test extends class_db_real_utils_mysql_test {
	public static function setUpBeforeClass() {
		self::$_bak['DB_DRIVER'] = self::$DB_DRIVER;
		self::$DB_DRIVER = 'sqlite';
		self::_connect();
	}
	public static function tearDownAfterClass() {
		$db_file = self::$DB_NAME.'.db';
		if (file_exists($db_file)) {
			unlink($db_file);
		}
		self::$DB_DRIVER = self::$_bak['DB_DRIVER'];
	}
	public static function _connect() {
		self::$DB_NAME = DB_NAME;
		$db_class = load_db_class();
		self::$db = new $db_class(self::$DB_DRIVER);
		self::$db->ALLOW_AUTO_CREATE_DB = true;
		self::$db->NO_AUTO_CONNECT = true;
		self::$db->RECONNECT_NUM_TRIES = 1;
		self::$db->CACHE_TABLE_NAMES = false;
		self::$db->ERROR_AUTO_REPAIR = false;
		self::$db->FIX_DATA_SAFE = true;
		self::$db->_init();
		$res = self::$db->connect(array(
			'host'	=> 'localhost',
			'name'	=> self::$DB_NAME.'.db',
			'user'	=> DB_USER,
			'pswd'	=> DB_PSWD,
			'prefix'=> DB_PREFIX,
			'force' => true,
		));
		return !empty($res) ? true : false;
	}
	public function _need_skip_test($name) {
		if (substr($name, 0, 5) !== 'test_') {
			return false;
		}
		$short = substr($name, 5);
		return in_array($short, array(
			'list_collations',
			'list_charsets',
			'list_databases',
			'database_info',
			'database_exists',
			'create_database',
			'drop_database',
			'alter_database',
			'rename_database',
		));
	}
	public function table_name($name) {
		return $name;
	}
}
