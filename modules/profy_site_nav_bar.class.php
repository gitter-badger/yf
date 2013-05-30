<?php

//-----------------------------------------------------------------------------
// Navigation bar handler
class profy_site_nav_bar {

	/** @var string */
	var $HOOK_NAME		= "_nav_bar_items";
	/** @var string */
	var $HOME_LOCATION	= "./";
	/** @var bool */
	var $AUTO_TRANSLATE = true;
	/** @var bool */
	var $SHOW_NAV_BAR	= true;

	/**
	* Catch missing method call
	*/
	function __call($name, $arguments) {
		trigger_error(__CLASS__.": No method ".$name, E_USER_WARNING);
		return false;
	}

	//-----------------------------------------------------------------------------
	// Display navigation bar
	function _show () {
		$items = array();
		// Switch between specific actions
		if (in_array($_GET["object"], array("", "home_page"))) {
			// Empty
		} else {
			if (!in_array($_GET["action"], array("", "show"))) {
				$items[]	= $this->_nav_item($this->_decode_from_url($_GET["object"]), "./?object=".$_GET["object"]);
				$items[]	= $this->_nav_item($this->_decode_from_url($_GET["action"]));
			} else {
				$items[]	= $this->_nav_item($this->_decode_from_url($_GET["object"]));
			}
		}
		// Add first item to all valid items
		array_unshift($items, $this->_nav_item("Home", $this->HOME_LOCATION));
		// Try to get items from hook "_nav_bar_items"
		if (!empty($this->HOOK_NAME)) {
			$CUR_OBJ = module($_GET["object"]);
			if (is_object($CUR_OBJ) && method_exists($CUR_OBJ, $this->HOOK_NAME)) {
				$hook_params = array(
					"nav_bar_obj"	=> $this,
					"items"			=> $items,
				);
				$try_array = array($CUR_OBJ, $this->HOOK_NAME);
				$hooked_items = call_user_func($try_array, $hook_params);
			}
		}
		// Do not show nav bar if hooked code set that
		if (!$this->SHOW_NAV_BAR) {
			return false;
		}
		// Stop here if gathered nothing
		if (count($items) == 1) {
			return false;
		}
		// Hook have max priority
		if (!empty($hooked_items)) {
			$items = $hooked_items;
		}
		// Process template
		$replace = array(
			"items"			=> implode(tpl()->parse(__CLASS__."/div"), $items),
			"is_logged_in"	=> intval((bool) (isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0)),
			"bookmark_page"	=> isset($bookmark_page_code) ? $bookmark_page_code : "",
		);
		return tpl()->parse(__CLASS__."/main", $replace);
	}

	//-----------------------------------------------------------------------------
	// Display navigation bar item
	function _nav_item ($name = "", $nav_link = "") {
		if ($this->AUTO_TRANSLATE) {
			$name = t($name);
		}
		$replace = array(
			"name"			=> _prepare_html($name),
			"link"			=> $nav_link,
			"as_link"		=> !empty($nav_link) ? 1 : 0,
			"is_logged_in"	=> intval((bool) (isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0)),
		);
		return tpl()->parse(__CLASS__."/item", $replace);
	}

	//-----------------------------------------------------------------------------
	// Decode name
	function _decode_from_url ($text = "") {
		return ucwords(str_replace("_", " ", $text));
	}

	//-----------------------------------------------------------------------------
	// Encode name
	function _encode_for_url ($text = "") {
		return strtolower(str_replace(" ", "_", $text));
	}
}
