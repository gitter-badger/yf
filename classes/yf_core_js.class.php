<?php

class yf_core_js {

// TODO: debug console block
// TODO: auto-caching into web-accessible dir with locking (to avoid duplicate cache entry attempts)

	public $content = array();
	/** @array List of pre-defined assets */
	public $assets = array(
// TODO: add support for sub-arrays and params
		'jquery'	=> '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js',
		'jquery-ui'	=> '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js',
		'angular'	=> '//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular.min.js',
		'angular-ui'=> '//cdnjs.cloudflare.com/ajax/libs/angular-ui/0.4.0/angular-ui.min.js',
		'bs2'		=> '//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js',
		'bs3'		=> '//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js',
#		'html5shiv'	=> '<!--[if lt IE 9]><script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js" class="yf_core"></script><![endif]-->',
	);

	/**
	* Main method to display overall JS. Can be called from main 
	* like this: {execute_shutdown(core_js,show)} or {execute_shutdown(graphics,show_js)}
	*/
	public function show($params = array()) {
		// Main JS from theme stpl
		$main_script_js = trim(tpl()->parse_if_exists('script_js'));
		// single string = automatically generated by compass
		if (strpos($main_script_js, "\n") === false && strlen($main_script_js) && preg_match('~^js/script.js\?[0-9]{10}$~ims', $main_script_js)) {
			$this->add_url(WEB_PATH. tpl()->TPL_PATH. $main_script_js);
		} else {
			$this->add_raw($main_script_js);
		}
		// JS from current module
		$module_js_path = $this->_find_module_js($_GET['object']);
		if ($module_js_path) {
			$this->add_file($module_js_path);
		}
		$out = array();
		// Process previously added content, depending on its type
		foreach ((array)$this->content as $md5 => $v) {
			$type = $v['type'];
			$text = $v['text'];
			if ($type == 'url') {
				$out[$md5] = '<script src="'._prepare_html($text).'" type="text/javascript"></script>';
			} elseif ($type == 'file') {
				$out[$md5] = '<script type="text/javascript">'. PHP_EOL. file_get_contents($text). PHP_EOL. '</script>';
			} elseif ($type == 'inline') {
				$text = $this->_strip_script_tags($text);
				$out[$md5] = '<script type="text/javascript">'. PHP_EOL. $text. PHP_EOL. '</script>';
			} elseif ($type == 'raw') {
				$out[$md5] = $text;
			}
		}
		return implode(PHP_EOL, $out);
	}

	/**
	* Alias
	*/
	public function show_js($params = array()) {
		return $this->show($params);
	}

	/**
	* $content: string/array
	* $type: = auto|asset|url|file|inline|raw
	*/
	public function add($content, $force_type = 'auto', $params = array()) {
		if (DEBUG_MODE) {
			$trace = main()->trace_string();
		}
		if (!is_array($content)) {
			$content = array($content);
		}
		foreach ($content as $_content) {
			$_content = trim($_content);
			if (!strlen($_content)) {
				continue;
			}
			$type = '';
			if (in_array($force_type, array('url','file','inline','raw','asset'))) {
				$type = $force_type;
			} else {
				$type = $this->_detect_content($_content);
			}
			$md5 = md5($_content);
			if ($type == 'url') {
				$this->content[$md5] = array(
					'type'	=> 'url',
					'text'	=> $_content,
				);
			} elseif ($type == 'file') {
				if (file_exists($_content)) {
					$text = file_get_contents($_content);
					if (strlen($text)) {
						$this->content[$md5] = array(
							'type'	=> 'file',
							'text'	=> $_content,
						);
					}
				}
			} elseif ($type == 'inline') {
				$this->content[$md5] = array(
					'type'	=> 'inline',
					'text'	=> $_content,
				);
			} elseif ($type == 'raw') {
				$this->content[$md5] = array(
					'type'	=> 'raw',
					'text'	=> $_content,
				);
			} elseif ($type == 'asset') {
				$url = $this->assets[$_content];
				$md5 = md5($url);
				$this->content[$md5] = array(
					'type'	=> 'url',
					'text'	=> $url,
				);
			}
			if (DEBUG_MODE) {
				debug('core_css[]', array(
					'type'		=> $type,
					'md5'		=> $md5,
					'content'	=> $_content,
					'is_added'	=> isset($this->content[$md5]),
					'trace'		=> $trace,
				));
			}
		}
	}

	/**
	*/
	public function add_url($content, $params = array()) {
		return $this->add($content, 'url');
	}

	/**
	*/
	public function add_file($content, $params = array()) {
		return $this->add($content, 'file');
	}

	/**
	*/
	public function add_inline($content, $params = array()) {
		return $this->add($content, 'inline');
	}

	/**
	*/
	public function add_raw($content, $params = array()) {
		return $this->add($content, 'raw');
	}

	/**
	* Search for JS for current module in several places, where it can be stored.
	*/
	public function _find_module_js($module = '') {
		if (!$module) {
			$module = $_GET['object'];
		}
		$js_path = $module.'/'.$module.'.js';
		$paths = array(
			MAIN_TYPE_ADMIN ? YF_PATH. 'templates/admin/'.$js_path : '',
			YF_PATH. 'templates/user/'.$js_path,
			MAIN_TYPE_ADMIN ? YF_PATH. 'plugins/'.$module.'/templates/admin/'.$js_path : '',
			YF_PATH. 'plugins/'.$module.'/templates/user/'.$js_path,
			MAIN_TYPE_ADMIN ? PROJECT_PATH. 'templates/admin/'.$js_path : '',
			PROJECT_PATH. 'templates/user/'.$js_path,
			SITE_PATH != PROJECT_PATH ? SITE_PATH. 'templates/user/'.$js_path : '',
		);
		$found = '';
		foreach (array_reverse($paths, true) as $path) {
			if (!strlen($path)) {
				continue;
			}
			if (file_exists($path)) {
				$found = $path;
				break;
			}
		}
		return $found;
	}
	
	/**
	*/
	public function _detect_content($content = '') {
		$content = trim($content);
		$type = false;
// TODO: domain.com/script.js
// TODO: /script.js
// TODO: script.js
		if (isset($this->assets[$content])) {
			$type = 'asset';
		} elseif (preg_match('~^(http://|https://|//)[a-z0-9]+~ims', $content)) {
			$type = 'url';
// TODO: file allowed to begin with PROJECT_PATH, SITE_PATH or YF_PATH
		} elseif (preg_match('~^/[a-z0-9\./_-]+\.js$~ims', $content) && file_exists($content)) {
			$type = 'file';
		} elseif (preg_match('~^(<script|[$;#\*/])~ims', $content) || strpos($content, PHP_EOL) !== false) {
			$type = 'inline';
		}
		return $type;
	}

	/**
	*/
	public function _strip_script_tags ($text) {
		for ($i = 0; $i < 10; $i++) {
			if (strpos($text, 'script') === false) {
				break;
			}
			$text = preg_replace('~^<script[^>]*?>~ims', '', $text);
			$text = preg_replace('~</script>$~ims', '', $text);
		}
		return $text;
	}
}