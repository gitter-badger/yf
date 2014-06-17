<?php

class yf_notifications {
//	var $_CACHE_CHECK_TTL = 1;
// todo: cache
	function check() {
		main()->NO_GRAPHICS = true;
		$out = array();
		
		$online_class = _class('online_users','classes/');
		$online_class->process();
		// user part
//		$cache_name = __CLASS__.'|'.__FUNCTION__."|".$online_class->online_user_id."|".$online_class->online_user_type;
//		if (true || cache()->get($cache_name) != 'OK') {
			$R = db()->get_all("SELECT `notification_id` FROM `".db('notifications_receivers')."` WHERE `receiver_id`='".$online_class->online_user_id."' AND `receiver_type`='".$online_class->online_user_type."' AND `is_read`=0");
			$notifications = array();
			foreach ((array)$R as $A) {
				$notifications[] = $A['notification_id'];
			}
			if (count($notifications) != 0) {
				$out = db()->get_all("SELECT `id`,`title`,`content`,`add_date` FROM `".db('notifications')."` WHERE `id` IN (".implode(",",$notifications).")");
			}
//			cache()->set($cache_name,'OK',$this->_CACHE_CHECK_TTL);
//		}
		echo json_encode($out);
		exit;
	}
	
	function read() {
		$online_class = _class('online_users','classes/');
		$online_class->process();
		
		main()->NO_GRAPHICS = true;
		db()->query("UPDATE `".db('notifications_receivers')."` SET `is_read`=1 WHERE `receiver_id`='".$online_class->online_user_id."' AND `receiver_type`='".$online_class->online_user_type."' AND `notification_id`=".intval($_POST['id']));
		echo "OK";
		exit;
	}
    
	function _prepare () {
		$tpl = file_get_contents(INCLUDE_PATH."templates/user/notifications/notifications_js.stpl");
		$func_name = "url_".main()->type;
		$obj_name = main()->type == 'admin' ? 'notifications_admin' : 'notifications';
		$replace = array(
			"url_check" => $func_name("/{$obj_name}/check"),
			"url_read" => $func_name("/{$obj_name}/read"),
		);
		require_js(tpl()->parse_string($tpl, $replace));		
		require_css(INCLUDE_PATH."js/pnotify.custom.min.css");
		require_js(INCLUDE_PATH."js/pnotify.custom.min.js");
	}
	
}

	
