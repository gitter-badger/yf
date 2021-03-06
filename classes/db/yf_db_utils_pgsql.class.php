<?php

/**
*/
load('db_utils_driver', 'framework', 'classes/db/');
class yf_db_utils_pgsql extends yf_db_utils_driver {

	/**
	*/
	public function _get_supported_field_types() {
// TODO
		return array(
			'bit','int','real','float','double','decimal','numeric',
			'varchar','char','tinytext','mediumtext','longtext','text',
			'tinyblob','mediumblob','longblob','blob','varbinary','binary',
			'timestamp','datetime','time','date','year',
			'enum','set',
		);
	}

	/**
	*/
	public function _get_unsigned_field_types() {
// TODO
		return array(
			'bit','int','real','double','float','decimal','numeric'
		);
	}

	/**
	*/
	public function _get_supported_table_options() {
// TODO
		return array(
			'conn_limit' => 'CONNECTION LIMIT',
		);
	}

	/**
	*/
	public function list_databases($extra = array()) {
		$sql = 'SELECT datname,datname FROM pg_database WHERE datistemplate = false';
		return $extra['sql'] ? $sql : $this->db->get_2d($sql);
	}

	/**
	*/
	public function database_info($db_name = '', $extra = array(), &$error = false) {
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!strlen($db_name)) {
			$error = 'db_name is empty';
			return false;
		}
		$info = $this->db->get('SELECT * FROM pg_database WHERE datname = '.$this->_escape_val($db_name));
		if (!$info) {
			$error = 'db_name not exists';
			return false;
		}
		return array(
			'name'	=> $db_name,
		);
	}

	/**
	*/
	public function alter_database($db_name, $extra = array(), &$error = false) {
		if (!strlen($db_name)) {
			$error = 'db_name is empty';
			return false;
		}
		$allowed = array(
			'conn_limit' => 'CONNECTION LIMIT',
		);
		foreach ((array)$extra as $k => $v) {
			$v = preg_replace('~[^a-z0-9_]+~i', '', $v);
			if (isset($allowed[$k])) {
				$params[$k] = $allowed[$k].' = '.$v;
			} elseif (in_array($k, $allowed)) {
				$params[$k] = $k.' = '.$v;
			}
		}
		$sql = '';
		if ($params) {
			$sql = 'ALTER DATABASE '.$this->_escape_database_name($db_name).' '.implode(' ', $params);
		}
		return $extra['sql'] ? $sql : $this->db->query($sql);
	}

	/**
	*/
	public function list_tables($db_name = '', $extra = array(), &$error = false) {
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!strlen($db_name)) {
			$error = 'db_name is empty';
			return false;
		}
		$sql = 'SELECT table_name
			FROM "information_schema"."tables"
			WHERE "table_catalog" = '.$this->_escape_val($db_name).'
				AND "table_schema" = \'public\'
			ORDER BY table_schema,table_name';
		$tables = $this->db->get_2d($sql);
		return $tables ? array_combine($tables, $tables) : array();
	}

	/**
	*/
	public function list_tables_details($db_name = '', $extra = array(), &$error = false) {
		foreach((array)$this->list_tables($db_name, $extra, $error) as $table) {
			$tables[$table] = array(
				'name'		=> $table,
				'engine'	=> null,
				'rows'		=> null,
				'data_size'	=> null,
				'collate'	=> null,
			);
		}
		return $tables;
	}

	/**
	*/
	public function table_get_columns($table, $extra = array(), &$error = false) {
		if (!strlen($table)) {
			$error = 'table_name is empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function table_info($table, $extra = array(), &$error = false) {
		$orig_table = $table;
		if (strpos($table, '.') !== false) {
			list($db_name, $table) = explode('.', trim($table));
		}
		if (!strlen($table)) {
			$error = 'table_name is empty';
			return false;
		}
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!strlen($db_name)) {
			$error = 'db_name is empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function list_indexes($table, $extra = array(), &$error = false) {
		if (!$table) {
			$error = 'table_name is empty';
			return false;
		}
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!$db_name) {
			$error = 'db_name is empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function add_index($table, $index_name = '', $fields = array(), $extra = array(), &$error = false) {
		if (!strlen($table)) {
			$error = 'table name is empty';
			return false;
		}
		if (empty($fields)) {
			$error = 'fields are empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function list_foreign_keys($table, $extra = array(), &$error = false) {
		$orig_table = $table;
		if (strpos($table, '.') !== false) {
			list($db_name, $table) = explode('.', trim($table));
		}
		if (!$table) {
			$error = 'table_name is empty';
			return false;
		}
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!$db_name) {
			$error = 'db_name is empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function list_views($db_name = '', $extra = array(), &$error = false) {
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!$db_name) {
			$error = 'db_name is empty';
			return false;
		}

// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function create_view($table, $sql_as, $extra = array(), &$error = false) {
		if (!strlen($table)) {
			$error = 'table is empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function list_triggers($db_name = '', $extra = array(), &$error = false) {
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!$db_name) {
			$error = 'db_name is empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function create_trigger($name, $table, $trigger_time, $trigger_event, $trigger_body, $extra = array(), &$error = false) {
		if (strpos($name, '.') !== false) {
			list($db_name, $name) = explode('.', trim($name));
		}
		if (!$name) {
			$error = 'trigger name is empty';
			return false;
		}
		if (strpos($table, '.') !== false) {
			list($db_name, $table) = explode('.', trim($table));
		}
		if (!$table) {
			$error = 'trigger table is empty';
			return false;
		}
		if (!$db_name) {
			$db_name = $this->db->DB_NAME;
		}
		if (!$db_name) {
			$error = 'db_name is empty';
			return false;
		}
		$supported_trigger_times = array(
			'before',
			'after'
		);
		if (!strlen($trigger_time) || !in_array(strtolower($trigger_time), $supported_trigger_times)) {
			$error = 'trigger time is wrong';
			return false;
		}
		$supported_trigger_events = array(
			'insert',
			'update',
			'delete'
		);
		if (!strlen($trigger_event) || !in_array(strtolower($trigger_event), $supported_trigger_events)) {
			$error = 'trigger event is wrong';
			return false;
		}
		if (!strlen($trigger_body)) {
			$error = 'trigger body is empty';
			return false;
		}
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function _parse_column_type($str, &$error = false) {
// TODO: use code from mysql and adapt it
	}

	/**
	* Create part of SQL for "CREATE TABLE" from array of params
	*/
	public function _compile_create_table($data, $extra = array(), &$error = false) {
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function _escape_database_name($name = '') {
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function _escape_table_name($name = '') {
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function _escape_key($key = '') {
// TODO: use code from mysql and adapt it
	}

	/**
	*/
	public function _escape_val($val = '') {
		$val = trim($val);
		if (!strlen($val)) {
			return '';
		}
// TODO: support for binding params (':field' => $val)
		return is_object($this->db) ? $this->db->escape_val($val) : '\''.addslashes($val).'\'';
	}

	/**
	*/
	public function _escape_fields(array $fields) {
		if (empty($fields)) {
			return $fields;
		}
		$self = __FUNCTION__;
		foreach ((array)$fields as $k => $v) {
			if (is_array($v)) {
				$fields[$k] = $this->$self($v);
			} else {
				$fields[$k] = $this->_escape_key($v);
			}
		}
		return $fields;
	}

	/**
	*/
	public function _es($val = '') {
		if (is_null($val)) {
			return 'NULL';
		}
		$val = trim($val);
		if (!strlen($val)) {
			return '';
		}
// TODO: support for binding params (':field' => $val)
		return is_object($this->db) && method_exists($this->db, '_es') ? $this->db->_es($val) : addslashes($val);
	}
}
