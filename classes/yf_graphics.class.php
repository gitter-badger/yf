<?php

/**
* Core content-related methods stored here
*
* @package		YF
* @author		YFix Team <yfix.dev@gmail.com>
* @version		1.0
*/
class yf_graphics {

	/** @var bool Add pages names to the title */
	public $ADD_TITLE_PAGES			= true;
	/** @var bool Show auto-parsed (and tried to translate) task name */
	public $SHOW_AUTO_TASK_NAME		= false;
	/** @var string Sub-modules dir */
	public $SUB_MODULES_PATH		= 'classes/graphics/';
	/** @var string Path to icons */
	public $ICONS_PATH				= 'uploads/icons/';
	/** @var string Default HTML Title tag contents */
	public $META_TITLE				= '';
	/** @var string Default HTML Meta tag 'keywords' */
	public $META_KEYWORDS			= '';
	/** @var string Default HTML Meta tag 'description' */
	public $META_DESCRIPTION		= '';
	/** @var bool Enable quick menu */
	public $QUICK_MENU_ENABLED		= true;
	/** @var bool Menu hide links to disabled modules */
	public $MENU_HIDE_INACTIVE_MODULES	= false;
	/** @var bool */
	public $NOT_FOUND_RAISE_WARNING	= true;
	/** @var bool */
	public $HEADER_POWERED_BY		= true;
	/** @var bool */
	public $JS_CONSOLE_ALLOW		= true;
	/** @var string Required for the compatibility with old main class */
	public $MEDIA_PATH				= '';

	/**
	* Catch missing method call
	*/
	function __call($name, $args) {
		return main()->extend_call($this, $name, $args);
	}

	/**
	* Framework constructor
	*/
	function _init() {
		// Default user group
		if (empty($_SESSION['user_group'])) {
			$_SESSION['user_group'] = 1;
		}
		// Try to assign class properties from global settings
		$add_title_pages = conf('add_title_pages');
		if (isset($add_title_pages)) {
			$this->ADD_TITLE_PAGES = $add_title_pages;
		}
		// Force hide inactive menu items inside admin section
		if (MAIN_TYPE_ADMIN) {
			$this->MENU_HIDE_INACTIVE_MODULES = true;
		}
		$this->MEDIA_PATH = WEB_PATH;
		if (defined('MEDIA_PATH')) {
			$this->MEDIA_PATH = MEDIA_PATH;
		}
	}

	/**
	* Show CSS
	*/
	function show_css () {
		return _class('core_css')->show_css();
	}

	/**
	* Try to load CSS file with inheritance
	*/
	function _show_css_file ($css = '', $keyword = '', $option = '') {
		return _class('core_css')->_show_css_file($css, $keyword, $option);
	}

	/**
	* Try to load CSS file with inheritance
	*/
	function _load_css_file ($name = '') {
		return _class('core_css')->_load_css_file($name);
	}

	/**
	* Common javascript loader
	*/
	function show_js () {
		return _class('core_js')->show_js();
	}

	/**
	* Show site title
	*/
	function show_site_title () {
		if (defined('SITE_ADVERT_NAME')) {
			$title = SITE_ADVERT_NAME;
		}
		if (conf('SITE_ADVERT_NAME')) {
			$title = conf('SITE_ADVERT_NAME');
		}
		if (defined('SITE_TITLE')) {
			$title = SITE_TITLE;
		}
		if (!empty($this->META_TITLE)) {
			$title = $this->META_TITLE;
		}
		// For compatibility with old versions
		$website_name = conf('website_name');
		if (strlen($website_name)) {
			$title = _prepare_html($website_name);
		}
		// Add pages names to the title
		if ($this->ADD_TITLE_PAGES) {
			if (strlen($_GET['object']) && $_GET['object'] != 'static_pages') {
				$title .= ' :: '._ucfirst(t($_GET['object']));
			}
		}
		// Override by hook method
		$method_name = '_site_title';
// TODO: need to check permissions at first
#		$obj = module($_GET['object']);
		if (method_exists($obj, $method_name)) {
			$title = _prepare_html($obj->$method_name($title));
		}
		// Force by global var
		$conf_title = conf('site_title');
		if ($conf_title) {
			$title = _prepare_html($conf_title);
		}
		return $title;
	}

	/**
	* Show metatags
	*/
	function show_metatags () {
		$charset = conf('charset');
		if (!$charset) {
			$charset = 'utf-8';
		}
		$meta_keywords = conf('meta_keywords');
		if (!$meta_keywords) {
			$meta_keywords = $this->META_KEYWORDS;
		}
		$meta_description = conf('meta_description');
		if (!$meta_description) {
			$meta_description = $this->META_DESCRIPTION;
		}
		$meta = array(
			'charset'		=> $charset,
			'keywords'		=> $meta_keywords,
			'description'	=> $meta_description,
		);
		// Override by hook method
		$method_name = '_hook_meta_tags';
// TODO: need to check permissions at first
#		$obj = module($_GET['object']);
		if (method_exists($obj, $method_name)) {
			$meta = _prepare_html($obj->$method_name($meta));
		}
		if (DEBUG_MODE) {
			debug('_DEBUG_META', $meta);
		}
		$replace = array(
			'charset'		=> _prepare_html($meta['charset']),
			'keywords'		=> $meta['keywords'],
			'description'	=> $meta['description'],
		);
		return tpl()->parse('system/meta_tags', $replace);
	}

	/**
	* Show menu (alias for the '_show_menu')
	*/
	function show_menu ($params) {
		return $this->_show_menu($params);
	}

	/**
	* Display main 'center' block contents
	*/
	function show_center () {
		return _class('core_blocks')->show_center();
	}

	/**
	* Alias for the '_show_block'
	*/
	function show_block ($params = array()) {
		return $this->_show_block($params);
	}

	/**
	* Show custom block contents
	*/
	function _show_block ($input = array()) {
		return _class('core_blocks')->_show_block($input);
	}

	/**
	* Action to on denied block
	*/
	function _action_on_block_denied ($block_name = '') {
		return _class('core_blocks')->_action_on_block_denied($block_name);
	}

	/**
	* Try to find id of the center block
	*/
	function _get_center_block_id() {
		return _class('core_blocks')->_get_center_block_id();
	}

	/**
	* Load array of blocks rules
	*/
	function _load_blocks_rules () {
		return _class('core_blocks')->_load_blocks_rules();
	}

	/**
	* Check rights for blocks
	*/
	function _check_block_rights ($block_id = 0, $OBJECT = '', $ACTION = '') {
		return _class('core_blocks')->_check_block_rights($block_id, $OBJECT, $ACTION);
	}

	/**
	* Try to run center block module/method if allowed
	*/
	function prefetch_center() {
		return _class('core_blocks')->prefetch_center();
	}

	/**
	* Main $_GET tasks handler
	*/
	function tasks($allowed_check = false) {
		return _class('core_blocks')->tasks($allowed_check);
	}

	/**
	* Method that allows to change standard tasks mapping (if needed)
	*/
	function _route_request() {
		return _class('router')->_route_request();
	}

	/**
	* Welcome message method
	*/
	function show_welcome () {
		// For authorized admins only
		if (MAIN_TYPE_ADMIN) {
			$login_time = $_SESSION['admin_login_time'];
			$admin_id	= (int)main()->ADMIN_ID;
			$admin_group= (int)main()->ADMIN_GROUP;
			if ($admin_id && $admin_group) {
				$admin_info		= db()->query_fetch('SELECT * FROM '.db('admin').' WHERE id='.$admin_id);
				$admin_groups	= main()->get_data('admin_groups');

				$body .= tpl()->parse('system/admin_welcome', array(
					'id'		=> intval($admin_id),
					'name'		=> _prepare_html($admin_info['first_name'].' '.$admin_info['last_name']),
					'group'		=> _prepare_html(t($admin_groups[$admin_group])),
					'time'		=> _format_date($login_time),
					'edit_link'	=> './?object=admin_account',
				));
				if ($_SESSION['admin_prev_info']) {
					$body .= '<li><a href="./?task=login&id=prev_info"><i class="icon icon-arrow-up"></i> '.t('Login back').'</a></li>';
				}
			}
		// For authorized users only
		} elseif (MAIN_TYPE_USER) {
			$login_time = $_SESSION['user_login_time'];
			$user_id	= (int)main()->USER_ID;
			$user_group	= (int)main()->USER_GROUP;
			if ($user_id && $user_group) {
				$user_info 		= user($user_id);
				$user_groups	= main()->get_data('user_groups');

				$body .= tpl()->parse('system/user_welcome', array(
					'id'	=> intval($user_info['id']),
					'name'	=> _prepare_html(_display_name($user_info)),
					'group'	=> _prepare_html(t($user_groups[$user_group])),
					'time'	=> _format_date($login_time),
				));
			}
		}
		return $body;
	}

	/**
	* Welcome message for the admin section
	*/
	function show_welcome2 () {
		if (MAIN_TYPE_ADMIN) {
			$body = t('You logged in as %user at %date', array('%date' => date('H:i:s', $_SESSION['admin_login_time']), '%user' => t('admin')));
		}
		return $body;
	}

	/**
	* @deprecated
	*/
	function _show_se_keywords ($input = '') {
		return false;
	}

	/**
	* @deprecated
	*/
	function _set_se_keywords () {
		return false;
	}

	/**
	* Show menu
	*/
	function _show_menu ($input = array()) {
		return _class('core_menu')->_show_menu($input);
	}

	/**
	* Template for the custom class method for menu block (useful to inherit)
	*/
	function _custom_menu_items($params = array()) {
		return _class('core_menu')->_custom_menu_items($params);
	}

	/**
	* Get menu items ordered array (recursively)
	*/
	function _recursive_get_menu_items($menu_id = 0, $skip_item_id = 0, $parent_id = 0, $level = 0) {
		return _class('core_menu')->_recursive_get_menu_items($menu_id, $skip_item_id, $parent_id, $level);
	}

	/**
	* Show help tip block
	*/
	function _show_help_tip ($params = array()) {
		$tip_id		= $params['tip_id'];
		$tip_type	= !empty($params['tip_type']) ? intval($params['tip_type']) : 1;
		if (empty($tip_id)) {
			return false;
		}
		if (!isset($this->_avail_tips)) {
			$this->_avail_tips = (array)main()->get_data('tips');
		}
		$r = $params['replace'];
		$legend = '';
		$var = $tip_id[0] == '#' ? substr($tip_id, 1) : '';
		if ($var && isset($r[$var])) {
			$legend = $r[$var];
		} elseif (isset($this->_avail_tips[$tip_id])) {
			$legend = $this->_avail_tips[$tip_id];
		} else {
			$legend = $tip_id;
		}
		return tpl()->parse('system/help_tip', array(
			'tip_id'	=> _prepare_html($tip_id),
			'tip_type'	=> intval($tip_type),
			'legend'	=> _prepare_html($legend),
		));
	}

	/**
	* Show inline tip block
	*/
	function _show_inline_tip ($params = array()) {
		$params['tip_id'] = $params['text'];
		return $this->_show_help_tip($params);
	}

	/**
	* Prepare help for show
	*/
	function show_help(){
		$module_name = $_GET['object'];
		$action_name = $_GET['action'];
		$replace = array(
			'action'	=> $action_name,
		);
		$STPL_NAME = $module_name.'/help';
		if (tpl()->_stpl_exists($STPL_NAME)) {
			$body = tpl()->parse($STPL_NAME, $replace);
		}
		return tpl()->parse('system/help_wrapper', array('body' => nl2br(trim($body))));
	}

	/**
	* Get html code for external bookmarking (Yahoo, Digg, etc)
	*/
	function _show_bookmarks_button($title = '', $url = '', $only_links = 1) {
		return _class('graphics_bookmarks', $this->SUB_MODULES_PATH)->_show_bookmarks_button($title, $url, $only_links);
	}

	/**
	* Get html code for external bookmarking (Yahoo, Digg, etc)
	*/
	function _show_rss_button($feed_name = '', $feed_link = '', $only_links = 1) {
		return _class('graphics_bookmarks', $this->SUB_MODULES_PATH)->_show_rss_button($feed_name, $feed_link, $only_links);
	}

	/**
	* Send main headers
	*/
	function _send_main_headers($content_length = 0) {
		if (headers_sent() || conf('no_headers')) {
			return false;
		}
		if ($this->HEADER_POWERED_BY) {
			header('X-Powered-By: YF');
		}
		header('Content-Type:text/html; charset='.conf('charset'));
		header('Content-language: '.conf('language'));
		if (tpl()->REWRITE_MODE && MAIN_TYPE_USER && !main()->NO_CACHE_HEADERS) {
			// To emulate static pages need these headers (Tells that page is modified only one time per day)
			header('Last-Modified: '. gmdate('D, d M Y 00:01:01') . ' GMT');
			header('Content-Length: '.intval($content_length));
		} else {
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
			header('Cache-Control: post-check=0, pre-check=0', false); // HTTP/1.1
			header('Pragma: no-cache'); // HTTP/1.0
		}
		if (main()->is_ajax()/* || main()->is_post()*/) {
			header('X-Robots-Tag: noindex,nofollow,noarchive,nosnippet');
		}
		$this->_send_custom_http_headers();
	}

	/**
	*/
	function _send_custom_http_headers() {
		// Override headers
		$conf_headers = conf('http_headers');
		if (is_array($conf_headers)) {
			foreach ($conf_headers as $name => $value) {
				$name = trim($name);
				if (!$name) {
					continue;
				}
				$value = str_replace(PHP_EOL, ' ', trim($value));
				header($name.': '.$value, true);
			}
		}
	}

	/**
	* Generate typos for the given text
	*/
	function _generate_typos($params = array()) {
		return _class('graphics_typos', $this->SUB_MODULES_PATH)->get_all_with_stpl($params['text'], $params);
	}

	/**
	* Display user geo location block
	*/
	function _show_user_geo_block($params = array()) {
		if (!main()->USE_GEO_IP) {
			return false;
		}
		$geo_data	= main()->_USER_GEO_DATA;
		if (empty($geo_data)) {
			return false;
		}
		$replace = array(
			'country_name'			=> _prepare_html($geo_data['country_name']),
			'country_code_lower'	=> strtolower($geo_data['country_code']),
			'region_code'			=> _prepare_html($geo_data['region_code']),
			'region_name'			=> _prepare_html(_region_name($geo_data['region_code'], $geo_data['country_code'])),
			'city_name'				=> _prepare_html($geo_data['city_name']),
			'change_link'			=> './?object=geo_content&action=change_location',
		);
		return tpl()->parse('user_geo_block', $replace);
	}

	/**
	* Display header content (hook)
	*/
	function _show_header() {
		if (conf('no_page_header')) {
			return false;
		}
		$page_header = '';
		$page_subheader = '';
		// Display hook contents
// TODO: need to check permissions at first
#		$obj = module($_GET['object']);
		if (method_exists($obj, '_show_header')) {
			$result = $obj->_show_header();
			if (is_array($result)) {
				$page_header	= $result['header'];
				$page_subheader = $result['subheader'];
			} else {
				return strval($result);
			}
		}
		// Show default header
		if (!isset($page_header)) {
			$page_header = _ucwords(str_replace('_', ' ', $_GET['object']));
		}
		if (!isset($page_subheader)) {
			if ($_GET['action'] != 'show') {
				$page_subheader = _ucwords(str_replace('_', ' ', $_GET['action']));
			}
		}
		$replace = array(
			'header'	=> $page_header ? t($page_header) : '',
			'subheader'	=> $page_subheader ? t($page_subheader) : '',
		);
		return tpl()->parse('system/page_header', $replace);
	}

	/**
	* Display quick menu items
	*/
	function quick_menu() {
		if (!$this->QUICK_MENU_ENABLED
			|| (MAIN_TYPE_USER && (!isset($_SESSION['user_id']) || !$_SESSION['user_id']))
			|| (MAIN_TYPE_ADMIN && (!isset($_SESSION['admin_id']) || !$_SESSION['admin_id']))
		) {
			return false;
		}
		if (MAIN_TYPE_ADMIN) {
			$data[-1] = array(
				'name'	=> 'module settings',
				'url'	=> './?object=conf_editor&action=admin_modules&id='.strtolower($_GET['object']),
			);
		}
		$method_name = '_quick_menu';
// TODO: need to check permissions at first
#		$obj = module($_GET['object']);
		if (method_exists($obj, $method_name)) {
			$data2 = $obj->$method_name();
		}
		$data = (array)$data2 + (array)$data;
		if (empty($data)) {
			return false;
		}
		foreach ((array)$data as $_item) {
			if (!$_item['name']) {
				continue;
			}
			$replace2 = array(
				'item_name'	=> _prepare_html(t($_item['name'])),
				'item_url'	=> $_item['url'],
			);
			$items .= tpl()->parse('system/quick_menu_item', $replace2);

		}
		if (!$items) {
			return false;
		}
		$replace = array(
			'items'	=> $items,
		);
		return tpl()->parse('system/quick_menu_main', $replace);
	}
}
