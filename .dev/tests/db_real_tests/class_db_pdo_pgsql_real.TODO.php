<?php

require __DIR__.'/class_db_real.Test.php';

/**
 * @requires extension PDO
 * @requires extension pdo_pgsql
 */
class class_db_pdo_pgsql_real_test extends class_db_real_test {
	public static function setUpBeforeClass() {
		self::$DB_DRIVER = 'pdo_pgsql';
		parent::setUpBeforeClass();
	}
}