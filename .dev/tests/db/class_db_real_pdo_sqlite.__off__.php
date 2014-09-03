<?php

require_once __DIR__.'/class_db_real_sqlite.Test.php';

/**
 * @requires extension PDO
 * @requires extension pdo_sqlite
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class class_db_real_pdo_sqlite_test extends class_db_real_sqlite_test {
	public static function setUpBeforeClass() {
		self::$_bak['DB_DRIVER'] = self::$DB_DRIVER;
		self::$DB_DRIVER = 'pdo_sqlite';
		self::_connect();
	}
	public static function tearDownAfterClass() {
		self::$DB_DRIVER = self::$_bak['DB_DRIVER'];
	}
}
