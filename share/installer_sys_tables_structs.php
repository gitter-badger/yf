<?php
/** @var Structures for the system tables */
$data = my_array_merge((array)$data, array(
"admin"				=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`first_name` varchar(64) NOT NULL default '',
	`last_name` varchar(64) NOT NULL default '',
	`login` varchar(64) NOT NULL default '',
	`email` varchar(255) NOT NULL default '',
	`password` varchar(64) NOT NULL default '',
	`group` int(10) unsigned NOT NULL default '0',
	`active` enum('0','1') NOT NULL default '0',
	`add_date` int(10) unsigned NOT NULL default '0',
	`last_login` int(10) unsigned NOT NULL default '0',
	`num_logins` smallint(6) unsigned NOT NULL default '0',
	`go_after_login` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `login` (`login`)
",
"admin_groups"		=> "
	`id` tinyint(3) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`active` enum('1','0') NOT NULL default '1',
	`go_after_login` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`id`)
",
"admin_modules"		=> "
	`id` tinyint(3) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`description` varchar(255) NOT NULL,
	`version` varchar(16) NOT NULL,
	`author` varchar(255) NOT NULL,
	`active` enum('0','1') NOT NULL,
	PRIMARY KEY  (`id`)
",
"banned_ips"		=> "
	`ip` varchar(20) NOT NULL default '',
	`admin_id` int(10) NOT NULL default '0',
	`time` int(10) NOT NULL default '0',
	`ban_type` varchar(16) NOT NULL default '',
	PRIMARY KEY  (`ip`)
",
"block_rules"		=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`block_id` smallint(5) unsigned NOT NULL default '0',
	`rule_type` enum('DENY','ALLOW') NOT NULL default 'DENY',
	`user_groups` varchar(255) NOT NULL default '',
	`methods` text NOT NULL default '',
	`themes` text NOT NULL default '',
	`locales` text NOT NULL default '',
	`others` text NOT NULL default '',
	`active` enum('1','0') NOT NULL default '1',
	`order` int(10) unsigned NOT NULL default '0',
	`site_ids` varchar(255) NOT NULL,
	`server_ids` varchar(255) NOT NULL,
	PRIMARY KEY  (`id`)
",
"blocks"			=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(64) NOT NULL default '',
	`desc` varchar(255) NOT NULL default '',
	`stpl_name` varchar(255) NOT NULL,
	`method_name` varchar(255) NOT NULL,
	`type` enum('user','admin') NOT NULL,
	`active` enum('1','0') NOT NULL default '1',
	PRIMARY KEY  (`id`)
",
"cache"				=> "
	`key` varchar(64) NOT NULL default '',
	`value` text NOT NULL,
	`time` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY  (`key`)
",
"cache_info"		=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`object` varchar(32) NOT NULL default '',
	`action` varchar(32) NOT NULL default '',
	`query_string` varchar(128) NOT NULL default '',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`group_id` tinyint(3) unsigned NOT NULL default '1',
	`hash` varchar(32) NOT NULL default '',
	PRIMARY KEY (`id`),
	UNIQUE KEY `object` (`object`,`action`,`query_string`,`site_id`)
",
"catalog_items" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`cat_id` int(10) unsigned NOT NULL,
	`parent_id` int(10) unsigned NOT NULL,
	`name` varchar(255) NOT NULL,
	`desc` varchar(255) NOT NULL,
	`url` varchar(255) NOT NULL,
	`type_id` int(10) unsigned NOT NULL,
	`order` int(10) unsigned NOT NULL,
	`active` enum('1','0') NOT NULL,
	`user_groups` varchar(255) NOT NULL,
	`other_info` text NOT NULL,
	`icon` varchar(255) NOT NULL,
	`featured` tinyint(3) unsigned NOT NULL,
	PRIMARY KEY	(`id`)
",
"catalog_node_types" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL,
	`desc` varchar(255) NOT NULL,
	`code` text NOT NULL,
	`active` enum('1','0') NOT NULL,
	PRIMARY KEY	(`id`)
",
"categories"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(64) NOT NULL,
	`desc` varchar(255) NOT NULL,
	`type` enum('user','admin') NOT NULL,
	`active` enum('1','0') NOT NULL,
	`stpl_name` varchar(255) NOT NULL,
	`method_name` varchar(255) NOT NULL,
	`custom_fields` text NOT NULL,
	PRIMARY KEY  (`id`)
",
"category_items"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`cat_id` int(10) unsigned NOT NULL,
	`parent_id` int(10) unsigned NOT NULL,
	`name` varchar(255) NOT NULL,
	`desc` varchar(255) NOT NULL,
	`meta_keywords` text collate utf8_unicode_ci NOT NULL,
	`meta_desc` text collate utf8_unicode_ci NOT NULL,
	`url` varchar(255) NOT NULL,
	`type_id` int(10) unsigned NOT NULL,
	`order` int(10) unsigned NOT NULL,
	`active` enum('1','0') NOT NULL,
	`user_groups` varchar(255) NOT NULL,
	`other_info` text NOT NULL,
	`icon` varchar(255) NOT NULL,
	`featured` tinyint(3) unsigned NOT NULL,
	PRIMARY KEY  (`id`)
",
"code_source"		=> "
	`id` int(10) NOT NULL auto_increment,
	`keyword` varchar(255) NOT NULL default '',
	`source` text NOT NULL,
	PRIMARY KEY  (`id`)
",
"conf"		=> "
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`value` text NOT NULL,
	`desc` text NOT NULL,
	`active` enum('1','0') NOT NULL DEFAULT '1',
	`linked_table` varchar(255) NOT NULL,
	`linked_data` varchar(255) NOT NULL,
	`linked_method` varchar(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`)
",
"conf_items"		=> "
	`id` int(10) NOT NULL auto_increment,
	`title` text NOT NULL,
	`desc` text NOT NULL,
	`group_id` smallint(3) NOT NULL default '0',
	`group_name` varchar(255) NOT NULL default '',
	`type` varchar(255) NOT NULL default '',
	`keyword` text NOT NULL,
	`value` text NOT NULL,
	`default` text NOT NULL,
	`extra` text NOT NULL,
	`eval_php` text NOT NULL,
	`position` smallint(3) NOT NULL default '0',
	`display` tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`)
",
"custom_bbcode"		=> "
	`id` int(10) NOT NULL auto_increment,
	`title` varchar(255) NOT NULL default '',
	`desc` text NOT NULL,
	`tag` varchar(255) NOT NULL default '',
	`replace` text NOT NULL,
	`useoption` tinyint(1) NOT NULL default '0',
	`example` text NOT NULL,
	PRIMARY KEY  (`id`)
",
"custom_replace_rules"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`methods` text NOT NULL default '',
	`query_string` varchar(255) NOT NULL default '',
	`language` varchar(12) NOT NULL default '0',
	`user_groups` varchar(255) NOT NULL default '',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`tag_id` smallint(5) unsigned NOT NULL default '0',
	`tag_replace` text NOT NULL,
	`order` int(10) unsigned NOT NULL default '0',
	`active` enum('1','0') NOT NULL default '1',
	`eval_code` enum('1','0') NOT NULL,
	PRIMARY KEY (`id`)
",
"custom_replace_tags"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(64) NOT NULL default '',
	`desc` varchar(255) NOT NULL default '',
	`pattern_find` text NOT NULL,
	`pattern_replace` text NOT NULL,
	`active` enum('1','0') NOT NULL default '1',
	PRIMARY KEY (`id`)
",
"custom_replace_words"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`key` varchar(255) NOT NULL,
	`value` text NOT NULL,
	`desc` text NOT NULL,
	`active` enum('1','0') NOT NULL,
	PRIMARY KEY (`id`)
	/** DEFAULT CHARSET=UTF8 **/ 
",
"locale_langs"		=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`locale` varchar(12) NOT NULL default '',
	`name` varchar(64) NOT NULL default '',
	`charset` varchar(32) NOT NULL,
	`active` enum('1','0') NOT NULL default '0',
	`is_default` enum('0','1') NOT NULL default '0',
	PRIMARY KEY (`id`),
	UNIQUE KEY `locale` (`locale`)
",
"locale_translate"	=> "
	`var_id` int(10) unsigned NOT NULL default '0',
	`value` text NOT NULL,
	`locale` varchar(12) NOT NULL default '',
	KEY `lang` (`locale`),
	KEY `var_id` (`var_id`)
	/** DEFAULT CHARSET=UTF8 **/ 
",
//			,PRIMARY KEY (`locale`,`var_id`)
"locale_vars"		=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`value` text NOT NULL,
	`location` text NOT NULL,
	PRIMARY KEY (`id`)
	/** DEFAULT CHARSET=UTF8 **/ 
",
"locale_user_tr"		=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`site_id` smallint(4) unsigned NOT NULL default '0',
	`user_id` int(10) unsigned NOT NULL default '0',
	`var` varchar(255) NOT NULL default '',
	`translation` text NOT NULL default '',
	`locale` varchar(12) NOT NULL default '',
	`last_update` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY (`id`),
	UNIQUE KEY `locale_var_user_id` (`user_id`,`var`,`locale`)
	/** DEFAULT CHARSET=UTF8 **/ 
",
"log_admin_auth" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`admin_id` int(10) unsigned NOT NULL default '0',
	`login` varchar(255) NOT NULL default '',
	`group` int(10) unsigned NOT NULL default '0',
	`date` int(10) unsigned NOT NULL default '0',
	`session_id` varchar(32) NOT NULL default '',
	`ip` varchar(16) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`activity` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `ip` (`ip`),
	KEY `admin_id` (`admin_id`),
	KEY `date` (`date`)
",
"log_admin_auth_fails"	=> "
	`time` decimal(13,3) unsigned NOT NULL default '0.000',
	`ip` varchar(16) NOT NULL default '',
	`login` varchar(64) NOT NULL default '',
	`pswd` varchar(64) NOT NULL default '',
	`reason` char(1) NOT NULL default 'w',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`time`)
",
"log_admin_exec" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`date` int(10) unsigned NOT NULL default '0',
	`query_string` varchar(255) NOT NULL default '',
	`request_uri` varchar(255) NOT NULL,
	`exec_time` float NOT NULL default '0',
	`num_dbq` smallint(5) unsigned NOT NULL default '0',
	`page_size` int(10) unsigned NOT NULL default '0',
	`admin_id` int(10) unsigned NOT NULL default '0',
	`admin_group` tinyint(3) unsigned NOT NULL,
	`ip` varchar(16) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `admin_group` (`admin_group`)
",
"log_auth" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`user_id` int(10) unsigned NOT NULL default '0',
	`login` varchar(255) NOT NULL default '',
	`group` int(10) unsigned NOT NULL default '0',
	`date` int(10) unsigned NOT NULL default '0',
	`session_id` varchar(32) NOT NULL default '',
	`ip` varchar(16) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`activity` int(10) unsigned NOT NULL default '0',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `ip` (`ip`),
	KEY `user_id` (`user_id`),
	KEY `date` (`date`)
",
"log_auth_fails"	=> "
	`time` decimal(13,3) unsigned NOT NULL default '0.000',
	`ip` varchar(16) NOT NULL default '',
	`login` varchar(64) NOT NULL default '',
	`pswd` varchar(64) NOT NULL default '',
	`reason` char(1) NOT NULL default 'w',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`time`)
",
"log_core_errors" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`error_level` smallint(5) unsigned NOT NULL,
	`error_text` text NOT NULL,
	`source_file` varchar(255) NOT NULL,
	`source_line` smallint(5) unsigned NOT NULL,
	`env_data` text NOT NULL,
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_group` tinyint(3) unsigned NOT NULL,
	`is_admin` enum('0','1') NOT NULL default '0',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`date` int(10) unsigned NOT NULL default '0',
	`ip` varchar(16) NOT NULL default '',
	`query_string` varchar(255) NOT NULL default '',
	`request_uri` varchar(255) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`object` varchar(255) NOT NULL default '',
	`action` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`),
	KEY `error_level` (`error_level`),
	KEY `date` (`date`)
",
"log_emails" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`email_from` varchar(255) NOT NULL default '',
	`name_from` varchar(255) NOT NULL default '',
	`email_to` varchar(255) NOT NULL default '',
	`name_to` varchar(255) NOT NULL default '',
	`subject` varchar(255) NOT NULL default '',
	`text` text NOT NULL,
	`other_options` varchar(255) NOT NULL default '',
	`source_file` varchar(255) NOT NULL default '',
	`source_line` smallint(5) unsigned NOT NULL,
	`env_data` text NOT NULL,
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_group` tinyint(3) unsigned NOT NULL,
	`is_admin` enum('0','1') NOT NULL default '0',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`date` int(10) unsigned NOT NULL default '0',
	`ip` varchar(16) NOT NULL default '',
	`query_string` varchar(255) NOT NULL default '',
	`request_uri` varchar(255) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`object` varchar(255) NOT NULL default '',
	`action` varchar(255) NOT NULL default '',
	`success` enum('0','1') NOT NULL,
	`error_text` text NOT NULL,
	`send_time` float unsigned NOT NULL,
	`mail_debug` enum('0','1') NOT NULL,
	`used_mailer` varchar(32) NOT NULL,
	PRIMARY KEY	(`id`),
	KEY `email_from` (`email_from`),
	KEY `email_to` (`email_to`),
	KEY `date` (`date`)
",
"log_exec" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`date` int(10) unsigned NOT NULL default '0',
	`query_string` varchar(255) NOT NULL default '',
	`request_uri` varchar(255) NOT NULL,
	`exec_time` float NOT NULL default '0',
	`num_dbq` smallint(5) unsigned NOT NULL default '0',
	`page_size` int(10) unsigned NOT NULL default '0',
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_group` tinyint(3) unsigned NOT NULL,
	`ip` varchar(16) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`is_admin` enum('0','1') NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `user_group` (`user_group`)
",
"log_exec_daily" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`day` DATE NOT NULL,
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`hits` int(10) unsigned NOT NULL default '0',
	`member_hits` int(10) unsigned NOT NULL default '0',
	`spider_hits` int(10) unsigned NOT NULL default '0',
	`cache_hits` int(10) unsigned NOT NULL default '0',
	`hosts` int(10) unsigned NOT NULL default '0',
	`member_hosts` int(10) unsigned NOT NULL default '0',
	`spider_hosts` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	UNIQUE KEY	(`day`,`site_id`)
",
"log_hourly_exec" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`start_date` int(10) unsigned NOT NULL default '0',
	`avg_exec_time` float NOT NULL default '0',
	`hits` int(10) unsigned NOT NULL default '0',
	`hosts` int(10) unsigned NOT NULL default '0',
	`traffic` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	UNIQUE KEY `start_date` (`start_date`)
",
"log_img_resizes" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`source_path` varchar(255) NOT NULL,
	`source_file_size` int(10) unsigned NOT NULL,
	`source_x` int(10) unsigned NOT NULL,
	`source_y` int(10) unsigned NOT NULL,
	`result_path` varchar(255) NOT NULL,
	`result_file_size` int(10) unsigned NOT NULL,
	`result_x` int(10) unsigned NOT NULL,
	`result_y` int(10) unsigned NOT NULL,
	`limit_x` int(10) unsigned NOT NULL,
	`limit_y` int(10) unsigned NOT NULL,
	`other_options` varchar(255) NOT NULL default '',
	`source_file` varchar(255) NOT NULL default '',
	`source_line` smallint(5) unsigned NOT NULL,
	`env_data` text NOT NULL,
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_group` tinyint(3) unsigned NOT NULL,
	`is_admin` enum('0','1') NOT NULL default '0',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`date` int(10) unsigned NOT NULL default '0',
	`ip` varchar(16) NOT NULL default '',
	`query_string` varchar(255) NOT NULL default '',
	`request_uri` varchar(255) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`object` varchar(255) NOT NULL default '',
	`action` varchar(255) NOT NULL default '',
	`success` enum('0','1') NOT NULL,
	`error_text` text NOT NULL,
	`process_time` float unsigned NOT NULL,
	`used_lib` varchar(32) NOT NULL,
	`tried_libs` varchar(32) NOT NULL,
	PRIMARY KEY	(`id`),
	KEY `date` (`date`)
",
"log_maxmind_phone_verify"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`owner_id` int(11) unsigned NOT NULL,
	`check_type` enum('i','v') NOT NULL,
	`phone_num` varchar(32) NOT NULL,
	`phone_type` tinyint(3) unsigned NOT NULL,
	`verify_code` varchar(4) NOT NULL,
	`ref_id` varchar(32) NOT NULL,
	`success` enum('0','1') NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`process_time` int(10) unsigned NOT NULL,
	`error_text` text NOT NULL,
	`site_id` tinyint(3) unsigned NOT NULL,
	`user_id` int(10) unsigned NOT NULL,
	`user_group` tinyint(3) unsigned NOT NULL,
	`is_admin` enum('0','1') NOT NULL,
	`ip` varchar(32) NOT NULL,
	`query_string` text NOT NULL,
	`user_agent` varchar(255) NOT NULL,
	`referer` varchar(255) NOT NULL,
	`request_uri` varchar(255) NOT NULL,
	`object` varchar(255) NOT NULL,
	`action` varchar(255) NOT NULL,
	`server_answer` varchar(255) NOT NULL,
	PRIMARY KEY  (`id`)
",
"log_ssh_action"	=> "
	`microtime`		decimal(13,3) unsigned NOT NULL default '0.000',
	`server_id`		varchar(64) NOT NULL default '',
	`init_type`		enum('user','admin') default 'user',
	`action`		varchar(32) NOT NULL default '',
	`comment`		varchar(255) NOT NULL default '',
	`get_object`	varchar(32) NOT NULL default '',
	`get_action`	varchar(32) NOT NULL default '',
	`user_id`		int(11) unsigned NOT NULL default '0',
	`user_group`	tinyint(2) unsigned NOT NULL default '0',
	`ip`			varchar(32) NOT NULL default '',
	KEY  (`microtime`),
	KEY  (`server_id`)
",
"log_tags"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`tag_id` int(10) unsigned NOT NULL,
	`object_id` int(10) unsigned NOT NULL,
	`object_name` varchar(64) NOT NULL default '',
	`owner_id` int(10) unsigned NOT NULL,
	`user_id` int(10) unsigned NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`text` varchar(128) NOT NULL default '',
	`site_id` int(10) unsigned NOT NULL,
	`ip` varchar(16) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`request_uri` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`id`)
",
"log_user_errors" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`error_level` smallint(5) unsigned NOT NULL,
	`error_text` text NOT NULL,
	`source_file` varchar(255) NOT NULL,
	`source_line` smallint(5) unsigned NOT NULL,
	`env_data` text NOT NULL,
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_group` tinyint(3) unsigned NOT NULL,
	`is_admin` enum('0','1') NOT NULL default '0',
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`date` int(10) unsigned NOT NULL default '0',
	`ip` varchar(16) NOT NULL default '',
	`query_string` varchar(255) NOT NULL default '',
	`request_uri` varchar(255) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`object` varchar(255) NOT NULL default '',
	`action` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`),
	KEY `error_level` (`error_level`),
	KEY `date` (`date`)
",
"log_webshell_action" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`microtime` decimal(13,3) unsigned NOT NULL default '0.000',
	`server_id` int(11) NOT NULL,
	`user_id` int(11) NOT NULL,
	`action` text NOT NULL,
	PRIMARY KEY	(`id`)
",
"menu_items"		=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`menu_id` int(10) unsigned NOT NULL,
	`parent_id` int(10) unsigned NOT NULL,
	`name` varchar(255) NOT NULL,
	`location` varchar(255) NOT NULL,
	`type_id` int(10) unsigned NOT NULL,
	`order` int(10) unsigned NOT NULL,
	`active` enum('1','0') NOT NULL,
	`user_groups` varchar(255) NOT NULL,
	`site_ids` varchar(255) NOT NULL,
	`server_ids` varchar(255) NOT NULL,
	`cond_code` varchar(255) NOT NULL default '',
	`icon` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
",
"menus"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(64) NOT NULL,
	`desc` varchar(255) NOT NULL,
	`type` enum('user','admin') NOT NULL,
	`active` enum('1','0') NOT NULL,
	`stpl_name` varchar(255) NOT NULL,
	`method_name` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
",
"online"			=> "
	`id` varchar(32) NOT NULL default '',
	`user_id` int(10) unsigned NOT NULL,
	`user_group` int(10) unsigned NOT NULL,
	`time` int(10) unsigned NOT NULL default '0',
	`type` enum('user','admin') NOT NULL,
	`ip` varchar(16) NOT NULL,
	`user_agent` varchar(255) NOT NULL,
	`query_string` varchar(255) NOT NULL,
	`site_id` tinyint(3) unsigned NOT NULL,
	PRIMARY KEY	(`id`),
	KEY `user_id` (`user_id`)
	/** ENGINE=MEMORY **/ 
",
"revisions" => "
	`id` int(10) unsigned NOT NULL auto_increment,
	`object_name` varchar(64) NOT NULL,
	`object_id` varchar(64) NOT NULL,
	`old_text` text NOT NULL,
	`new_text` text NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`user_id` int(10) unsigned NOT NULL,
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`server_id` tinyint(3) unsigned NOT NULL default '0',
	`ip` char(15) NOT NULL,
	`comment` varchar(255) NOT NULL,
	PRIMARY KEY	(`id`)
",
"sessions"			=> "
	`id` varchar(32) NOT NULL default '',
	`user_id` int(10) unsigned NOT NULL,
	`user_group` int(10) unsigned NOT NULL,
	`start_time` int(10) unsigned NOT NULL default '0',
	`last_time` int(10) unsigned NOT NULL default '0',
	`type` enum('user','admin') NOT NULL,
	`host_name` varchar(128) NOT NULL,
	`data` longtext,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	KEY `start_time` (`start_time`)
",
"settings"			=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`item` varchar(255) NOT NULL default '',
	`value` text NOT NULL,
	`type` enum('text','enum','char','date') NOT NULL default 'text',
	`size` varchar(255) NOT NULL default '',
	`debug` enum('0','1') NOT NULL default '0',
	`order` int(10) unsigned NOT NULL default '0',
	`category` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY (`id`),
	UNIQUE KEY `item` (`item`)
",
"settings_category"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`order` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY (`id`)
",
"sites"				=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`web_path` varchar(255) NOT NULL default '',
	`real_path` varchar(255) NOT NULL default '',
	`active` enum('1','0') NOT NULL default '1',
	`vertical` char(5) NOT NULL default 'homes',
	`locale` char(7) NOT NULL default 'en',
	`country` char(2) NOT NULL,
	PRIMARY KEY (`id`)
",
"core_servers"		=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`comment` text NOT NULL default '',
	`hostname` varchar(255) NOT NULL default '',
	`ip` varchar(255) NOT NULL default '',
	`active` enum('1','0') NOT NULL default '1',
	PRIMARY KEY (`id`)
",
"skins"				=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(64) NOT NULL default '',
	`desc` varchar(255) NOT NULL default '',
	`for_admin` enum('0','1') NOT NULL default '0',
	`for_user` enum('1','0') NOT NULL default '1',
	`active` enum('0','1') NOT NULL default '0',
	PRIMARY KEY (`id`)
",
"smilies"			=> "
	`id` smallint(5) unsigned NOT NULL auto_increment,
	`code` varchar(50) default NULL,
	`url` varchar(100) default NULL,
	`emoticon` varchar(75) default NULL,
	`emo_set` tinyint(3) unsigned NOT NULL default '1',
	PRIMARY KEY  (`id`)
",
"task_logs"	=> "
	`log_id` int(10) NOT NULL auto_increment,
	`log_title` varchar(255) NOT NULL default '',
	`log_date` int(10) NOT NULL default '0',
	`log_ip` varchar(16) NOT NULL default '0',
	`log_desc` text NOT NULL,
	`log_time` float NOT NULL default '0',
	PRIMARY KEY	(`log_id`)
",
"task_manager"		=> "
	`id` int(10) NOT NULL auto_increment,
	`title` varchar(255) NOT NULL default '',
	`file` varchar(255) NOT NULL default '',
	`php_code` TEXT NOT NULL default '',
	`next_run` int(10) NOT NULL default '0',
	`week_day` tinyint(1) NOT NULL default '-1',
	`month_day` smallint(2) NOT NULL default '-1',
	`hour` smallint(2) NOT NULL default '-1',
	`minute` smallint(2) NOT NULL default '-1',
	`cronkey` varchar(32) NOT NULL default '',
	`log` tinyint(1) NOT NULL default '0',
	`description` text NOT NULL,
	`enabled` tinyint(1) NOT NULL default '1',
	`key` varchar(30) NOT NULL default '',
	`safemode` tinyint(1) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `task_next_run` (`next_run`)
",
"templates"			=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`theme_name` varchar(32) NOT NULL default '',
	`name` varchar(128) NOT NULL default '',
	`text` longtext NOT NULL,
	`site_id` tinyint(3) unsigned NOT NULL default '0',
	`language` tinyint(3) unsigned NOT NULL default '0',
	`active` enum('1','0') NOT NULL default '1',
	PRIMARY KEY (`id`),
	KEY `theme_name_1` (`theme_name`,`name`,`active`)
",
"user_groups"		=> "
	`id` tinyint(3) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`active` enum('1','0') NOT NULL default '1',
	`go_after_login` varchar(255) NOT NULL default '',
	PRIMARY KEY (`id`)
",
"user_modules"		=> "
	`id` tinyint(3) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`description` VARCHAR( 255 ) NOT NULL default '',
	`version` VARCHAR( 16 ) NOT NULL default '',
	`author` VARCHAR( 255 ) NOT NULL default '',
	`active` ENUM( '0', '1' ) NOT NULL default '0',
	PRIMARY KEY (`id`)
",
));

