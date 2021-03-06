<?php

/**
* Site Map Generator (implementation of the http://www.sitemaps.org/protocol.php)
* 
* @package		YF
* @author		YFix Team <yfix.dev@gmail.com>
* @version		1.0
*/
class yf_site_map {

	/** @var bool Enable\disable site map generation */
	public $SITE_MAP_ENABLED		= false;
	/** @var array Array of site modules to include their parts of sitemap */
	public $MODULES_TO_INCLUDE		= array();
	/** @var string Sitemap store folder */
	public $SITEMAP_STORE_FOLDER	= 'site_map/';
	/** @var string Sitemap file name */
	public $SITEMAP_FILE_NAME		= 'site_map';
	/** @var @conf_skip */
	public $_HOOK_NAME				= '_site_map_items';
	/** @var bool Use GZIP */
	public $USE_GZIP				= false;
	/** @var bool Notify Google */
	public $NOTIFY_GOOGLE			= false;
	/** @var int Max entries for sitemap file */
	public $MAX_ENTRIES			= 50000;
	/** @var int Max sitemap filesize */
	public $MAX_SIZE				= 10000000;
	/** @var int Limit max URL length to the 2048 symbols*/
	public $MAX_URL_LENGTH			= 2048;
	/** @var int Max number of sitemaps */
	public $MAX_SITEMAPS			= 1000;
	/** @var bool Do not change! 
	* It's a flag which is become 'true' if number of sitemap 
	* files reached $MAX_SITEMAPS. There will be no actions after this
	*/
	public $LIMIT_REACHED			= false;
	/** @var array Frequency avail values @conf_skip */
	public $CHANGEFREQ_VALUES		= array('always','hourly','daily','weekly','monthly','yearly','never');
	/** @var string @conf_skip */
	public $_notify_url			= '';
	/** @var string @conf_skip */
	public $_file_name				= '';
	/** @var string @conf_skip */
	public $_file_extension		= '.xml';
	/** @var bool Use locking */
	public $USE_LOCKING			= false;
	/** @var int Lock timeout */
	public $LOCK_TIMEOUT			= 600;
	/** @var string Lock file name */
	public $LOCK_FILE_NAME			= 'uploads/site_map.lock';
	/** @var int Site map TTL */
	public $SITEMAP_LIVE_TIME		= 43200;	// 60*60*12 = 12hours
	/** @var bool Allow rewrite */
	public $ALLOW_REWRITE			= false;
	/** @var int Rewrite split length */
	public $REWRITE_SPLIT_LENGTH	= 200000;
	/** @var bool */
	public $DIRECT_FILE_OUTPUT		= true;
	/** @var bool */
	public $TEST_MODE				= false;
	/** @var bool */
	public $SECURITY_URL_PARAM		= '';

	/**
	* Catch missing method call
	*/
	function __call($name, $arguments) {
		trigger_error(__CLASS__.': No method '.$name, E_USER_WARNING);
		return false;
	}

	/**
	* YF module constructor
	*
	* @access	private
	* @return	void
	*/
	function _init () {
		// Prepare lock file
		if ($this->USE_LOCKING) {
			$this->LOCK_FILE_NAME = INCLUDE_PATH. $this->LOCK_FILE_NAME;
		}
		// Define path to folder where sitemap files are stored
		if (!defined('UPLOADS_DIR')) {
			define('UPLOADS_DIR', 'uploads/');
		}
		if (defined('SEARCH_VERTICAL') && SEARCH_VERTICAL != '') {
			$this->SITEMAP_FILE_NAME .= '_'.SEARCH_VERTICAL;
			$this->LOCK_FILE_NAME = str_replace('.lock', '_'.SEARCH_VERTICAL.'.lock', $this->LOCK_FILE_NAME);
		}
		if (defined('SEARCH_COUNTRY') && SEARCH_COUNTRY != '') {
			$this->SITEMAP_FILE_NAME .= '_'.SEARCH_COUNTRY;
			$this->LOCK_FILE_NAME = str_replace('.lock', '_'.SEARCH_COUNTRY.'.lock', $this->LOCK_FILE_NAME);
		}
		$this->SITEMAP_WEB_PATH = WEB_PATH. UPLOADS_DIR. $this->SITEMAP_STORE_FOLDER;
		$this->SITEMAP_STORE_FOLDER = INCLUDE_PATH. UPLOADS_DIR. $this->SITEMAP_STORE_FOLDER;
		// Calculate size of a string (web path) adding to url when file processed
		$this->_path_size_bytes = strlen(htmlspecialchars(utf8_encode(WEB_PATH)));
		// Leave some space in file size
		$this->MAX_SIZE = $this->MAX_SIZE * 0.9;
		// Current page URL
		$this->_notify_url = process_url(WEB_PATH. $_GET['object']);
		// Turn off gzip if not available
		if ($this->USE_GZIP && extension_loaded('zlib')) {
			$this->USE_GZIP = false;
		}
		// Get available user section modules (only when list is not predefined)
		if (empty($this->MODULES_TO_INCLUDE)) {
			$this->MODULES_TO_INCLUDE = $this->_get_modules_from_files();
		}
		// Ensure uniqueness of module names
		$tmp = array();
		foreach ((array)$this->MODULES_TO_INCLUDE as $v) {
			$tmp[$v] = $v;
		}
		$this->MODULES_TO_INCLUDE = $tmp;
		// Remove non-active modules
		$Q = db()->query('SELECT * FROM '.db('user_modules').' WHERE active=0');
		while ($A = db()->fetch_assoc($Q)) {
			if (in_array($A['name'], $this->MODULES_TO_INCLUDE)) {
				unset($this->MODULES_TO_INCLUDE[$A['name']]);
			}
		}
		if ($this->ALLOW_REWRITE && !tpl()->REWRITE_MODE) {
			$this->ALLOW_REWRITE = false;
		}
	}

	/**
	* Default method
	*/
	function show () {
		if ($this->SECURITY_URL_PARAM && $_GET['id'] != $this->SECURITY_URL_PARAM) {
			main()->NO_GRAPHICS = true;
			return print _e('Wrong url');
		}
		return $this->_generate_sitemap();
	}

	/**
	* Sitemap format generator (compatible with Google, Yahoo, MSN etc)
	*
	* https://www.google.com/webmasters/sitemaps/docs/en/about.html
	* XML-Specification: https://www.google.com/webmasters/sitemaps/docs/en/protocol.html
	* 
	* You can use the gzip feature or compress your Sitemap files using gzip. 
	* Please note that your uncompressed Sitemap file
	* may not be larger than 10MB.
	*
	* No support for more the 50.000 URLs at the moment. See
	* http://www.google.com/webmasters/sitemaps/docs/en/protocol.html#sitemapFileRequirements
	*/
	function _generate_sitemap () {
		main()->NO_GRAPHICS = true;
		// Ability to turn off site map
		if (!$this->SITE_MAP_ENABLED) {
			return false;
		}
		$_sitemap_base_name = $this->SITEMAP_FILE_NAME;
		// Check if needed to recreate sitemap files
		$_path = $this->SITEMAP_STORE_FOLDER. $this->SITEMAP_FILE_NAME.'_index'.$this->_file_extension;
		if (file_exists($_path) && (filemtime($_path) + $this->SITEMAP_LIVE_TIME) > time() && !$this->TEST_MODE) {
			// send headers
			$this->_redirect_sitemap($this->SITEMAP_WEB_PATH. $this->SITEMAP_FILE_NAME. '_index'. $this->_file_extension);
			return false;
		}
		if (!file_exists($_path)){
			$_path = $this->SITEMAP_STORE_FOLDER. $this->SITEMAP_FILE_NAME.'1'.$this->_file_extension;
			if (file_exists($_path) && (filemtime($_path) + $this->SITEMAP_LIVE_TIME) > time() && !$this->TEST_MODE) {
				// send headers
				$this->_redirect_sitemap($this->SITEMAP_WEB_PATH. $this->SITEMAP_FILE_NAME. '1'. $this->_file_extension);
				return false;
			} 
		}
		// Start generate sitemap
		@ignore_user_abort(true);
		@set_time_limit($this->LOCK_TIMEOUT);
		// Process locking
		if ($this->USE_LOCKING) {
			clearstatcache();
			if (file_exists($this->LOCK_FILE_NAME)) {
				// Timed out lock file
				if ((time() - filemtime($this->LOCK_FILE_NAME)) > $this->LOCK_TIMEOUT) {
					unlink($this->LOCK_FILE_NAME);
				} else {
					return false;
				}
			}
			// Put lock file
			file_put_contents($this->LOCK_FILE_NAME, time());
		}
		$this->_sitemap_file_counter = 1;		
		_mkdir_m($this->SITEMAP_STORE_FOLDER);
		_class('dir')->delete_files($this->SITEMAP_STORE_FOLDER, '/'.$_sitemap_base_name.'.*/i');

		$this->_fp = fopen($this->SITEMAP_STORE_FOLDER.$this->SITEMAP_FILE_NAME.$this->_sitemap_file_counter.$this->_file_extension, 'w+');
		$this->_total_length = 0;
		$this->_entries_counter = 0;

		$this->_output($this->_tpl_sitemap_header());
		$this->_total_length = strlen($header_text);
		// Process modules hooks
		foreach ((array)$this->MODULES_TO_INCLUDE as $_mod_name) {
			$MOD_OBJ = module($_mod_name);
			if (!is_object($MOD_OBJ) || !method_exists($MOD_OBJ, $this->_HOOK_NAME) || $this->LIMIT_REACHED) {
				continue;
			}
			$MOD_OBJ->{$this->_HOOK_NAME}($this);
		}
		if (!$this->LIMIT_REACHED) {
			$this->_output($this->_tpl_sitemap_footer());
			@fclose($this->_fp);
		}
		$files = _class('dir')->scan_dir($this->SITEMAP_STORE_FOLDER);
		foreach ((array)$files as $file_name) {
			if (false !== strpos($file_name, '.svn')) {
				continue;
			}
			if (false !== strpos($file_name, '.git')) {
				continue;
			}
			$path_info = pathinfo($file_name);
			$_sitemap_filename_pattern = '/^('.$this->SITEMAP_FILE_NAME.')([0-9]{1,3})(\.xml)$/';
			if (!preg_match($_sitemap_filename_pattern, $path_info['basename'])) {
				continue;
			} else {
				$sitemaps[$file_name] = $file_name;
			}
		}
		if ($sitemaps) {
			foreach ((array)$sitemaps as $filename) {
				$this->_process_sitemap_file($filename);
			}
		} 
		$this->_file_for_google = str_replace(INCLUDE_PATH, WEB_PATH, $this->SITEMAP_STORE_FOLDER). $this->SITEMAP_FILE_NAME.'1'.$this->_file_extension;
		if ($sitemaps && count($sitemaps) > 1) {
			$this->_output($this->_tpl_sitemap_index_header());

			// Create sitemap index 
			$this->_fp = fopen($this->SITEMAP_STORE_FOLDER.$this->SITEMAP_FILE_NAME.'_index'.$this->_file_extension, 'w+');
			foreach ((array)$sitemaps as $sitemap_file){
				// Gzip sitemap files
				if ($this->USE_GZIP){
					$sitemap_file = $this->_gzip_file($sitemap_file);
				}
				$string = "\t<sitemap>\n";
				$string .= "\t\t<loc>".str_replace(INCLUDE_PATH, WEB_PATH, $sitemap_file)."</loc>\n";
				$last_mod = $this->_iso8601_date(filemtime($sitemap_file));
				if ($last_mod) {
					$string .= "\t\t<lastmod>".$last_mod."</lastmod>\n";
				}
				$string .= "\t</sitemap>\n";
				// Store entry data to file
				$this->_output($string);
			}
			fclose($this->_fp);

			$this->_output($this->_tpl_sitemap_index_footer());

			$this->_file_for_google = str_replace(INCLUDE_PATH, WEB_PATH, $this->SITEMAP_STORE_FOLDER) .$this->SITEMAP_FILE_NAME. '_index'. $this->_file_extension;
		}	
		// Release lock
		if ($this->USE_LOCKING) {
			unlink($this->LOCK_FILE_NAME);
		}
		$this->_redirect_sitemap($this->_file_for_google);

		$this->_do_notify_google();
	}

	/**
	* Store sitemap item
	*/
	function _store_item ($data = array()) {
		if (empty($data) || empty($data['url'])) {
			return false;
		}
		// Do nothing
		if ($this->LIMIT_REACHED) {
			return false;
		}
		$location = $data['url'];
		if ((strlen($location) + $this->_path_size_bytes) >= $this->MAX_URL_LENGTH) {
			return false;
		}
		$string .= "\t<url>\n";
		$string .= "\t\t<loc>".$location."</loc>\n";
		// Adding size of path string to a total size of data
		$this->_total_length += $this->_path_size_bytes;
		if ($data["last_update"]) {
			$string .= "\t\t<lastmod>".$this->_iso8601_date($data["last_update"])."</lastmod>\n";
		}
		if ($data["priority"]) {
			$string .= "\t\t<priority>".floatval($data["priority"])."</priority>\n";
		}
		if ($data["changefreq"] && in_array($data["changefreq"], $this->CHANGEFREQ_VALUES)) {
			$string .= "\t\t<changefreq>".$data["changefreq"]."</changefreq>\n";
		}
		$string .= "\t</url>\n";

		$this->_total_length += strlen($string);
		$this->_entries_counter++;	
		// Check total length and number of entries before save
		if ($this->_total_length >= $this->MAX_SIZE || $this->_entries_counter >= $this->MAX_ENTRIES) {
			// Finish to write file and create another one
			if ($this->_fp) {
				// Output footer
				$this->_output($this->_tpl_sitemap_footer());
				fclose($this->_fp);
			}
			if ($this->_entries_counter >= $this->MAX_ENTRIES || $this->_total_length >= $this->MAX_SIZE) {
				$this->_entries_counter = 0; 
				$this->_total_length = 0; 
			}
			// Check if we reached max sitemap files number
			if ($this->_sitemap_file_counter >= $this->MAX_SITEMAPS){
				$this->LIMIT_REACHED = true;
			}
			if (!$this->LIMIT_REACHED) {
				$this->_sitemap_file_counter++;
				// Create and open to write next sitemap file 
				$this->_fp = fopen($this->SITEMAP_STORE_FOLDER.$this->SITEMAP_FILE_NAME.$this->_sitemap_file_counter.$this->_file_extension, 'w+');
				// Output header
				$this->_output($this->_tpl_sitemap_header());
				$this->_total_length = strlen($this->_tpl_sitemap_header());
			}
		}
		if (!$this->LIMIT_REACHED) {
			// Store entry data to file
			$this->_output($string);
		}
	}

	/**
	* Output generated content
	*/
	function _output($string) {
		if ($this->TEST_MODE) {
			return false;
		}
		if ($this->_fp) {
			fwrite($this->_fp, $string);
		} 
	}

	/**
	* Redirect to sitemap source files
	*/
	function _redirect_sitemap($location) {
		if ($this->TEST_MODE) {
			return false;
		}
		if ($this->DIRECT_FILE_OUTPUT) {
			header('Content-type: text/xml');
			readfile(str_replace($this->SITEMAP_WEB_PATH, $this->SITEMAP_STORE_FOLDER, $location));
			exit();
		} else {
			header(($_SERVER['SERVER_PROTOCOL'] ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1').' 301 Moved Permanently');
			header('Location: '.$location); 
		}
	}

	/**
	* Do gzip file
	*/
	function _gzip_file($filename) {
		$new_filename = $filename.'.gz';
		file_put_contents($new_filename, gzencode(file_get_contents($filename)));
		return $new_filename;		
	}

	/**
	* Notify google about update
	* $this->_file_for_google - sitemap or sitemap index file to give to google
	*/
	function _do_notify_google() {
		if ($this->NOTIFY_GOOGLE) {
			fopen('http://www.google.com/webmasters/sitemaps/ping?sitemap='.urlencode($this->_notify_url), 'r');
		}
	}

	/**
	* backwards compatible for PHP version < 5
	*/
	function _iso8601_date($timestamp) {
		return date('c', $timestamp);
	}

	/**
	* Processes sitemap file. Escapes special chars. Makes full webpath 
	*/
	function _process_sitemap_file($_path = ''){
		$text = file_get_contents($_path);
		// Process URLs
		if ($this->ALLOW_REWRITE) {
			$RW = _class('rewrite');
			$RW->FORCE_NO_DEBUG = true;
			// Save old pattern
			$old_pattern = $RW->_links_pattern;
			// Aplly our custom pattern
			$RW->_links_pattern = '/(<loc>)([^\<]+)(<\/loc>)/ms';
			// Process rewrite
			foreach ((array)common()->_my_split($text, '<url>', $this->REWRITE_SPLIT_LENGTH) as $_cur_text) {
				$rewrited .= $RW->_rewrite_replace_links($_cur_text);
			}
			$text = $rewrited;
			unset($rewrited);
			// Revert old links pattern
			$RW->_links_pattern = $old_pattern;
		} else {
			$text = str_replace('<loc>./?', '<loc>'.WEB_PATH.'?', $text);
		}
		$text = preg_replace('/<loc>([^\<]+)<\/loc>/imse', "'<loc>'.htmlspecialchars('\\1').'</loc>'", $text);
		file_put_contents($_path, $text);
	}

	/**
	* Get available modules for the sitemap creation
	*/
	function _get_modules_from_files ($include_framework = true, $with_sub_modules = false) {
		$pattern = '/(function)(\s)+(_site_map_items)/ims';

		$user_modules_array = array();
		$dir_to_scan = INCLUDE_PATH. USER_MODULES_DIR;
		foreach ((array)_class('dir')->scan_dir($dir_to_scan) as $k => $v) {
			$v = str_replace('//', '/', $v);
			if (substr($v, -strlen(YF_CLS_EXT)) != YF_CLS_EXT) {
				continue;
			}
			if (!$with_sub_modules && false !== strpos(substr($v, strlen($dir_to_scan)), '/')) {
				continue;
			}
			$module_name = substr(basename($v), 0, -strlen(YF_CLS_EXT));
			$module_name = str_replace(YF_SITE_CLS_PREFIX, '', $module_name);
			$file_content = file_get_contents($v);
			if (preg_match($pattern, $file_content, $matches)) {
				$user_modules_array[$module_name] = $module_name;
			}
		}
		$dir_to_scan = INCLUDE_PATH. 'priority2/'. USER_MODULES_DIR;
		foreach ((array)_class('dir')->scan_dir($dir_to_scan) as $k => $v) {
			$v = str_replace('//', '/', $v);
			if (substr($v, -strlen(YF_CLS_EXT)) != YF_CLS_EXT) {
				continue;
			}
			if (!$with_sub_modules && false !== strpos(substr($v, strlen($dir_to_scan)), '/')) {
				continue;
			}
			$module_name = substr(basename($v), 0, -strlen(YF_CLS_EXT));
			$module_name = str_replace(YF_SITE_CLS_PREFIX, '', $module_name);
			$file_content = file_get_contents($v);
			if (preg_match($pattern, $file_content, $matches)) {
				$user_modules_array[$module_name] = $module_name;
			}
		}
		// Do parse files from the framework
		if ($include_framework) {
			$dir_to_scan = YF_PATH. USER_MODULES_DIR;
			foreach ((array)_class('dir')->scan_dir($dir_to_scan) as $k => $v) {
				$v = str_replace('//', '/', $v);
				if (substr($v, -strlen(YF_CLS_EXT)) != YF_CLS_EXT) {
					continue;
				}
				if (!$with_sub_modules && false !== strpos(substr($v, strlen($dir_to_scan)), '/')) {
					continue;
				}
				$module_name = substr(basename($v), 0, -strlen(YF_CLS_EXT));
				$module_name = str_replace(YF_PREFIX, '', $module_name);
				$module_name = str_replace(YF_SITE_CLS_PREFIX, '', $module_name);
				$file_content = file_get_contents($v);
				if (preg_match($pattern, $file_content, $matches)) {
					$user_modules_array[$module_name] = $module_name;
				}
			}
			$dir_to_scan = YF_PATH. 'priority2/'. USER_MODULES_DIR;
			foreach ((array)_class('dir')->scan_dir($dir_to_scan) as $k => $v) {
				$v = str_replace('//', '/', $v);
				if (substr($v, -strlen(YF_CLS_EXT)) != YF_CLS_EXT) {
					continue;
				}
				if (!$with_sub_modules && false !== strpos(substr($v, strlen($dir_to_scan)), '/')) {
					continue;
				}
				$module_name = substr(basename($v), 0, -strlen(YF_CLS_EXT));
				$module_name = str_replace(YF_PREFIX, '', $module_name);
				$module_name = str_replace(YF_SITE_CLS_PREFIX, '', $module_name);
				$file_content = file_get_contents($v);
				if (preg_match($pattern, $file_content, $matches)) {
					$user_modules_array[$module_name] = $module_name;
				}
			}
		}
		ksort($user_modules_array);
		return $user_modules_array;
	}

	/**
	*/
	function _tpl_sitemap_header () {
		return '<?xml version="1.0" encoding="UTF-8"?>'
			.PHP_EOL. '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'
			.PHP_EOL. ' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'
			.PHP_EOL;
	}

	/**
	*/
	function _tpl_sitemap_footer () {
		return PHP_EOL. '</urlset>'. PHP_EOL;
	}

	/**
	*/
	function _tpl_sitemap_index_header () {
		return '<?xml version="1.0" encoding="UTF-8"?>'
			.PHP_EOL. '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'
			.PHP_EOL. ' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">'
			.PHP_EOL;
	}

	/**
	*/
	function _tpl_sitemap_index_footer () {
		return PHP_EOL. '</sitemapindex>'. PHP_EOL;
	}
}
