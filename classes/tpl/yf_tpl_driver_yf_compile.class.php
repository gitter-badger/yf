<?php

/**
* Framework template engine compile extension code
* 
* @package		YF
* @author		YFix Team <yfix.dev@gmail.com>
* @version		1.0
*/
class yf_tpl_driver_yf_compile {

	/** @var string @conf_skip pattern for multi-conditions */
	public $_PATTERN_MULTI_COND= '/["\']{0,1}([\w\s\.\-\+\%]+?)["\']{0,1}[\s\t]+(eq|ne|gt|lt|ge|le|mod)[\s\t]+["\']{0,1}([\w\s\-\#]*)["\']{0,1}/ims';
	/** @var array @conf_skip For "_process_conditions" */
	public $_cond_operators	= array('eq'=>'==','ne'=>'!=','gt'=>'>','lt'=>'<','ge'=>'>=','le'=>'<=','mod'=>'%');

	/**
	*/
	function _init() {
		$_php_start = '<'.'?p'.'hp ';
		$_php_end	= ' ?'.'>';

		// Patterns replaces
		$this->_patterns = array(
			'/\{(else)\}/i'
				=> $_php_start. '} else {'. $_php_end,

// TODO: better execute some wrapper that will convert this into simple call t('changeme %num', array('%num' => 5), 'ru')
			'/\{(t|translate|i18n)\(\s*["\']{0,1}(.*?)["\']{0,1}\s*\)\}/ims'
				=> $_php_start. 'echo _class(\'tpl\')->_i18n_wrapper(\'$2\', $replace);'. $_php_end,

			'/(\{const\(\s*["\']{0,1})([a-z_][a-z0-9_]+?)(["\']{0,1}\s*\)\})/i'
				=> $_php_start. 'echo (defined(\'$2\') ? $2 : \'\');'. $_php_end,

			'/(\{conf\(\s*["\']{0,1})([a-z_][a-z0-9_:]+?)(["\']{0,1}\s*\)\})/i'
				=> $_php_start. 'echo conf(\'$2\');'. $_php_end,

			'/(\{module_conf\(\s*["\']{0,1})([a-z_][a-z0-9_:]+?)(["\']{0,1}\s*,\s*["\']{0,1})([a-z_][a-z0-9_:]+?)(["\']{0,1}\s*\)\})/i'
				=> $_php_start. 'echo module_conf(\'$2\',\'$3\');'. $_php_end,

			// Common replace vars
			'/\{([a-z0-9_-]+)\}/i'
				=> $_php_start. 'echo $replace[\'$1\'];'. $_php_end,

			// Second level vars
			'/\{([a-z0-9_-]+)\.([a-z0-9_-]+)\}/i'
				=> $_php_start. 'echo $replace[\'$1\'][\'$2\'];'. $_php_end,

			// vars inside foreach
			'/\{\#\.([a-z0-9_-]+)\}/i'
				=> $_php_start. 'echo $_v[\'$1\'];'. $_php_end,

			// Variable filtering like in Smarty/Twig
			// Examples:	 {var1|trim}    {var1|urlencode|trim}   {var1|_prepare_html}   {var1|my_func}   {sub1.var1|trim}
			'/\{([a-z0-9_-]+)\|([a-z0-9_\|-]+)\}/i'
				=> $_php_start. 'echo _class(\'tpl\')->_process_var_filters($replace[\'$1\'],\'$2\');'. $_php_end,

			// Second level variables with filters
			'/\{([a-z0-9_-]+)\.([a-z0-9_-]+)\|([a-z0-9_\|-]+)\}/i'
				=> $_php_start. 'echo _class(\'tpl\')->_process_var_filters($replace[\'$1\'][\'$2\'],\'$3\');'. $_php_end,

			// Vars inside foreach with filters
			'/\{\#\.([a-z0-9_-]+)\|([a-z0-9_\|-]+)\}/i'
				=> $_php_start. 'echo _class(\'tpl\')->_process_var_filters($_v[\'$1\'],\'$2\');'. $_php_end,

			// Just closing foreach tags
			'/\{\/(if|foreach)\}/i'
				=> $_php_start. '}'. $_php_end,

			// !!! This pattern also differs from original adding \#\. symbols
			'/\{if\(\s*["\']{0,1}([\w\s\.+%#-]+?)["\']{0,1}[\s\t]+(eq|ne|gt|lt|ge|le|mod)[\s\t]+["\']{0,1}([\w\-\#]*)["\']{0,1}([^\(\)\{\}\n]*)\s*\)\}/imse'
				=> "'". $_php_start. 'if (\'.$this->_compile_prepare_condition(\'$1\',\'$2\',\'$3\',\'$4\').\') {'. $_php_end. "'",

			// !!! This is a completely written from scratch pattern for compilation only
			'/\{foreach\(\s*["\']{0,1}([\w\s\.-]+)["\']{0,1}\s*\)\}/is'
				=> $_php_start.'$__f_total = count($replace[\'$1\']); foreach (is_array($replace[\'$1\']) ? $replace[\'$1\'] : range(1, (int)$replace[\'$1\']) as $_k => $_v) {$__f_counter++;'.$_php_end,

			'/(\{execute\(\s*["\']{0,1})\s*([\w-]+)\s*[,;]\s*([\w-]+)\s*[,;]{0,1}([^"\'\)\}]*)(["\']{0,1}\s*\)\})/i'
				=> $_php_start.'echo main()->_execute(\'$2\',\'$3\',\'$4\',\''.$name.'\',0,false);'.$_php_end,

			'/(\{exec_cached\(\s*["\']{0,1})\s*([\w-]+)\s*[,;]\s*([\w-]+)\s*[,;]{0,1}([^"\'\)\}]*)(["\']{0,1}\s*\)\})/i'
				=> $_php_start.'echo main()->_execute(\'$2\',\'$3\',\'$4\',\''.$name.'\',0,true);'.$_php_end,

			'/\{block\(\s*([\w\-]+)\s*[,;]{0,1}\s*([^"\'\)\}]*)["\']{0,1}\s*\)\}/i'
				=> $_php_start.'echo main()->_execute(\'graphics\',\'_show_block\',\'name=$1;$2\',\''.$name.'\',0,false);'.$_php_end,

			'/\{tip\(\s*["\']{0,1}([\w\.#-]+)["\']{0,1}[,]{0,1}["\']{0,1}([^"\'\)\}]*)["\']{0,1}\s*\)\}/ims'
				=> $_php_start.'echo _class_safe("graphics")->_show_help_tip(array("tip_id"=>\'$1\',"tip_type"=>\'$2\'));'.$_php_end,

			'/\{itip\(\s*["\']{0,1}([^"\'\)\}]*)["\']{0,1}\s*\)\}/ims'
				=> $_php_start.'echo _class_safe("graphics")->_show_inline_tip(array("text"=>\'$1\'));'.$_php_end,

			'/\{(e|user_error)\(\s*["\']{0,1}([\w\.-]+)["\']{0,1}\s*\)\}/ims'
				=> $_php_start.'echo common()->_show_error_inline(\'$2\');'.$_php_end,

			'/(\{include\(\s*["\']{0,1})\s*([@:\w\\/\.]+)\s*["\']{0,1}?\s*[,;]{0,1}\s*([^"\'\)\}]*)\s*(["\']{0,1}\s*\)\})/i'
				=> $_php_start. 'echo $this->_include_stpl(\'$2\',\'$3\',$replace);'. $_php_end,

			'/(\{include_if_exists\(\s*["\']{0,1})\s*([@:\w\\/\.]+)\s*["\']{0,1}?\s*[,;]{0,1}\s*([^"\'\)\}]*)\s*(["\']{0,1}\s*\)\})/i'
				=> $_php_start. 'echo $this->_include_stpl(\'$2\',\'$3\',$replace,true);'. $_php_end,

			'/(\{eval_code\()([^\}]+?)(\)\})/i'
				=> $_php_start. 'echo $2;'. $_php_end,

			'/\{catch\(\s*["\']{0,1}([a-z0-9_]+?)["\']{0,1}\s*\)\}(.*?)\{\/catch\}/ims'
				=> $_php_start. 'ob_start();'. $_php_end. '$2'. $_php_start. '$replace["$1"] = ob_get_clean();'. $_php_end,

			'/\{cleanup\(\s*\)\}(.*?)\{\/cleanup\}/ims'
				=> $_php_start. 'echo trim(str_replace(array("\r","\n","\t"),"",stripslashes(\'$1\')));'. $_php_end,

   			'/\{ad\(\s*["\']{0,1}([^"\'\)\}]*)["\']{0,1}\s*\)\}/ims'
				=> $_php_start. 'echo module_safe("advertising")->_show(array("ad"=>\'$1\'));'. $_php_end,

			'/\{url\(\s*["\']{0,1}([^"\'\)\}]*)["\']{0,1}\s*\)\}/ims'
				=> $_php_start. 'echo _class(\'tp\')->_generate_url_wrapper(\'$1\');'. $_php_end,

			'/\{form_row\(\s*["\']{0,1}[\s\t]*([a-z0-9_-]+)[\s\t]*["\']{0,1}([\s\t]*,[\s\t]*["\']{1}([^"\']*)["\']{1})?([\s\t]*,[\s\t]*["\']{1}([^"\']*)["\']{1})?([\s\t]*,[\s\t]*["\']{1}([^"\']*)["\']{1})?\s*\)\}/ims'
				=> $_php_start. 'echo _class("form2")->tpl_row(\'$1\',$replace,\'$3\',\'$5\',\'$7\');'. $_php_end,

			'/\{(css|require_css|js|require_js)\(\s*["\']{0,1}([^"\'\)\}]*?)["\']{0,1}\s*\)\}\s*(.+?)\s*{\/(\1)\}/ims'
				=> $_php_start. 'echo $1(\'$3\', _attrs_string2array(\'$2\'));'. $_php_end,

// TODO: this code needed to be executed last, but if compiled - this will be hard to achieve
// TODO: convert this into events after center block was processed
			'/(\{exec_last|execute_shutdown\(\s*["\']{0,1})\s*([\w-]+)\s*[,;]\s*([\w-]+)\s*[,;]{0,1}([^"\'\)\}]*)(["\']{0,1}\s*\)\})/i'
				=> $_php_start.'echo main()->_execute(\'$2\',\'$3\',\'$4\',\''.$name.'\',0,false);'.$_php_end,

			// DEBUG_MODE patterns

			'/(\{_debug_get_replace\(\)\})/i'
				=> $_php_start. 'echo (DEBUG_MODE && is_array($replace) ? "<pre>".print_r(array_keys($replace),1)."</pre>" : "");'. $_php_end,

			'/(\{_debug_get_vars\(\)\})/i'
				=> $_php_start. 'echo $this->_debug_get_vars($string);'. $_php_end,
		);
	}

	/**
	* Compile given template into pure PHP code
	*/
	function _compile($name, $replace = array(), $string = "") {
		$_time_start = microtime(true);

		// For later check for templates changes
		if (_class('tpl')->COMPILE_CHECK_STPL_CHANGED) {
			$_md5_string = md5($string);
		}
		$compiled_dir = PROJECT_PATH. _class('tpl')->COMPILED_DIR;
		// Do not check dir existence twice
		if (!isset($this->_stpl_compile_dir_check)) {
			_mkdir_m($compiled_dir);
			$this->_stpl_compile_dir_check = true;
		}

		$file_name = $compiled_dir.'c_'.MAIN_TYPE.'_'.urlencode($name).'.php';

		$_php_start = '<'.'?p'.'hp ';
		$_php_end	= ' ?'.'>';

		// Simple replaces
		$_my_replace = array(
			// Special tags for foreach
			'{_key}'	=> $_php_start. 'echo $_k;'. $_php_end,
			'{_val}'	=> $_php_start. 'echo $_v;'. $_php_end,
		);
		$string = str_replace(array_keys($_my_replace), array_values($_my_replace), $string);
		$string = preg_replace(array_keys($this->_patterns), array_values($this->_patterns), $string);

		// Images and uploads paths compile
		$web_path		= MAIN_TYPE_USER ? 'MEDIA_PATH' : 'ADMIN_WEB_PATH';
		$images_path	= $web_path. '._class(\'tpl\')->TPL_PATH. _class(\'tpl\')->_IMAGES_PATH';
		$to_replace = array(
			'"images/'		=> '"'.$_php_start. 'echo '.$images_path.';'. $_php_end,
			'\'images/'		=> '\''.$_php_start. 'echo '.$images_path.';'. $_php_end,
			'"uploads/'		=> '"'.$_php_start. 'echo MEDIA_PATH. _class(\'tpl\')->_UPLOADS_PATH;'. $_php_end,
			'\'uploads/'	=> '\''.$_php_start. 'echo MEDIA_PATH. _class(\'tpl\')->_UPLOADS_PATH;'. $_php_end,
			'src="uploads/'	=> 'src="'.$_php_start. 'echo '.$web_path.'._class(\'tpl\')->_UPLOADS_PATH;'. $_php_end,
		);
		$string = str_replace(array_keys($to_replace), array_values($to_replace), $string);

		$string = '<'.'?p'.'hp if(!defined(\'YF_PATH\')) exit(); /* '.
			'date: '.gmdate('Y-m-d H:i:s').' GMT; '.
			'compile_time: '.common()->_format_time_value(microtime(true) - $_time_start).'; '.
			'name: '.$name.'; '.
			' */ '.
			'?'.'>'. PHP_EOL. $string;

		file_put_contents($file_name, $string);
	}

	/**
	* Prepare condition for the compilation
	*/
	function _compile_prepare_condition ($part_left = '', $cond_operator = '', $part_right = '', $add_cond = '') {
		// Left part processing
		$part_left = $this->_compile_prepare_left($part_left);
		// Right part processing
		if ($part_right{0} == '#') {
			$part_right = '$replace[\''.ltrim($part_right, '#').'\']';
		} else {
			$part_right = "'".$part_right."'";
		}
		// Additional condition
		if ($add_cond) {
			$_tmp_parts = preg_split("/[\s\t]+(and|xor|or)[\s\t]+/ims", $add_cond, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			if ($_tmp_parts) {
				$_tmp_count = count($_tmp_parts);
			}
			for ($i = 1; $i < $_tmp_count; $i+=2) {
				if (preg_match($this->_PATTERN_MULTI_COND, stripslashes($_tmp_parts[$i]), $m)) {
					$a_part_left	= $this->_compile_prepare_left($m[1]);
					$a_cur_operator	= $this->_cond_operators[strtolower($m[2])];
					$a_part_right	= $m[3];
					if ($a_part_right{0} == '#') {
						$a_part_right = '$replace[\''.ltrim($a_part_right, '#').'\']';
					}
					if (!is_numeric($a_part_right)) {
						$a_part_right = "'".$a_part_right."'";
					}
					if (empty($a_part_left)) {
						$a_part_left = "''";
					}
					$_tmp_parts[$i] = $a_part_left." ".$a_cur_operator." ".$a_part_right;
				} else {
					$_tmp_parts[$i] = '';
				}
				if (!strlen($_tmp_parts[$i])) {
					unset($_tmp_parts[$i]);
					unset($_tmp_parts[$i - 1]);
				}
			}
			if ($_tmp_parts) {
				$add_cond = implode(' ', (array)$_tmp_parts);
			} else {
				$add_cond = '';
			}
		}
		$op = $this->_cond_operators[strtolower($cond_operator)];
		// Special case for "mod". 
		// Examples: {if("id" mod 4)} content {/if}
		if ($op == '%') {
			$part_left = '!('.$part_left;
			$part_right = $part_right.')';
		}
		return trim($part_left.' '.$op.' '.$part_right.' '.$add_cond);
	}

	/**
	* Prepare left part of the condition
	*/
	function _compile_prepare_left ($part_left = '') {
		$_array_magick = array(
			'_num'	=> '$__f_counter',
			'_total'=> '$__f_total',
			'_first'=> '($__f_counter == 1)',
			'_last'	=> '($__f_counter == $__f_total)',
			'_even'	=> '(!($__f_counter % 2))',
			'_odd'	=> '($__f_counter % 2)',
			'_total'=> '$__f_total',
			'_key'	=> '$_k',
			'_val'	=> '$_v',
		);
		// Array item
		if (substr($part_left, 0, 2) == '#.') {
			$part_left = '$_v[\''.substr($part_left, 2).'\']';
		// Array special magic keyword
		} elseif (isset($_array_magick[$part_left])) {
			$part_left = $_array_magick[$part_left];
		// Module config item
		} elseif (strpos($part_left, 'module_conf.') === 0) {
			list($mod_name, $mod_conf) = explode('.', substr($part_left, strlen('module_conf.')));
			$part_left = 'module_conf(\''.$mod_name.'\',\''.$mod_conf.'\')';
		// Configuration item
		} elseif (strpos($part_left, 'conf.') === 0) {
			$part_left = 'conf(\''.substr($part_left, strlen('conf.')).'\')';
		// Constant
		} elseif (false !== strpos($part_left, 'const.')) {
			$part_left = substr($part_left, strlen('const.'));
			$part_left = '(defined(\''.$part_left.'\') ? '.$part_left.' : \'\')';
		// Global array item in left part
		} elseif (false !== strpos($part_left, '.')) {
			list($k, $v) = explode('.', $part_left);
			$avail_arrays = (array)_class('tpl')->_avail_arrays;
			$part_left = '$'.str_replace(array_keys($avail_arrays), array_values($avail_arrays), $k).'[\''.$v.'\']';
		// Simple number or string, started with '%'
		} elseif ($part_left{0} == '%' && strlen($part_left) > 1) {
			$part_left = '"'.str_replace('"', '\"', substr($part_left, 1)).'"';
		} else {
			$part_left = '$replace[\''.$part_left.'\']';
		}
		return $part_left;
	}

	/**
	* fix translation of the dynamic vars like: {t('num vars in {vertical}')}
	*/
#	function _prepare_translate2 ($string = '', $for_params = false) {
#		if ($for_params) {
#			$string = str_replace("'", '', $string);
#		}
#		return preg_replace('/\{([a-z0-9\-\_]+)\}/i', "'.\$replace['\\1'].'", $string);
#	}
}