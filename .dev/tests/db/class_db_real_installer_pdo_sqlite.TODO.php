<?php

require_once __DIR__.'/class_db_real_installer_sqlite.Test.php';

/**
 * @requires extension PDO
 * @requires extension pdo_sqlite
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class class_db_real_installer_pdo_sqlite_test extends class_db_real_installer_sqlite_test {
	public static function setUpBeforeClass() {
		self::$DB_DRIVER = 'pdo_sqlite';
		parent::setUpBeforeClass();
	}
}
