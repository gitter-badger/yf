<?php

/**
* Manage shop sub module
*/
class yf_manage_shop_settings {

	/**
	* Show orders
	*/
	function show_settings() {
		$replace = array(
			"items"		=> "settings",
		);
		return tpl()->parse("manage_shop/settings_show", $replace);
	}
}
