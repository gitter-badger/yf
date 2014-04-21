<?php

// TODO: implement migrations like in ROR, based on these methods

/**
*/
class yf_db_utils {

	/**
	* Catch missing method call
	*/
	function __call($name, $arguments) {
		trigger_error(__CLASS__.': No method '.$name, E_USER_WARNING);
		return false;
	}

	/**
	* We cleanup object properties when cloning
	*/
	function __clone() {
		foreach ((array)get_object_vars($this) as $k => $v) {
			$this->$k = null;
		}
	}

	/**
	* Will be like this: 
	* db()->utils()->database('geonames')->create();
	* db()->utils()->database('geonames')->drop();
	* db()->utils()->database('geonames')->alter($params);
	* db()->utils()->database('geonames')->rename($new_name);
	*/
	function database($name) {
// TODO
		return _class('db_utils_database', 'classes/db/');
	}

	/**
	* Will be like this: 
	* db()->utils()->database('geonames')->table('geo_city')->create();
	* db()->utils()->database('geonames')->table('geo_city')->drop();
	* db()->utils()->database('geonames')->table('geo_city')->alter($params);
	* db()->utils()->database('geonames')->table('geo_city')->rename($new_name);
	*/
	function table($name) {
// TODO
		return _class('db_utils_table', 'classes/db/');
	}

	/**
	* Will be like this: 
	* db()->utils()->database('geonames')->table('geo_city')->column('name')->add();
	* db()->utils()->database('geonames')->table('geo_city')->column('name')->drop();
	*/
	function column($name) {
// TODO
		return _class('db_utils_column', 'classes/db/');
	}

	/**
	* db()->utils()->database('geonames')->view('test')->create();
	*/
	function view($name) {
// TODO
		return _class('db_utils_view', 'classes/db/');
	}

	/**
	* db()->utils()->database('geonames')->procedure('test')->create();
	*/
	function procedure($name) {
// TODO
		return _class('db_utils_procedure', 'classes/db/');
	}

	/**
	* db()->utils()->database('geonames')->trigger('test')->create();
	*/
	function trigger($name) {
// TODO
		return _class('db_utils_trigger', 'classes/db/');
	}

	/**
	* db()->utils()->database('geonames')->event('test')->create();
	*/
	function event($name) {
// TODO
		return _class('db_utils_event', 'classes/db/');
	}

	/**
	*/
	function list_databases($extra = array()) {
#		$sql = 'SHOW DATABASES';
// TODO
	}

	/**
	*/
	function create_database($name, $extra = array()) {
#		$sql = 'CREATE DATABASE '.$this->db->_es($name).'';
// TODO
	}

	/**
	*/
	function drop_database($name, $extra = array()) {
#		$sql = 'DROP DATABASE '.$this->db->_es($name).'';
// TODO
	}

	/**
	*/
	function alter_database($name, $extra = array()) {
#		$sql = 'ALTER DATABASE '.$this->db->_es($name).'';
// TODO
	}

	/**
	*/
	function rename_database($name, $new_name) {
#		$sql = 'ALTER DATABASE '.$this->db->_es($name).'';
#Script, in bash: for table in mysql -u root -s -N -e "show tables from goedi_drupal"; do mysql -u root -s -N -e "rename table goedi_drupal.$table to iota_drupal.$table"; done;
// TODO
	}

	/**
	*/
	function list_tables($extra = array(), &$error = false) {
		return (array)$this->db->get_2d('show tables');
	}

	/**
	*/
	function table_exists($name, $extra = array(), &$error = false) {
		if (!$name) {
			$error = 'name is empty';
			return false;
		}
		return (bool)in_array($name, (array)$this->list_tables());
	}

	/**
	*/
	function create_table($name, $extra = array(), &$error = false) {
		if (!$name) {
			$error = 'name is empty';
			return false;
		}
		$path = YF_PATH. 'share/db_installer/sql/'.$name.'.sql.php';
// TODO: optionally check if table really exists in database: show_tables and in_array(...)
// TODO: use glob for YF and PROJECT
		if (!file_exists($path)) {
			$error = 'file not exists: '.$path;
			return false;
		}
		include $path;
		if (!$data) {
			$error = 'data is empty';
			return false;
		}
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->db->_fix_table_name($name).'` ('. PHP_EOL. $data. PHP_EOL. ') ENGINE=InnoDB DEFAULT CHARSET=utf8;'. PHP_EOL;
		return $extra['sql'] ? $sql : $this->db->query($sql);
	}

	/**
	*/
	function drop_table($name, $extra = array(), &$error = false) {
		if (!$name) {
			$error = 'name is empty';
			return false;
		}
		$sql = 'DROP TABLE IF EXISTS `'.$this->db->_fix_table_name($name).'`;'.PHP_EOL;
		return $extra['sql'] ? $sql : $this->db->query($sql);
	}

	/**
	*/
	function alter_table($name, $extra = array()) {
#		$sql = 'ALTER TABLE '.$this->db->_es($name).'';
// TODO
	}

	/**
	*/
	function rename_table($name, $new_name) {
#		$sql = 'RENAME TABLE '.$this->db->_es($name).' TO '.$this->db->_es($new_name);
// TODO
	}

	/**
	*/
	function truncate_table($name, $extra = array()) {
// TODO
	}

	/**
	*/
	function check_table($name, $extra = array()) {
// TODO
	}

	/**
	*/
	function optimize_table($name, $extra = array()) {
// TODO
	}

	/**
	*/
	function repair_table($name, $extra = array()) {
// TODO
	}

	/**
	*/
	function list_indexes($table, $extra = array()) {
// TODO
	}

	/**
	*/
	function add_index($table, $fields, $name) {
# CREATE INDEX id_index ON lookup (id)
#		$sql = 'ALTER TABLE '.$this->db->_es($name).'';
// TODO
	}

	/**
	*/
	function drop_index($table, $fields, $name) {
# DROP INDEX index_name ON tbl_name
// TODO
	}

	/**
	*/
	function list_foreign_keys($table, $extra = array()) {
// TODO
	}

	/**
	*/
	function add_foreign_key($table, $fields, $name) {
// TODO
	}

	/**
	*/
	function drop_foreign_key($table, $fields, $name) {
// TODO
	}

	/**
	*/
	function list_columns($table, $extra = array()) {
// TODO
	}

	/**
	*/
	function add_column($table, $name, $data) {
// TODO
	}

	/**
	*/
	function rename_column($table, $name, $data) {
// TODO
	}

	/**
	*/
	function alter_column($table, $name, $data) {
// TODO
	}

	/**
	*/
	function drop_column($table, $name, $data) {
// TODO
	}

	/**
	*/
	function list_views($extra = array()) {
// TODO
	}

	/**
	*/
	function create_view($name, $data) {
# CREATE VIEW test.v AS SELECT * FROM t;
// TODO
	}

	/**
	*/
	function drop_view($name, $data) {
# DROP VIEW view_name
// TODO
	}

	/**
	*/
	function list_procedures($extra = array()) {
// TODO
	}

	/**
	*/
	function create_procedure($name, $data) {
# CREATE PROCEDURE simpleproc (OUT param1 INT)
# BEGIN
#    SELECT COUNT(*) INTO param1 FROM t;
# END//
// TODO
	}

	/**
	*/
	function drop_procedure($name, $data) {
# DROP PROCEDURE name
// TODO
	}

	/**
	*/
	function list_functions($extra = array()) {
// TODO
	}

	/**
	*/
	function create_function($name, $data) {
# CREATE FUNCTION hello (s CHAR(20))
# RETURNS CHAR(50) DETERMINISTIC
# RETURN CONCAT('Hello, ',s,'!');
// TODO
	}

	/**
	*/
	function drop_function($name, $data) {
# DROP FUNCTION name
// TODO
	}

	/**
	*/
	function list_triggers($extra = array()) {
	}

	/**
	*/
	function create_trigger($name, $data) {
# CREATE    [DEFINER = { user | CURRENT_USER }]    TRIGGER trigger_name    trigger_time trigger_event     ON tbl_name FOR EACH ROW    trigger_body
// TODO
	}

	/**
	*/
	function drop_trigger($name, $data) {
# DROP TRIGGER [IF EXISTS] [schema_name.]trigger_name
// TODO
	}

	/**
	*/
	function list_events($extra = array()) {
// TODO
	}

	/**
	*/
	function create_event($name, $data) {
// TODO
	}

	/**
	*/
	function drop_event($name, $data) {
// TODO
	}

	/**
	*/
	function list_users($extra = array()) {
// TODO
	}

	/**
	*/
	function create_user($name, $data) {
// TODO
	}

	/**
	*/
	function drop_user($name, $data) {
// TODO
	}

	/**
	*/
	function split_sql(&$ret, $sql) {
		// do not trim
		$sql			= rtrim($sql, "\n\r");
		$sql_len		= strlen($sql);
		$char			= '';
		$string_start	= '';
		$in_string		= FALSE;
		$nothing	 	= TRUE;
		$time0			= time();
		$is_headers_sent = headers_sent();

		for ($i = 0; $i < $sql_len; ++$i) {
			$char = $sql[$i];
			// We are in a string, check for not escaped end of strings except for
			// backquotes that can't be escaped
			if ($in_string) {
				for (;;) {
					$i		 = strpos($sql, $string_start, $i);
					// No end of string found -> add the current substring to the
					// returned array
					if (!$i) {
						$ret[] = array('query' => $sql, 'empty' => $nothing);
						return TRUE;
					}
					// Backquotes or no backslashes before quotes: it's indeed the
					// end of the string -> exit the loop
					else if ($string_start == '`' || $sql[$i-1] != "\\") {
						$string_start	  = '';
						$in_string		 = FALSE;
						break;
					}
					// one or more Backslashes before the presumed end of string...
					else {
						// ... first checks for escaped backslashes
						$j					 = 2;
						$escaped_backslash	 = FALSE;
						while ($i-$j > 0 && $sql[$i-$j] == "\\") {
							$escaped_backslash = !$escaped_backslash;
							$j++;
						}
						// ... if escaped backslashes: it's really the end of the
						// string -> exit the loop
						if ($escaped_backslash) {
							$string_start  = '';
							$in_string	 = FALSE;
							break;
						}
						// ... else loop
						else {
							$i++;
						}
					}
				}
			}
			// lets skip comments (/*, -- and #)
			else if (($char == '-' && $sql_len > $i + 2 && $sql[$i + 1] == '-' && $sql[$i + 2] <= ' ') || $char == '#' || ($char == '/' && $sql_len > $i + 1 && $sql[$i + 1] == '*')) {
				$i = strpos($sql, $char == '/' ? '*/' : "\n", $i);
				// didn't we hit end of string?
				if ($i === FALSE) {
					break;
				}
				if ($char == '/') $i++;
			}
			// We are not in a string, first check for delimiter...
			else if ($char == ';') {
				// if delimiter found, add the parsed part to the returned array
				$ret[]	  = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
				$nothing	= TRUE;
				$sql		= ltrim(substr($sql, min($i + 1, $sql_len)));
				$sql_len	= strlen($sql);
				if ($sql_len) {
					$i	  = -1;
				} else {
					// The submited statement(s) end(s) here
					return TRUE;
				}
			}
			// ... then check for start of a string,...
			else if (($char == '"') || ($char == '\'') || ($char == '`')) {
				$in_string	= TRUE;
				$nothing	  = FALSE;
				$string_start = $char;
			} elseif ($nothing) {
				$nothing = FALSE;
			}
			// loic1: send a fake header each 30 sec. to bypass browser timeout
			$time1	 = time();
			if ($time1 >= $time0 + 30) {
				$time0 = $time1;
				if (!$is_headers_sent) {
					header('X-YFPing: Pong');
				}
			}
		}
		// add any rest to the returned array
		if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql)) {
			$ret[] = array('query' => $sql, 'empty' => $nothing);
		}
		return TRUE;
	}
}
