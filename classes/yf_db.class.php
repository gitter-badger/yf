<?php

/**
 * Database abstraction layer
 * 
 * @package		YF
 * @author		YFix Team <yfix.dev@gmail.com>
 * @version		1.0
 */
class yf_db {

	/** @var string Type of database (default) */
	public $DB_TYPE					= "mysql";
	/** @var bool Use tables names caching */
	public $CACHE_TABLE_NAMES		= false;
	/** @var int @conf_skip Number of queries */
	public $NUM_QUERIES				= 0;
	/** @var array Query log array */
	public $QUERY_LOG				= array();
	/** @var array */
	public $QUERY_AFFECTED_ROWS		= array();
	/** @var array */
	public $QUERY_EXEC_TIME			= array();
	/** @var array */
	public $QUERY_BACKTRACE_LOG		= array();
	/** @var int Tables cache lifetime (while developing need to be short) (else need to be very large) */
	public $TABLE_NAMES_CACHE_TTL	= 3600; // 1*3600*24 = 1 day
	/** @var bool Auto-connect on/off */
	public $AUTO_CONNECT			= false;
	/** @var bool Use backtrace in error message */
	public $ERROR_BACKTRACE			= true;
	/** @var bool Use backtrace to get where query was called from (will be used only when DEBUG_MODE is enabled) */
	public $USE_QUERY_BACKTRACE		= true;
	/** @var bool Auto-repairing on error (table not exists) on/off */
	public $ERROR_AUTO_REPAIR		= true;
	/** @var string Folder where databases drivers are stored */
	public $DB_DRIVERS_DIR			= "classes/db/";
	/** @var int Num tries to reconnect (will be useful if db server is overloaded) (Set to "0" for disabling) */
	public $RECONNECT_NUM_TRIES		= 2;
	/** @var int Time to wait between reconnects (in seconds) */
	public $RECONNECT_DELAY			= 1;
	/** @var bool Use logarithmic increase or reconnect time */
	public $RECONNECT_DELAY_LOG_INC	= 1;
	/** @var bool Use locking for reconnects or not */
	public $RECONNECT_USE_LOCKING	= 1;
	/** @var string */
	public $RECONNECT_LOCK_FILE_NAME	= "uploads/db_cannot_connect_[DB_HOST]_[DB_NAME]_[DB_USER]_[DB_PORT].lock";
	/** @var int Time in seconds between unlock reconnect */
	public $RECONNECT_LOCK_TIMEOUT	= 30;
	/** @var string */
	public $CACHE_TABLES_NAMES_FILE	= "core_cache/cache_db_tables_[DB_HOST]_[DB_NAME]_[DB_USER].php";
	/** @var bool Connection required or not (else E_USER_WARNING will be thrown not E_USER_ERROR) */
	public $CONNECTION_REQUIRED		= 0;
	/** @var bool Allow to use shutdown queries or not */
	public $USE_SHUTDOWN_QUERIES	= 1;
	/** @var bool Allow to cache specified queries results */
	public $ALLOW_CACHE_QUERIES		= 0;
	/** @var bool Max number of cached queries */
	public $CACHE_QUERIES_LIMIT		= 100;
	/** @var bool Max number of logged queries (set to 0 to unlimited) */
	public $LOGGED_QUERIES_LIMIT	= 1000;
	/** @var bool Gather affected rows stats (will be used only when DEBUG_MODE is enabled) */
	public $GATHER_AFFECTED_ROWS	= true;
	/** @var bool Store db queries to file */
	public $LOG_ALL_QUERIES			= 0;
	/** @var bool Store db queries to file */
	public $LOG_SLOW_QUERIES		= 0;
	/** @var string Log queries file name */
	public $FILE_NAME_LOG_ALL		= "db_queries.log";
	/** @var string Log queries file name */
	public $FILE_NAME_LOG_SLOW		= "slow_queries.log";
	/** @var float */
	public $SLOW_QUERIES_TIME_LIMIT	= 0.2;
	/** @var bool Add additional engine details to the SQL as comment for later use */
	public $INSTRUMENT_QUERIES		= false;
	/** @var array */
	public $_instrument_items		= array();
	/** @var bool @conf_skip Internal var (default value) */
	public $_tried_to_connect		= false;
	/** @var bool @conf_skip Internal var (default value) */
	public $_connected				= false;
	/** @var mixed @conf_skip Driver instance */
	public $db						= null;
	/** @var string Tables names prefix */
	public $DB_PREFIX				= null;
	/** @var string */
	public $DB_HOST					= "";
	/** @var string */
	public $DB_NAME					= "";
	/** @var string */
	public $DB_USER					= "";
	/** @var string */
	public $DB_PSWD					= "";
	/** @var int */
	public $DB_PORT					= "";
	/** @var string */
	public $DB_CHARSET				= "";
	/** @var string */
	public $DB_SOCKET				= "";
	/** @var bool */
	public $DB_SSL					= false;
	/** @var bool */
	public $DB_PERSIST				= false;
	/** @var bool In case of true - we will try to avoid any data/structure modification queries to not break replication */
	public $DB_REPLICATION_SLAVE	= false;
	/** @var bool Adding SQL_NO_CACHE to SELECT queries: useful to find long running queries */
	public $SQL_NO_CACHE			= false;
	/** @var bool Needed for installation and repairing process */
	public $ALLOW_AUTO_CREATE_DB	= false;
	/** @var array List of tables inside current database */
	public $_PARSED_TABLES			= array();
	/** @var array */
	public $_need_sys_prefix		= array(
		"admin", "admin_groups", "admin_modules", "block_rules", "blocks", "categories", "category_items", "core_servers", "custom_bbcode",
		"custom_replace_tags", "custom_replace_words", "locale_langs", "locale_translate", "locale_vars", "log_admin_auth", "log_admin_auth_fails", "log_auth",
		"log_auth_fails", "log_core_errors", "log_emails", "log_img_resizes", "menu_items", "menus", "online", "settings", "sites", "smilies", "user_groups", "user_modules",
	);

	/**
	* Constructor
	*/
	function __construct($db_type = "", $no_parse_tables = 0, $db_prefix = null, $db_replication_slave = null) {
		$this->_no_parse_tables = $no_parse_tables;
		// Type/driver of database server
		$this->DB_TYPE = !empty($db_type) ? $db_type : DB_TYPE;
		if (!defined("DB_PREFIX") && empty($db_prefix)) {
			define("DB_PREFIX", "");
		}
		$this->DB_PREFIX = !empty($db_prefix) ? $db_prefix : DB_PREFIX;
		// Check if this is primary database connection
		$debug_index = $GLOBALS["DEBUG"]["db_instances"] ? count($GLOBALS["DEBUG"]["db_instances"]) : 0;
		if ($debug_index < 1) {
			$this->IS_PRIMARY_CONNECTION = true;
		} else {
			$this->IS_PRIMARY_CONNECTION = false;
		}
		// Trying to override replication slave setting
		if (isset($db_replication_slave)) {
			$this->DB_REPLICATION_SLAVE = (bool)$db_replication_slave;
		} elseif ($this->IS_PRIMARY_CONNECTION && defined("DB_REPLICATION_SLAVE")) {
			$this->DB_REPLICATION_SLAVE = (bool)DB_REPLICATION_SLAVE;
		}
		// Track db class instances
		$GLOBALS["DEBUG"]["db_instances"][$debug_index] = &$this;
		if (defined('DEBUG_MODE') && DEBUG_MODE) {
			$GLOBALS["DEBUG"]["db_instances_trace"][$debug_index] = $this->_trace();
			$GLOBALS["DEBUG"]["db_instances_trace2"][$debug_index] = $this->_trace_string();
		}
		// Compatibility with standalone mode (when main() instance is not present)
		if (!is_object($GLOBALS['main'])) {
			$GLOBALS['main'] = new StdClass();
		}
	}

	/**
	* Catch missing method call
	*/
	function __call($name, $arguments) {
		trigger_error(__CLASS__.": No method ".$name, E_USER_WARNING);
		return false;
	}

	/**
	* Framework constructor
	*/
	function _init() {
		// Perform auto-connection to db if needed
		if ($this->AUTO_CONNECT || $GLOBALS['main']->type == "admin") {
			$this->connect();
		}
		$this->_set_debug_items();
		// Put all table names into constants
		if (!$this->_no_parse_tables) {
			$this->_parse_tables();
		}
		if ($GLOBALS['main']->CONSOLE_MODE) {
			$this->enable_silent_mode();
		}
		// Set shutdown function
		if ($this->USE_SHUTDOWN_QUERIES) {
			register_shutdown_function(array($this, "_execute_shutdown_queries"));
		}
		if ($this->LOG_ALL_QUERIES || $this->LOG_SLOW_QUERIES) {
			register_shutdown_function(array($this, "_log_queries"));
		}
		// Turn off tables repairing if we are dealing with slave server
		if ($this->DB_REPLICATION_SLAVE) {
			$this->ERROR_AUTO_REPAIR = false;
		}
	}

	/**
	* Connect db driver and then connect to db
	*/
	function connect($db_host = "", $db_user = "", $db_pswd = null, $db_name = "", $force = false, $db_ssl = false, $db_port = "", $db_socket = "", $db_charset = "", $allow_auto_create_db = null) {
		if (!empty($this->_tried_to_connect) && !$force) {
			return $this->_connected;
		}
		$this->_connect_start_time = microtime(true);
		// Use standard main class method (if exists)
		if (is_object($GLOBALS['main']) && $GLOBALS['main']->type) {
			// Get driver class name
			$driver_class_name = $GLOBALS['main']->load_class_file("db_". $this->DB_TYPE, $this->DB_DRIVERS_DIR);
		} else {
			$driver_class_name = "yf_". "db_". $this->DB_TYPE;
			$db_driver_path = (defined("YF_PATH") ? YF_PATH: INCLUDE_PATH). $this->DB_DRIVERS_DIR. $driver_class_name. ".class.php";
			include_once ($db_driver_path);
		}
		$this->DB_HOST		= !empty($db_host)		? $db_host		: DB_HOST;
		$this->DB_USER		= !empty($db_user)		? $db_user		: DB_USER;
		$this->DB_PSWD		= !is_null($db_pswd)	? $db_pswd		: (defined("DB_PSWD") ? DB_PSWD : "");
		$this->DB_NAME		= !empty($db_name)		? $db_name		: DB_NAME;
		$this->DB_PORT		= !empty($db_port)		? $db_port		: (defined("DB_PORT") ? DB_PORT : "");
		$this->DB_SOCKET	= !empty($db_socket)	? $db_socket	: (defined("DB_SOCKET") ? DB_SOCKET : "");
		$this->DB_SSL		= !empty($db_ssl)		? $db_ssl		: (defined("DB_SSL") ? DB_SSL : false);
		$this->DB_CHARSET	= !empty($db_charset)	? $db_charset	: (defined("DB_CHARSET") ? DB_CHARSET : "");
		$this->ALLOW_AUTO_CREATE_DB	= !is_null($allow_auto_create_db) ? $allow_auto_create_db : $this->ALLOW_AUTO_CREATE_DB;
		// Create new instanse of the driver class
		if (!empty($driver_class_name) && class_exists($driver_class_name) && !is_object($this->db)) {
			// Set lock file
			if ($this->RECONNECT_USE_LOCKING) {
				$lock_file = $this->_get_reconnect_lock_path($this->DB_HOST, $this->DB_USER, $this->DB_NAME, $this->DB_PORT);
				clearstatcache();
				if (file_exists($lock_file)) {
					// Timed out lock file
					if ((time() - filemtime($lock_file)) > $this->RECONNECT_LOCK_TIMEOUT) {
						unlink($lock_file);
					} else {
						return false;
					}
				}
			}
			// Try to connect several times
			for ($i = 1; $i <= $this->RECONNECT_NUM_TRIES; $i++) {
				$this->db = new $driver_class_name($this->DB_HOST, $this->DB_USER, $this->DB_PSWD, $this->DB_NAME, $this->DB_PERSIST, $this->DB_SSL, $this->DB_PORT, $this->DB_SOCKET, $this->DB_CHARSET, $this->ALLOW_AUTO_CREATE_DB);
				if (!is_object($this->db) || !($this->db instanceof yf_db_driver)) {
					trigger_error("DB: Wrong driver", $this->CONNECTION_REQUIRED ? E_USER_ERROR : E_USER_WARNING);
					break;
				}
				// Stop after success
				if (!empty($this->db->db_connect_id)) {
					break;
				// Wait some time and try again (use logarithmic increase)
				} else {
					$multiplier = 1;
					if ($this->RECONNECT_DELAY_LOG_INC) {
						$multiplier = $i + ($this->RECONNECT_DELAY <= 1 ? 1 : 0);
					}
					sleep($this->RECONNECT_DELAY * $multiplier);
				}
			}
			// Put lock file
			if ($this->RECONNECT_USE_LOCKING && !$this->db->db_connect_id) {
				file_put_contents($lock_file, gmdate("Y-m-d H:i:s")." GMT");
			}
		}
		$this->_tried_to_connect = true;
		// Stop execution If connection has failed
		if (!$this->db->db_connect_id) {
			trigger_error("DB: ERROR CONNECTING TO DATABASE", $this->CONNECTION_REQUIRED ? E_USER_ERROR : E_USER_WARNING);
		} else {
			$this->db->SQL_NO_CACHE = $this->SQL_NO_CACHE;
			$this->_connected = true;
		}
		$this->_connection_time += (microtime(true) - $this->_connect_start_time);
		return $this->_connected;
	}

	/**
	* Close connection to db
	*/
	function close() {
		$this->_connected = false;
		return $this->db->close();
	}

	/**
	* Function return resource ID of the query
	*/
	function &query($sql) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		if (!is_object($this->db)) {
			return false;
		}
		$this->NUM_QUERIES++;
		if (DEBUG_MODE) {
			$this->_query_time_start = microtime(true);
			if ($this->SQL_NO_CACHE && false !== strpos($this->DB_TYPE, "mysql")) {
				$q = strtoupper(substr(ltrim($sql), 0, 100));
				if (substr($q, 0, 6) == "SELECT" && false === strpos($q, "SQL_NO_CACHE")) {
					$sql = preg_replace('/^[\s\t]*(SELECT)[\s\t]+/ims', '$1 SQL_NO_CACHE ', $sql);
				}
			}
		}
		if ($this->INSTRUMENT_QUERIES) {
			$sql = $this->_instrument_query($sql);
		}
		$query_allowed = true;
		if ($this->DB_REPLICATION_SLAVE && preg_match('/^[\s\t]*(UPDATE|INSERT|DELETE|ALTER|CREATE|RENAME|TRUNCATE)[\s\t]+/ims', $sql)) {
			$query_allowed = false;
		}
		if ($query_allowed) {
			$result = $this->db->query($sql);
		}
		$db_error = false;
		if (!$result && $query_allowed) {
			$db_error = $this->db->error();
		}
		if (!$result && $query_allowed && $db_error) {
			// Try to reconnect if we see some these errors: http://dev.mysql.com/doc/refman/5.0/en/error-messages-client.html
			$error_codes_to_reconnect = array(
				2000, 2002, 2003, 2004, 2005, 2006, 2008, 2012, 2013, 2020, 2027, 2055
			);
			if (false !== strpos($this->DB_TYPE, "mysql") && in_array($db_error["code"], $error_codes_to_reconnect)) {
				$this->db = null;
				$reconnect_successful = $this->connect($this->DB_HOST, $this->DB_USER, $this->DB_PSWD, $this->DB_NAME, true, $this->DB_SSL, $this->DB_PORT, $this->DB_SOCKET, $this->DB_CHARSET, $this->ALLOW_AUTO_CREATE_DB);
				if ($reconnect_successful) {
					$result = $this->db->query($sql);
				}
			}
		}
		if (!$result && $query_allowed && $db_error && $this->ERROR_AUTO_REPAIR) {
			$result = $this->_repair_table($sql, $db_error);
		}
		if (!$result && $db_error) {
			$this->_query_show_error($sql, $db_error, (DEBUG_MODE && $this->ERROR_BACKTRACE) ? $this->_trace() : array());
		}
		if (DEBUG_MODE || $this->LOG_ALL_QUERIES || $this->LOG_SLOW_QUERIES) {
			$this->_query_log($sql, $this->USE_QUERY_BACKTRACE ? $this->_trace() : array());
		}
		return $result;
	}

	/**
	*/
	function _query_show_error($sql, $db_error, $_trace = array()) {
		$old_db_error = $db_error;
		$db_error = $this->db->error();
		if (empty($db_error) || empty($db_error["message"])) {
			$db_error = $old_db_error;
		}
		if ($_trace) {
			$back_step	= $_trace[1]["class"] != "db" ? 1 : 2;
			$trace_text	= " (<i> in \"".$_trace[$back_step]["file"]."\" on line ".$_trace[$back_step]["line"]."</i>) ";
		}
		$msg = "DB: QUERY ERROR: ".$sql."<br />\n<b>CAUSE</b>: ".$db_error["message"]
			. ($db_error["code"] ? " (code:".$db_error["code"].")" : "")
			. ($db_error["offset"] ? " (offset:".$db_error["offset"].")" : "")
			. $trace_text."<br />\n";
		trigger_error($msg, E_USER_WARNING);
	}

	/**
	*/
	function _query_log($sql, $_trace = array()) {
		$_log_allowed = false;
		if (DEBUG_MODE || $this->LOG_ALL_QUERIES || $this->LOG_SLOW_QUERIES) {
			$_log_allowed = true;
		}
		if (!$_log_allowed) {
			return false;
		}
		// Save memory on high number of query log entries
		if ($this->LOGGED_QUERIES_LIMIT && count($this->QUERY_LOG) >= $this->LOGGED_QUERIES_LIMIT) {
			$_log_allowed = false;
		}
		if (!$_log_allowed) {
			return false;
		}
		$this->QUERY_LOG[] = $sql;
		$this->QUERY_EXEC_TIME[] = (float)microtime(true) - (float)$this->_query_time_start;
		if (is_array($_trace) && !empty($_trace)) {
// TODO: convert to much more powerful method   main()->trace_string()
			$_cur_trace_id = 0;
			$_db_class_name1 = "db.class.php";
			// Try to skip back trace for "query" function
			for ($i = 0; $i < 5; $i++) {
				if (in_array(strtolower($_trace[$i]["class"]), array("db", YF_PREFIX."db")) 
					&& in_array(strtolower($_trace[$i]["function"]), array("query", "unbuffered_query", "insert", "update", "replace")) 
					&& (substr($_trace[$i]["file"], -strlen($_db_class_name1)) == $_db_class_name1)
				) {
					continue;
				}
				$_cur_trace_id = $i;
				break;
			}
			$_trace[$_cur_trace_id]["inside_method"] = (!empty($_trace[$_cur_trace_id + 1]["class"]) ? $_trace[$_cur_trace_id + 1]["class"]. $_trace[$_cur_trace_id + 1]["type"] : ""). $_trace[$_cur_trace_id + 1]["function"];
			$this->QUERY_BACKTRACE_LOG[] = $_trace[$_cur_trace_id];
			$this->QUERY_BACKTRACE_LOG2[] = $this->_trace_string();
		}
		if ($this->GATHER_AFFECTED_ROWS) {
			$_sql_type = strtoupper(rtrim(substr(ltrim($sql), 0, 7)));
			if (in_array($_sql_type, array("INSERT", "UPDATE", "REPLACE", "DELETE"))) {
				$this->QUERY_AFFECTED_ROWS[$sql] = $this->affected_rows();
			} elseif ($_sql_type == "SELECT") {
				$this->QUERY_AFFECTED_ROWS[$sql] = $this->num_rows($result);
			}
		}
	}

	/**
	* Function execute unbuffered query
	*/
	function unbuffered_query($sql) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		$this->query($sql);
	}

	/**
	*/
	function multi_query($sql = array()) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		if (!is_object($this->db)) {
			return false;
		}
		if (!$this->db->HAS_MULTI_QUERY) {
			$result = array();
			foreach ((array)$sql as $k => $_sql) {
				$result[$k] = $this->query($_sql);
			}
			return $result;
		} else {
			return $this->db->multi_query($sql);
		}
	}

	/**
	* Insert array of values into table
	*/
	function insert($table, $data, $only_sql = false, $replace = false, $ignore = false) {
		if ($this->DB_REPLICATION_SLAVE && !$only_sql) {
			return false;
		}
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		if (!is_object($this->db)) {
			return false;
		}
		$table = $this->_fix_table_name($table);
		if (!strlen($table)) {
			return false;
		}
		if (is_string($replace)) {
			$replace = false;
		}
		$values_array = array();
		// Try to check if array is two-dimensional
		foreach ((array)$data as $cur_row) {
			$is_multiple = is_array($cur_row) ? 1 : 0;
			break;
		}
		if ($is_multiple) {
			foreach ((array)$data as $cur_row) {
				if (empty($cols)) {
					$cols	= array_keys($cur_row);
				}
				$cur_values = array_values($cur_row);
				foreach ((array)$cur_values as $k => $v) {
					$cur_values[$k] = $this->enclose_field_value($v);
				}
				$values_array[] = "(".implode(', ', $cur_values)."\n)";
			}
		} else {
			$cols	= array_keys($data);
			$values = array_values($data);
			foreach ((array)$values as $k => $v) {
				$values[$k] = $this->enclose_field_value($v);
			}
			$values_array[] = "(".implode(', ', $values)."\n)";
		}
		foreach ((array)$cols as $k => $v) {
			$cols[$k] = $this->enclose_field_name($v);
		}
		$sql = ($replace ? "REPLACE" : "INSERT"). ($ignore ? " IGNORE" : "")." INTO ".
			$this->enclose_field_name($table).
			" \n(".implode(', ', $cols).") VALUES \n".
			implode(", ", $values_array);
		if ($only_sql) {
			return $sql;
		}
		return $this->query($sql);
	}

	/**
	* Alias, forced to add INSERT IGNORE
	*/
	function insert_ignore($table, $data, $only_sql = false, $replace = false) {
		return $this->insert($table, $data, $only_sql, $replace, $ignore = true);
	}

	/**
	* Replace array of values into table
	*/
	function replace($table, $data, $only_sql = false) {
		return $this->insert($table, $data, $only_sql, true);
	}

	/**
	* Update table with given values
	*/
	function update($table, $data, $where, $only_sql = false) {
		if ($this->DB_REPLICATION_SLAVE && !$only_sql) {
			return false;
		}
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		if (!is_object($this->db)) {
			return false;
		}
		$table = $this->_fix_table_name($table);
		if (empty($table) || empty($data) || empty($where)) {
			return false;
		}
		// $where contains numeric id
		if (is_numeric($where)) {
			$where = "id=".intval($where);
		}
		$tmp_data = array();
		foreach ((array)$data as $k => $v) {
			if (empty($k)) {
				continue;
			}
			$tmp_data[$k] = $this->enclose_field_name($k)." = ".$this->enclose_field_value($v);
		}
		$sql = "UPDATE ".$this->enclose_field_name($table)
			." SET ".implode(', ', $tmp_data)
			.(!empty($where) ? " WHERE ".$where : '');
		if ($only_sql) {
			return $sql;
		}
		return $this->query($sql);
	}

	/**
	* Execute database query and fetch result as assoc array (for queries that returns only 1 row)
	*/
	function query_fetch($query, $use_cache = true, $assoc = true) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		$CACHE_CONTAINER = &$this->_db_results_cache;
		if ($use_cache && $this->ALLOW_CACHE_QUERIES && isset($CACHE_CONTAINER[$query])) {
			return $CACHE_CONTAINER[$query];
		}
		$Q = $this->query($query);
		if ($assoc) {
			$data = $this->db->fetch_assoc($Q);
		} else {
			$data = $this->db->fetch_row($Q);
		}
		$this->free_result($Q);
		// Store result in variable cache
		if ($use_cache && $this->ALLOW_CACHE_QUERIES && !isset($CACHE_CONTAINER[$query])) {
			$CACHE_CONTAINER[$query] = $data;
			// Permanently turn off queries cache (and free some memory) if case of limit reached
			if ($this->CACHE_QUERIES_LIMIT && count($CACHE_CONTAINER) > $this->CACHE_QUERIES_LIMIT) {
				$this->ALLOW_CACHE_QUERIES	= false;
				$CACHE_CONTAINER			= null;
			}
		}
		return $data;
	}

	/**
	* Alias
	*/
	function get($query, $use_cache = true) {
		return $this->query_fetch($query, $use_cache, true);
	}

	/**
	* Alias, return first value
	*/
	function get_one($query, $use_cache = true) {
		$result = $this->query_fetch($query, $use_cache, true);
		// Foreach needed here as we do not know first key name
		foreach (array_keys($result) as $key) {
			return $result[$key];
		}
		return false;
	}

	/**
	* Alias, return 2d array, where key is first field and value is the second, 
	* Example: "SELECT id, name FROM p_static_pages" => array('1' => 'page1', '2' => 'page2')
	* Example: "SELECT name FROM p_static_pages" => array('page1', 'page2')
	*/
	function get_2d($query, $use_cache = true) {
		$result = $this->query_fetch_all($query, $use_cache, true);
		// Get 1st and 2nd keys from first sub-array
		if (is_array($result) && $result) {
			$keys = array_keys(current($result));
		}
		if (!$keys) {
			return false;
		}
		$out = array();
		foreach ((array)$result as $id => $data) {
			if (isset($keys[1])) {
				$out[$data[$keys[0]]] = $data[$keys[1]];
			} else {
				$out[] = $data[$keys[0]];
			}
		}
		return $out;
	}

	/**
	* Generate multi-level (up to 4) array from incoming query, useful to save some code on generating this often.
	* Example: get_deep_array("SELECT department_id, user_id, name FROM t_personal", 2)  => 
	*	[ 25 => [ 654 => [
	*		'department_id' => 25,
	*		'user_id' => 654,
	*		'name' => 'Peter',
	*	]]]
	*/
	function get_deep_array($query, $levels = 1, $use_cache = true) {
		$out = array();
		$q = $this->query($sql);
		$row = $this->fetch_assoc($q);
		$k = array_keys( $row );
		do {
			if ($levels == 1) {
				@$a[ $row[$k[0]] ] = $row;
			} elseif ($levels == 2) {
				@$a[ $row[$k[0]] ][ $row[$k[1]] ] = $row;
			} elseif ($levels == 3) {
				@$a[ $row[$k[0]] ][ $row[$k[1]] ][ $row[$k[2]] ] = $row;
			} elseif ($levels == 4) {
				@$a[ $row[$k[0]] ][ $row[$k[1]] ][ $row[$k[2]] ][ $row[$k[3]] ] = $row;
			}
		} while ($row = $this->fetch_assoc($result));
		return $out;
	}

	/**
	* Alias
	*/
	function query_fetch_assoc($query, $use_cache = true) {
		return $this->query_fetch($query, $use_cache, true);
	}

	/**
	* Same as "query_fetch" except fetching as row not assoc
	*/
	function query_fetch_row($query, $use_cache = true) {
		return $this->query_fetch($query, $use_cache, false);
	}

	/**
	* Alias
	*/
	function get_all($query, $key_name = null, $use_cache = true) {
		return $this->query_fetch_all($query, $key_name, $use_cache);
	}

	/**
	* Execute database query and fetch result into assotiative array
	*/
	function query_fetch_all($query, $key_name = null, $use_cache = true) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		$CACHE_CONTAINER = &$this->_db_results_cache;
		if ($use_cache && $this->ALLOW_CACHE_QUERIES && isset($CACHE_CONTAINER[$query])) {
			return $CACHE_CONTAINER[$query];
		}
		$data = null;
		$Q = $this->query($query);
		// If $key_name is specified - then save to $data using it as key
		while ($A = $this->db->fetch_assoc($Q)) {
			if ($key_name != null && $key_name != "-1") {
				$data[$A[$key_name]] = $A;
			} elseif (isset($A["id"]) && $key_name != "-1") {
				$data[$A["id"]] = $A;
			} else {
				$data[] = $A;
			}
		}
		$this->free_result($Q);
		// Store result in variable cache
		if ($use_cache && $this->ALLOW_CACHE_QUERIES && !isset($CACHE_CONTAINER[$query])) {
			$CACHE_CONTAINER[$query] = $data;
			// Permanently turn off queries cache (and free some memory) if case of limit reached
			if ($this->CACHE_QUERIES_LIMIT && count($CACHE_CONTAINER) > $this->CACHE_QUERIES_LIMIT) {
				$this->ALLOW_CACHE_QUERIES	= false;
				$CACHE_CONTAINER			= null;
			}
		}
		return $data;
	}

	/**
	* Execute database query and fetch result as assoc array (for queries that returns only 1 row)
	*/
	function query_fetch_cached($query, $cache_ttl = 600) {
		$cache_key = "SQL_".__FUNCTION__."_".$this->DB_HOST."_".$this->DB_NAME."_".abs(crc32($query));
		$data = cache_get($cache_key);
		if (!empty($data)) {
			return $data;
		}
		$data = $this->query_fetch($query);
		cache_set($cache_key, $data);
		return $data;
	}

	/**
	* Alias with core cache
	*/
	function query_fetch_all_cached($query, $key_name = null, $cache_ttl = 600) {
		$cache_key = "SQL_".__FUNCTION__."_".$this->DB_HOST."_".$this->DB_NAME."_".abs(crc32($query));
		$data = cache_get($cache_key);
		if (!empty($data)) {
			return $data;
		}
		$data = $this->query_fetch_all($query, $key_name);
		cache_set($cache_key, $data);
		return $data;
	}

	/**
	* Alias
	*/
	function get_cached($query, $cache_ttl = 600) {
		return $this->query_fetch_cached($query, $cache_ttl);
	}

	/**
	* Alias
	*/
	function get_all_cached($query, $key_name = null, $cache_ttl = 600) {
		return $this->query_fetch_all_cached($query, $key_name, $cache_ttl);
	}

	/**
	* Execute database query and the calculate number of rows
	*/
	function query_num_rows($query) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		$Q = $this->query($query);
		$result = $this->db->num_rows($Q);
		$this->free_result($Q);
		return $result;
	}

	/**
	* Function return fetched array with both text and numeric indexes
	*/
	function fetch_array($result) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->fetch_array($result);
	}

	/**
	* Function return fetched array with text indexes
	*/
	function fetch_assoc($result) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->fetch_assoc($result);
	}

	/**
	* Alias
	*/
	function fetch($result) {
		return $this->fetch_assoc($result);
	}

	/**
	* Function return fetched array with numeric indexes
	*/
	function fetch_row($result) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->fetch_row($result);
	}

	/**
	* Function return number of rows in the query
	*/
	function num_rows($result) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->num_rows($result);
	}

	/**
	* Function escapes characters for using in query
	*/
	function real_escape_string($string) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		// Helper method for passing here whole arrays as param
		if (is_array($string)) {
			foreach ((array)$string as $k => $v) {
				$string[$k] = $this->real_escape_string($v);
			}
			return $string;
		}
		return $this->db->real_escape_string($string);
	}

	/**
	* Alias
	*/
	function escape_string($string) {
		return $this->real_escape_string ($string);
	}

	/**
	* Alias
	*/
	function escape($string) {
		return $this->real_escape_string($string);
	}

	/**
	* Alias
	*/
	function es($string) {
		return $this->real_escape_string($string);
	}

	/**
	* Real escape string with _filter_text (quick alias)
	*/
	function esf($string, $length = 0) {
		$string = _filter_text($string, $length);
		return $this->real_escape_string($string);
	}

	/**
	* Begin a transaction, or if a transaction has already started, continue it
	*/
	function begin() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->begin();
	}

	/**
	* End a transaction, or decrement the nest level if transactions are nested
	*/
	function commit() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->commit();
	}

	/**
	* Rollback a transaction
	*/
	function rollback() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->rollback();
	}

	/**
	* Return meta columns info for selected table
	*/
	function meta_columns($table, $KEYS_NUMERIC = false, $FULL_INFO = false) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->meta_columns($table, $KEYS_NUMERIC, $FULL_INFO);
	}

	/**
	* Return meta tables info
	*/
	function meta_tables() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->meta_tables($this->DB_PREFIX);
	}

	/**
	* Free result assosiated with a given query resource
	*/
	function free_result($result) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->free_result($result);
	}

	/**
	* Return database error
	*/
	function error() {
		if (!is_object($this->db)) {
			return false;
		}
		return $this->db->error();
	}

	/**
	* Return last insert id
	*/
	function insert_id() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->insert_id();
	}

	/**
	* Get number of affected rows
	*/
	function affected_rows() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->affected_rows();
	}

	/**
	* Enclose field names
	*/
	function enclose_field_name($data) {
		if (!is_object($this->db)) {
			return false;
		}
		if (is_array($data)) {
			$func = __FUNCTION__;
			foreach ((array)$data as $k => $v) {
				$data[$k] = $this->$func($v);
			}
			return $data;
		}
		return $this->db->enclose_field_name($data);
	}

	/**
	* Enclose field values
	*/
	function enclose_field_value($data) {
		if (!is_object($this->db)) {
			return false;
		}
		if (is_array($data)) {
			$func = __FUNCTION__;
			foreach ((array)$data as $k => $v) {
				$data[$k] = $this->$func($v);
			}
			return $data;
		}
		return $this->db->enclose_field_value($data);
	}

	/**
	*/
	function get_server_version() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->get_server_version();
	}

	/**
	*/
	function get_host_info() {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		return $this->db->get_host_info();
	}

	/**
	* "Silent" mode (logging off, tracing off, debugging off)
	*/
	function enable_silent_mode() {
 		$this->ALLOW_CACHE_QUERIES	= false;
		$this->ALLOW_CACHE_QUERIES	= false;
		$this->GATHER_AFFECTED_ROWS	= false;
		$this->USE_SHUTDOWN_QUERIES = false;
		$this->LOG_ALL_QUERIES		= false;
		$this->LOG_SLOW_QUERIES		= false;
		$this->USE_QUERY_BACKTRACE	= false;
		$this->ERROR_BACKTRACE		= false;
		$this->LOGGED_QUERIES_LIMIT = 1;
	}

	/**
	* Add query to shutdown array
	*/
	function _add_shutdown_query($sql = "") {
		if (empty($sql)) {
			return false;
		}
		// If shutdown execution is disabled - then execute this query immediatelly
		if (!$this->USE_SHUTDOWN_QUERIES) {
			return $this->query($sql);
		} else {
			// Add query to the array
			$this->_SHUTDOWN_QUERIES[] = $sql;
		}
		return true;
	}

	/**
	* Execute shutdown queries
	*/
	function _execute_shutdown_queries() {
		// Restore startup working directory
		@chdir($GLOBALS['main']->_CWD);

		if (!$this->USE_SHUTDOWN_QUERIES || $this->_shutdown_executed) {
			return false;
		}
		foreach ((array)$this->_SHUTDOWN_QUERIES as $sql) {
			$this->query($sql);
		}
		// Prevent executing this method more than once
		$this->_shutdown_executed = true;
	}

	/**
	* Create unique temporary table name
	*/
	function _get_unique_tmp_table_name () {
		return $this->DB_PREFIX."tmp__".substr(abs(crc32(rand().microtime(true))), 0, 8);
	}

	/**
	* Do Log
	*/
	function _log_queries () {
		// Restore startup working directory
		@chdir($GLOBALS['main']->_CWD);

		if (!isset($this->_queries_logged)) {
			$this->_queries_logged = true;
		} else {
			return false;
		}
		$GLOBALS['main']->call_class_method("logs", "classes/", "store_db_queries_log");
	}

	/**
	* Function return resource ID of the query
	*/
	function _parse_tables() {
		if ($this->_already_parsed_tables) {
			return true;
		}
		// Do connect (if not done yet)
		if (!is_object($this->db)) {
			$this->connect();
		}
		$included = false;
		$use_defines = true;
		// Check for first/primary connection
		if (count($GLOBALS["_debug_db_instances"]) > 1) {
			$use_defines = false;
		}
		$cache_path = $this->_get_cache_tables_names_path();
		// Get table names from cache
// TODO: use cache() class/function if available
		if ($use_defines && !empty($this->CACHE_TABLE_NAMES) && file_exists($cache_path)) {
			// Refresh cache file after 1 day
			$last_modified = filemtime($cache_path);
			if ($last_modified < (time() - $this->TABLE_NAMES_CACHE_TTL)) {
				unlink($cache_path);
			} else {
				include($cache_path);
				$included = true;
			}
		}
		if (empty($included)) {
			// Do get current database tables array
			$tmp_tables = $this->meta_tables();
			// Clean up tables from system prefixes
			$this->_PARSED_TABLES = array();
			foreach ((array)$tmp_tables as $table_name) {
				$short_name = substr(str_replace("sys_","",$table_name), strlen($this->DB_PREFIX));
				$this->_PARSED_TABLES[$short_name] = $table_name;
				$tables["dbt_".$short_name] = $table_name;
			}
			ksort($this->_PARSED_TABLES);
			// If non-empty - apply override of table names (useful for pre-release or development on same database as production)
			if (isset($GLOBALS['_OVERRIDES']['DB_TABLE_NAMES'][$this->DB_NAME])) {
				$override_table_names = $GLOBALS['_OVERRIDES']['DB_TABLE_NAMES'][$this->DB_NAME];
				foreach ((array)$override_table_names as $cur_name => $use_name) {
					$this->_PARSED_TABLES[$cur_name] = $use_name;
				}
			}
			if ($use_defines) {
				foreach ((array)$tables as $k => $v) {
					define($k, $v);
				}
			}
			// Put tables names to cache
			if ($use_defines && !empty($this->CACHE_TABLE_NAMES)) {
				// Do create folder for cache (if not exists)
				if (!file_exists(dirname($cache_path))) {
					mkdir(dirname($cache_path), 0777);
				}
				foreach ((array)$tables as $k => $v) {
					$file_text .= "define('".$k."','".$v."');\n";
				}
				file_put_contents($cache_path, "<?php\n".$file_text."?>");
			}

		}
		$this->_already_parsed_tables = true;
	}

	/**
	* Get real table name from its short variant
	*/
	function _real_name ($name) {
		if (!$this->_already_parsed_tables) {
			$this->_parse_tables();
		}
		return isset($this->_PARSED_TABLES[$name]) ? $this->_PARSED_TABLES[$name] : $this->DB_PREFIX. (in_array($name, $this->_need_sys_prefix) ? "sys_" : ""). $name;
	}

	/**
	* Get reconnect lock file name
	*/
	function _get_reconnect_lock_path($db_host = "", $db_user = "", $db_name = "", $db_port = "") {
		$params = array(
			"[DB_HOST]"	=> $db_host ? $db_host : $this->DB_HOST,
			"[DB_NAME]"	=> $db_name ? $db_name : $this->DB_NAME,
			"[DB_USER]"	=> $db_user ? $db_user : $this->DB_USER,
			"[DB_PORT]"	=> $db_port ? $db_port : $this->DB_PORT,
		);
		$file_name = str_replace(array_keys($params), array_values($params), $this->RECONNECT_LOCK_FILE_NAME);
		return INCLUDE_PATH. $file_name;
	}

	/**
	* Get tables names cache file name
	*/
	function _get_cache_tables_names_path($db_host = "", $db_user = "", $db_name = "", $db_port = "") {
		$params = array(
			"[DB_HOST]"	=> $db_host ? $db_host : $this->DB_HOST,
			"[DB_NAME]"	=> $db_name ? $db_name : $this->DB_NAME,
			"[DB_USER]"	=> $db_user ? $db_user : $this->DB_USER,
			"[DB_PORT]"	=> $db_port ? $db_port : $this->DB_PORT,
		);
		$file_name = str_replace(array_keys($params), array_values($params), $this->CACHE_TABLES_NAMES_FILE);
		return INCLUDE_PATH. $file_name;
	}

// TODO: cover this method with unit tests and simplify/remove constants/use PARSED TABLES
	/**
	* Try to fix table name
	*/
	function _fix_table_name($name = "") {
		if (!strlen($name)) {
			return "";
		}
		if (!$this->_already_parsed_tables) {
			$this->_parse_tables();
		}
		if (substr($name, 0, strlen("dbt_")) == "dbt_") {
			$name = substr($name, strlen("dbt_"));
		}
		$orig_name = $name;
		$name_wo_db_prefix = $name;
		if ($this->DB_PREFIX && substr($name, 0, strlen($this->DB_PREFIX)) == $this->DB_PREFIX) {
			$name_wo_db_prefix = substr($name, strlen($this->DB_PREFIX));
		}
		if (isset($this->_PARSED_TABLES[$name])) {
			$n2 = $this->_PARSED_TABLES[$name];
		} elseif (isset($this->_PARSED_TABLES[$name_wo_db_prefix])) {
			$n2 = $this->_PARSED_TABLES[$name_wo_db_prefix];
		} else {
			if ($name_wo_db_prefix != $name) {
				$n2 = $this->DB_PREFIX. (in_array($name_wo_db_prefix, $this->_need_sys_prefix) ? "sys_" : ""). $name_wo_db_prefix;
			} else {
				$n2 = $this->DB_PREFIX. $name_wo_db_prefix;
			}
		}
		return $n2;
	}

	/**
	* Trying to repair given table structure (and possibly data)
	*/
	function _repair_table($sql, $db_error) {
		if (empty($db_error) || !$this->ERROR_AUTO_REPAIR) {
			return false;
		}
		if (!function_exists('main')) {
			return false;
		}
		return _class('installer_db', 'classes/db/')->_auto_repair_table($sql, $db_error, $this);
	}

	/**
	* Simple trace without dumping whole objects
	*/
	function _trace() {
		$trace = array();
		foreach (debug_backtrace() as $k => $v) {
			if (!$k) {
				continue;
			}
			$v["object"] = isset($v["object"]) && is_object($v["object"]) ? get_class($v["object"]) : null;
			$trace[$k - 1] = $v;
		}
		return $trace;
	}

	/**
	* Print nice 
	*/
	function _trace_string() {
		$e = new Exception();
		$data = implode("\n", array_slice(explode("\n", $e->getTraceAsString()), 1, -1));
		return $data;
	}

	/**
	* Special init for the debug info items
	*/
	function _set_debug_items() {
		if (!$this->INSTRUMENT_QUERIES) {
			return false;
		}
		$cpu_usage = function_exists("getrusage") ? getrusage() : array();

		$this->_instrument_items = array(
			"memory_usage"		=> function_exists("memory_get_usage") ? memory_get_usage() : "",
			"cpu_user"			=> $cpu_usage["ru_utime.tv_sec"] * 1e6 + $cpu_usage["ru_utime.tv_usec"],
			"cpu_system"		=> $cpu_usage["ru_stime.tv_sec"] * 1e6 + $cpu_usage["ru_stime.tv_usec"],
			"GET_object"		=> $_GET["object"],
			"GET_action"		=> $_GET["action"],
			"GET_id"			=> $_GET["id"],
			"GET_page"			=> $_GET["page"],
			"user_id"			=> $_SESSION["user_id"],
			"user_group"		=> $_SESSION["user_group"],
			"session_id"		=> session_id(),
			"request_id"		=> md5($_SERVER["REMOTE_PORT"]. $_SERVER["REMOTE_ADDR"]. $_SERVER["REQUEST_URI"]. microtime(true)),
			"request_method"	=> $_SERVER["REQUEST_METHOD"],
			"request_uri"		=> $_SERVER["REQUEST_URI"],
			"http_host"			=> $_SERVER["HTTP_HOST"],
			"remote_addr"		=> $_SERVER["REMOTE_ADDR"],
		);
		return true;
	}

	/**
	* Get debug item value
	*/
	function _get_debug_item($name = "") {
		if (!$this->INSTRUMENT_QUERIES) {
			return "";
		}
		return $this->_instrument_items[$name];
	}

	/**
	* Add instrumentation info to the query for highload SQL debug and profile
	*/
	function _instrument_query($query_sql = "", $keys = array('request_id', 'session_id', 'SESSION_user_id', 'GET_object', 'GET_action')) {
		$query_header = "";
		if ($query_sql) {
			// the first frame is the original caller
			$frame = array_pop(debug_backtrace());
			// Add the PHP source location
			$query_header = "-- File: {$frame['file']}\tLine: {$frame['line']}\tFunction: {$frame['function']}\t";
			foreach ((array)$keys as $x => $key) {
				$val = $this->_get_debug_item($key);
				if($val) {
					$val = str_replace(array("\t","\n","\0"), "", $val); 
					// all other chars are safe in comments
					$key = strtolower(str_replace(array(": ","\t","\n","\0"), "", $key)); 
					// Add the requested instrumentation keys
					$query_header .= "\t{$key}: {$val}";
				}
			}
		}
		return $query_header . "\n" . $query_sql;
	}

	/**
	* Helper
	*/
	function delete($table, $where) {
		// Do not allow wide deletes, to prevent awful mistakes, use plain db()->query("DELETE ...") instead
		if (!$where) {
			return false;
		}
		$cond = "id=".$this->enclose_field_value($where);
		if (is_array($where)) {
			$cond = key($where)."=".$this->enclose_field_value(current($where));
		}
		$sql = "DELETE FROM ".$this->_real_name($table)." WHERE ".$cond;
		return $this->query($sql);
	}

	/**
	* Part of query-generation chain
	* Examples:
	*	db()
	*	->select(array("id","name"))
	*	->from("users","u")
	*	->inner_join("groups","g",array("u.group_id"=>"g.id"))
	*	->order_by("add_date")
	*	->group_by("id")
	*	->limit(10)
	*/
	function select($fields = array()) {
		if (is_array($fields)) {
			$sql = "SELECT ".implode(",", $fields);
		} elseif (empty($fields) || $fields == "*") {
			$sql = "SELECT *";
		}
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Part of query-generation chain
	* Examples: from("users"), from("users", "u"), from(array("users" => "u", "suppliers" => "s"))
	*/
	function from($table, $as = "") {
		$tt = array();
		if (is_array($table)) {
			foreach ((array)$table as $t => $_as) {
				$tt[] = $this->_real_name($t). ($_as ? " AS ".$this->enclose_field_name($_as) : "");
			}
		} else {
			$tt[] = $this->_real_name($table). ($as ? " AS ".$this->enclose_field_name($as) : "");
		}
		$sql = "FROM ".implode(",", $tt);
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Part of query-generation chain
	* Examples: join("suppliers", array("u.supplier_id" => "s.id"))
	*/
	function join($table, $as, $items, $join_type = "JOIN") {
		$on = array();
		foreach ((array)$items as $k => $v) {
			list($t1_as, $t1_field) = explode(".", $k);
			list($t2_as, $t2_field) = explode(".", $v);
			$on[] = $this->enclose_field_name($t1_as).".".$this->enclose_field_name($t1_field)." = ".$this->enclose_field_name($t2_as).".".$this->enclose_field_name($t2_field);
		}
		$sql = $join_type." ".$this->_real_name($table)." AS ".$this->enclose_field_name($as)." ON ".implode(",", $on);
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Part of query-generation chain
	*/
	function left_join($table, $as, $items) {
		return $this->join($table, $as, $items, "LEFT JOIN");
	}

	/**
	* Part of query-generation chain
	*/
	function right_join($table, $as, $items) {
		return $this->join($table, $as, $items, "RIGHT JOIN");
	}

	/**
	* Part of query-generation chain
	*/
	function inner_join($table, $as, $items) {
		return $this->join($table, $as, $items, "INNER JOIN");
	}

	/**
	* Part of query-generation chain
	* Example: where(array("id",">","1"),array("name","!=","peter"))
	*/
	function where($items) {
		$where = array();
		foreach ((array)$items as $v) {
			$where[] = $this->enclose_field_name($v[0]). $v[1]. $this->enclose_field_value($v[2]);
		}
		$sql = "WHERE ".implode(" AND ", $where);
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Part of query-generation chain
	* Examples: group_by("user_group"), group_by(array("supplier","manufacturer"))
	*/
	function group_by($items) {
		if (is_array($items)) {
			$by = array();
			foreach ((array)$items as $v) {
				$by[] = $this->enclose_field_name($v);
			}
		} else {
			$by = array($this->enclose_field_name($items));
		}
		$sql = "GROUP BY ".implode(",", $by);
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Part of query-generation chain
	* Examples: order_by("user_group"), order_by(array("supplier","manufacturer"))
	*/
	function order_by($items) {
		if (is_array($items)) {
			$by = array();
			foreach ((array)$items as $v) {
				$by[] = $this->enclose_field_name($v);
			}
		} else {
			$by = array($this->enclose_field_name($items));
		}
		$sql = "ORDER BY ".implode(",", $by);
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Part of query-generation chain
	* Examples: having(array("COUNT(*)",">","1"))
	*/
	function having($items) {
		$where = array();
		foreach ((array)$items as $v) {
			$where[] = $this->enclose_field_name($v[0]). $v[1]. $this->enclose_field_value($v[2]);
		}
		$sql = "HAVING ".implode(" AND ", $where);
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Part of query-generation chain
	* Examples: limit(10), limit(10,100)
	*/
	function limit($count, $offset = null) {
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		if (!is_object($this->db)) {
			return false;
		}
		$sql = $this->db->limit($count, $offset);
		$this->_sql[__FUNCTION__] = $sql;
		return $this;
	}

	/**
	* Execute generated query
	*/
	function exec($as_sql = true) {
		$a = array();
		// Ensuring strict order of parts of the generated SQL will be correct, no matter how functions were called
		foreach (array("select","from","join","left_join","right_join","inner_join","where","group_by","having","order_by","limit") as $name) {
			if ($this->_sql[$name]) {
				$a[] = $this->_sql[$name];
			}
		}
		$sql = implode(" ", $a);
		if (empty($sql)) {
			return false;
		}
		if ($as_sql) {
			return $sql;
		}
#		return $this->query($sql);
	}

	/**
	*/
	function update_batch($table, $data, $index = null, $only_sql = false) {
		if ($this->DB_REPLICATION_SLAVE && !$only_sql) {
			return false;
		}
		if (!$this->_connected && !$this->connect()) {
			return false;
		}
		if (!is_object($this->db)) {
			return false;
		}
		$table = $this->_fix_table_name($table);
		if (!$index) {
			$index = 'id';
		}
		if (!strlen($table) || !$data || !is_array($data) || !$index) {
			return false;
		}
		$this->_set_update_batch($data, $index);
		if (count($this->qb_set) === 0) {
			return false;
		}
		$affected_rows = 0;
		$records_at_once = 100;
		$out = '';
		for ($i = 0, $total = count($this->qb_set); $i < $total; $i += $records_at_once) {
			$sql = $this->_update_batch($table, array_slice($this->qb_set, $i, $records_at_once), $index);
			if ($only_sql) {
				$out .= $sql.";\n";
			} else {
				$this->query($sql);
				$affected_rows += $this->affected_rows();
			}
		}
		$this->qb_set = array();
		if ( ! $only_sql) {
			$out = $affected_rows;
		}
		return $out;
	}

	/**
	*/
	function _update_batch($table, $values, $index) {
		$index = $this->enclose_field_name($index);
		$ids = array();
		foreach ($values as $key => $val) {
			$ids[] = $val[$index];
			foreach (array_keys($val) as $field) {
				if ($field !== $index) {
					$final[$field][] = 'WHEN '.$index.' = '.$val[$index].' THEN '.$val[$field];
				}
			}
		}
		$cases = '';
		foreach ($final as $k => $v) {
			$cases .= $k." = CASE \n"
				.implode("\n", $v)."\n"
				.'ELSE '.$k.' END, ';
		}
		return 'UPDATE '.$this->enclose_field_name($table).' SET '.substr($cases, 0, -2). ' WHERE '.$index.' IN('.implode(',', $ids).')';
	}

	/**
	*/
	function _set_update_batch($key, $index = '') {
		if ( ! is_array($key)) {
			return false;
		}
		foreach ($key as $k => $v) {
			$index_set = FALSE;
			$clean = array();
			foreach ($v as $k2 => $v2) {
				if ($k2 === $index)	{
					$index_set = TRUE;
				}
				$clean[$this->enclose_field_name($k2)] = $this->enclose_field_value($v2);
			}
			if ($index_set === FALSE) {
				//return $this->display_error('db_batch_missing_index');
				return false;
			}
			$this->qb_set[] = $clean;
		}
		return $this;
	}

	/**
	*/
	function insert_batch() {
//		SHOW VARIABLES LIKE 'max_allowed_packet'
// TODO
	}
}
