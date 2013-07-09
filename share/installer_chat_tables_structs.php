<?php
// YF_CHAT tables
$data = array_merge((array)$data, array(
"chat_archive_log_online"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`user_id` int(10) unsigned NOT NULL default '0',
	`room_id` int(10) unsigned NOT NULL default '0',
	`login_date` int(10) unsigned NOT NULL default '0',
	`logout_date` int(10) unsigned NOT NULL default '0',
	`ip` varchar(32) NOT NULL default '',
	`session_id` varchar(32) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`text_color` varchar(32) NOT NULL default '',
	`login` varchar(255) NOT NULL default '',
	`gender` enum('m','f') NOT NULL default 'm',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY  (`id`)
",
"chat_archive_messages"	=> "
	`id` int(11) NOT NULL auto_increment,
	`room_id` int(10) unsigned NOT NULL default '0',
	`user_id` int(10) unsigned NOT NULL default '0',
	`login` varchar(32) NOT NULL default '',
	`user_group` int(10) unsigned NOT NULL default '0',
	`text` text NOT NULL,
	`add_date` int(10) unsigned NOT NULL default '0',
	`poster_ip` varchar(32) NOT NULL default '',
	`text_color` varchar(32) NOT NULL default '',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `add_date` (`add_date`)
",
"chat_archive_private"	=> "
	`id` int(11) NOT NULL auto_increment,
	`room_id` int(10) unsigned NOT NULL default '0',
	`user_from` int(10) unsigned NOT NULL default '0',
	`user_to` int(10) unsigned NOT NULL default '0',
	`login_from` varchar(32) NOT NULL default '',
	`login_to` varchar(32) NOT NULL default '',
	`user_group` int(10) unsigned NOT NULL default '0',
	`text` text NOT NULL,
	`add_date` int(10) unsigned NOT NULL default '0',
	`poster_ip` varchar(32) NOT NULL default '',
	`text_color` varchar(32) NOT NULL default '',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `add_date` (`add_date`)
",
"chat_ban_list"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`value` varchar(255) NOT NULL default '',
	`type` enum('ip','user') NOT NULL default 'ip',
	`expiration` int(10) unsigned NOT NULL default '0',
	`moderator` int(10) unsigned NOT NULL default '0',
	`add_date` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
"chat_ignore"	=> "
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_ignore` int(10) unsigned NOT NULL default '0',
	`add_date` int(10) unsigned NOT NULL default '0',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`user_id`,`user_ignore`),
	KEY `add_date` (`add_date`)
",
"chat_log_online"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`user_id` int(10) unsigned NOT NULL default '0',
	`room_id` int(10) unsigned NOT NULL default '0',
	`login_date` int(10) unsigned NOT NULL default '0',
	`logout_date` int(10) unsigned NOT NULL default '0',
	`ip` varchar(32) NOT NULL default '',
	`session_id` varchar(32) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`text_color` varchar(32) NOT NULL default '',
	`login` varchar(255) NOT NULL default '',
	`gender` enum('m','f') NOT NULL default 'm',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
"chat_messages"	=> "
	`id` int(11) NOT NULL auto_increment,
	`room_id` int(10) unsigned NOT NULL default '0',
	`user_id` int(10) unsigned NOT NULL default '0',
	`login` varchar(32) NOT NULL default '',
	`user_group` int(10) unsigned NOT NULL default '0',
	`text` text NOT NULL,
	`add_date` int(10) unsigned NOT NULL default '0',
	`poster_ip` varchar(32) NOT NULL default '',
	`text_color` varchar(32) NOT NULL default '',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `add_date` (`add_date`)
",
"chat_online"	=> "
	`user_id` int(10) unsigned NOT NULL default '0',
	`group_id` tinyint(3) unsigned NOT NULL default '0',
	`room_id` int(10) unsigned NOT NULL default '0',
	`add_date` int(10) unsigned NOT NULL default '0',
	`ip` varchar(32) NOT NULL default '',
	`session_id` varchar(32) NOT NULL default '',
	`user_agent` varchar(255) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`text_color` varchar(32) NOT NULL default '',
	`chat_color_1` varchar(32) NOT NULL default '',
	`chat_color_2` varchar(32) NOT NULL default '',
	`chat_color_3` varchar(32) NOT NULL default '',
	`chat_color_4` varchar(32) NOT NULL default '',
	`chat_show_time` tinyint(3) unsigned NOT NULL default '0',
	`chat_refresh` int(10) unsigned NOT NULL default '0',
	`chat_msg_filter` tinyint(3) unsigned NOT NULL default '0',
	`login` varchar(255) NOT NULL default '',
	`gender` enum('m','f') NOT NULL default 'm',
	`info_status` tinyint(3) unsigned NOT NULL default '0',
	`last_visit` int(10) unsigned NOT NULL default '0',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`user_id`),
	UNIQUE KEY `user_id` (`user_id`)
",
"chat_private"	=> "
	`id` int(11) NOT NULL auto_increment,
	`room_id` int(10) unsigned NOT NULL default '0',
	`user_from` int(10) unsigned NOT NULL default '0',
	`user_to` int(10) unsigned NOT NULL default '0',
	`login_from` varchar(32) NOT NULL default '',
	`login_to` varchar(32) NOT NULL default '',
	`user_group` int(10) unsigned NOT NULL default '0',
	`text` text NOT NULL,
	`add_date` int(10) unsigned NOT NULL default '0',
	`poster_ip` varchar(32) NOT NULL default '',
	`text_color` varchar(32) NOT NULL default '',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `add_date` (`add_date`)
",
"chat_rooms"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(255) NOT NULL default '',
	`desc` text NOT NULL,
	`css` text NOT NULL,
	`active` enum('0','1') NOT NULL default '0',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
"chat_smilies"	=> "
	`id` smallint(5) unsigned NOT NULL auto_increment,
	`code` varchar(50) default NULL,
	`url` varchar(100) default NULL,
	`emoticon` varchar(75) default NULL,
	PRIMARY KEY	(`id`)
",
"chat_users"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`group_id` int(10) unsigned NOT NULL default '0',
	`login` varchar(32) NOT NULL default '',
	`password` varchar(64) NOT NULL default '',
	`gender` enum('m','f') NOT NULL default 'm',
	`email` varchar(128) NOT NULL default '',
	`first_name` varchar(255) NOT NULL default '',
	`last_name` varchar(255) NOT NULL default '',
	`city` varchar(255) NOT NULL default '',
	`country` varchar(255) NOT NULL default '',
	`birth` int(10) unsigned NOT NULL default '0',
	`height` int(10) unsigned NOT NULL default '0',
	`weight` int(10) unsigned NOT NULL default '0',
	`hair_color` int(10) unsigned NOT NULL default '0',
	`eyes_color` int(10) unsigned NOT NULL default '0',
	`education` int(10) unsigned NOT NULL default '0',
	`job` varchar(255) NOT NULL default '',
	`speciality` varchar(255) NOT NULL default '',
	`married` tinyint(3) unsigned NOT NULL default '0',
	`children` tinyint(3) unsigned NOT NULL default '0',
	`drinking` int(10) unsigned NOT NULL default '0',
	`smoking` int(10) unsigned NOT NULL default '0',
	`home_phone` varchar(255) NOT NULL default '',
	`cell_phone` varchar(255) NOT NULL default '',
	`icq` varchar(255) NOT NULL default '',
	`msn` varchar(255) NOT NULL default '',
	`yahoo` varchar(255) NOT NULL default '',
	`web_page` varchar(255) NOT NULL default '',
	`other_info` text NOT NULL,
	`photo` varchar(255) NOT NULL default '',
	`chat_color_1` varchar(32) NOT NULL default '',
	`chat_color_2` varchar(32) NOT NULL default '',
	`chat_color_3` varchar(32) NOT NULL default '',
	`chat_color_4` varchar(32) NOT NULL default '',
	`chat_show_time` tinyint(3) unsigned NOT NULL default '0',
	`chat_refresh` int(10) unsigned NOT NULL default '0',
	`chat_msg_filter` tinyint(3) unsigned NOT NULL default '0',
	`info_add_date` int(10) unsigned NOT NULL default '0',
	`allowed_fields` text NOT NULL,
	`add_date` int(10) unsigned NOT NULL default '0',
	`active` enum('1','0') NOT NULL default '1',
	`language` tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	UNIQUE KEY `login` (`login`)
",
));

