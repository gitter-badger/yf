<?php
$data = '
	`id` int(10) unsigned NOT NULL auto_increment,
	`status` char(1) default \'a\',
	`name` varchar(24) NOT NULL default \'\',
	`pswd` varchar(32) NOT NULL default \'\',
	`user_lastvisit` int(10) unsigned NOT NULL default \'0\',
	`user_regdate` int(10) unsigned NOT NULL default \'0\',
	`group` tinyint(4) default NULL,
	`user_posts` mediumint(8) unsigned NOT NULL default \'0\',
	`user_timezone` float NOT NULL default \'0\',
	`user_dateformat` varchar(14) NOT NULL default \'d M Y H:i\',
	`user_rank` int(10) unsigned default NULL,
	`user_avatar` varchar(100) default NULL,
	`user_email` varchar(255) default NULL,
	`user_icq` varchar(15) default NULL,
	`user_website` varchar(100) default NULL,
	`user_from` varchar(100) default NULL,
	`user_sig` text,
	`user_aim` varchar(255) default NULL,
	`user_yim` varchar(255) default NULL,
	`user_msnm` varchar(255) default NULL,
	`user_occ` varchar(100) default NULL,
	`user_interests` varchar(255) default NULL,
	`user_birth` date NOT NULL,
	`view_sig` tinyint(1) unsigned NOT NULL default \'1\',
	`view_images` tinyint(1) unsigned NOT NULL default \'1\',
	`view_avatars` tinyint(1) unsigned NOT NULL default \'1\',
	`posts_per_page` tinyint(3) unsigned NOT NULL default \'0\',
	`topics_per_page` tinyint(3) unsigned NOT NULL default \'0\',
	`dst_status` tinyint(1) unsigned NOT NULL default \'0\',
	`language` varchar(12) NOT NULL default \'0\',
	PRIMARY KEY	(`id`)
';