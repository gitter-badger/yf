<?php

load('db_installer', 'framework', 'classes/db/');
class yf_db_installer_mysql extends yf_db_installer {

	/** @var int */
	public $NUM_RETRIES = 5;
	/** @var int */
	public $RETRY_DELAY = 0;
	/** @var string */
	public $DEFAULT_CHARSET = 'utf8';
	/** @var array */
	public $_KNOWN_TABLE_OPTIONS = array(
		'ENGINE',
		'TYPE',
		'AUTO_INCREMENT',
		'AVG_ROW_LENGTH',
		'CHARSET',
		'DEFAULT CHARSET',
		'CHARACTER SET',
		'DEFAULT CHARACTER SET',
		'CHECKSUM',
		'COLLATE',
		'DEFAULT COLLATE',
		'COMMENT',
		'CONNECTION',
		'DATA DIRECTORY',
		'DELAY_KEY_WRITE',
		'INDEX DIRECTORY',
		'INSERT_METHOD',
		'MAX_ROWS',
		'MIN_ROWS',
		'PACK_KEYS',
		'PASSWORD',
		'ROW_FORMAT',
		'UNION',
	);

	/**
	* Framework construct
	*/
	function _init() {
		parent::_init();
		$this->_DEF_TABLE_OPTIONS = array(
			'DEFAULT CHARSET'	=> $this->DEFAULT_CHARSET,
			'ENGINE'			=> 'InnoDB',
		);
	}

	/**
	* Execute original query again safely
	*/
	function _db_query_safe($sql, $db) {
		if (isset($db->_repairs_by_sql[$sql]) && $db->_repairs_by_sql[$sql] >= $this->NUM_RETRIES) {
			return false;
		}
		$db->_repairs_by_sql[$sql]++;
		$result = $db->query($sql);
		if (!$result) {
			if ($this->RETRY_DELAY) {
				sleep($this->RETRY_DELAY);
			}
			$result = $this->_auto_repair_table($sql, $db_error, $db);
		}
		return $result;
	}

	/**
	* Trying to repair given table structure (and possibly data)
	*/
	function _auto_repair_table($sql, $db_error, $db) {
		$sql = trim($sql);
		// #1191 Can't find FULLTEXT index matching the column list
		if (in_array($db_error['code'], array(1191)) && $this->RESTORE_FULLTEXT_INDEX) {

			foreach ((array)conf('fulltext_needed_for') as $_fulltext_field) {
				list($f_table, $f_field) = explode('.', $_fulltext_field);
				if (empty($f_table) || false === strpos($sql, $f_table) || empty($f_field)) {
					continue;
				}
				// Check if such index already exists
				foreach ((array)$db->query_fetch_all('SHOW INDEX FROM '.$f_table.'', 'Key_name') as $k => $v) {
					if ($v['Column_name'] != $f_field) {
						continue;
					}
					if ($v['Index_type'] == 'FULLTEXT') {
						continue 2;
					}
				}
				$db->query('ALTER TABLE '.$f_table.' ADD FULLTEXT KEY '.$f_field.' ('.$f_field.')');
			}
			return $this->_db_query_safe($sql, $db);

		// Errors related to server high load (currently we will handle only SELECTs)
		// #2013 means 'Lost connection to MySQL server during query'
		// #1205 means 'Lock wait timeout expired. Transaction was rolled back' (InnoDB)
		// #1213 means 'Transaction deadlock. You should rerun the transaction.' (InnoDB)
		} elseif (in_array($db_error['code'], array(2013,1205,1213)) && substr($sql, 0, strlen('SELECT ')) == 'SELECT ') {

			return $this->_db_query_safe($sql, $db);

		// #1146 means "Table %s doesn't exist"
		} elseif ($db_error['code'] == 1146) {

			// Try to get table name from error message
			preg_match("#Table [\'][a-z_0-9]+\.([a-z_0-9]+)[\'] doesn\'t exist#ims", $db_error['message'], $m);
			$item_to_repair = trim($m[1]);
			foreach(range(1,3) as $n) {
				$dot_pos = strpos($item_to_repair, '.');
				if (false !== $dot_pos) {
					$item_to_repair = substr($item_to_repair, $dot_pos);
				}
			}
			if (substr($item_to_repair, 0, strlen($db->DB_PREFIX)) == $db->DB_PREFIX) {
				$item_to_repair = substr($item_to_repair, strlen($db->DB_PREFIX));
			}
			if (!empty($item_to_repair)) {
				if (!$this->create_table(str_replace('dbt_', '', $item_to_repair), $db)) {
					return false;
				}
			}
			return $this->_db_query_safe($sql, $db);

		// #1054 means "Unknown column %s"
		} elseif ($db_error['code'] == 1054) {

			// Try to get column name from error message
			preg_match("#Unknown column [\']([a-z_0-9]+)[\'] in#ims", $db_error['message'], $m);
			$item_to_repair = $m[1];
			foreach(range(1,3) as $n) {
				$dot_pos = strpos($item_to_repair, '.');
				if (false !== $dot_pos) {
					$item_to_repair = substr($item_to_repair, $dot_pos);
				}
			}
			// Try to get table name from SQL
			preg_match("#[\s\t]*(UPDATE|FROM|INTO)[\s\t]+[\`]{0,1}([a-z_0-9]+)[\`]{0,1}#ims", $sql, $m2);
			$table_to_repair = $m2[2];
			foreach(range(1,3) as $n) {
				$dot_pos = strpos($table_to_repair, '.');
				if (false !== $dot_pos) {
					$table_to_repair = substr($table_to_repair, $dot_pos);
				}
			}
			if (substr($table_to_repair, 0, strlen($db->DB_PREFIX)) == $db->DB_PREFIX) {
				$table_to_repair = substr($table_to_repair, strlen($db->DB_PREFIX));
			}
			if (!empty($item_to_repair) && !empty($m2[2])) {
				if (!$this->alter_table($table_to_repair, $item_to_repair, $db)) {
					return false;
				}
			}
			if (!empty($installer_result)) {
				return $this->_db_query_safe($sql, $db);
			}
		}
		return true;
	}

	/**
	* Do create table
	*/
	function _do_create_table ($full_table_name, $table_struct, $db) {
		$TABLE_OPTIONS = $this->_DEF_TABLE_OPTIONS;

// TODO: convert into db utils()

		$_options_to_merge = array();
		// Get table options from table structure
		// Example: /** ENGINE=MEMORY **/
		if (preg_match('#\/\*\*([^\*\/]+)\*\*\/$#i', trim($table_struct), $m)) {
			// Cut comment with options from source table structure
			// to prevent misunderstanding
			$table_struct = str_replace($m[0], '', $table_struct);

			$_raw_options = str_replace(array("\r","\n","\t"), array('','',' '), trim($m[1]));

			$_pattern = '/('.implode('|', $this->_KNOWN_TABLE_OPTIONS).")[\s]{0,}=[\s]{0,}([\']{0,1}[^\'\,]+[\']{0,1})/ims";
			if (preg_match_all($_pattern, $_raw_options, $m)) {
				foreach ((array)$m[0] as $_id => $v) {
					$_option_key = strtoupper(trim($m[1][$_id]));
					$_option_val = trim($m[2][$_id]);
					if (!in_array($_option_key, $this->_KNOWN_TABLE_OPTIONS)) {
						continue;
					}
					$_options_to_merge[$_option_key] = $_option_val;
				}
			}
		}
		if (!empty($_options_to_merge)) {
			foreach ((array)$_options_to_merge as $k => $v) {
				$TABLE_OPTIONS[$k] = $v;
			}
		}
		$_tmp = array();
		foreach ((array)$TABLE_OPTIONS as $k => $v) {
			$_tmp[$k] = $k.'='.$v;
		}
		$_table_options_string = '';
		if (!empty($_tmp)) {
			$_table_options_string = ' '.implode(', ', $_tmp);
		}
		$if_not_exists = ($this->USE_SQL_IF_NOT_EXISTS ? 'IF NOT EXISTS ' : '');
		$sql = 'CREATE TABLE '.$if_not_exists. $db->escape_key($full_table_name).' ('.PHP_EOL. $table_struct.')'.$_table_options_string;
		return $db->query($sql);
	}

	/**
	* Do alter table structure
	*/
	function _do_alter_table ($table_name, $column_name, $table_struct, $db) {

// TODO: convert into db utils()

		$struct = $table_struct[$column_name];
		$sql = 'ALTER TABLE '.$db->DB_PREFIX. $table_name. ' ADD '._class('db_ddl_parser_mysql', 'classes/db/')->create_column_line($column_name, $struct);
		return $db->query($sql);
	}
}
