<?php
$data = '
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`error_level` smallint(5) unsigned NOT NULL,
	`error_text` text NOT NULL,
	`source_file` varchar(255) NOT NULL,
	`source_line` smallint(5) unsigned NOT NULL,
	`env_data` text NOT NULL,
	`user_id` int(10) unsigned NOT NULL DEFAULT \'0\',
	`user_group` tinyint(3) unsigned NOT NULL,
	`is_admin` enum(\'0\',\'1\') NOT NULL DEFAULT \'0\',
	`site_id` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
	`server_id` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
	`date` int(10) unsigned NOT NULL DEFAULT \'0\',
	`ip` varchar(16) NOT NULL DEFAULT \'\',
	`query_string` varchar(255) NOT NULL DEFAULT \'\',
	`request_uri` varchar(255) NOT NULL DEFAULT \'\',
	`user_agent` varchar(255) NOT NULL DEFAULT \'\',
	`referer` varchar(255) NOT NULL DEFAULT \'\',
	`object` varchar(255) NOT NULL DEFAULT \'\',
	`action` varchar(255) NOT NULL DEFAULT \'\',
	PRIMARY KEY (`id`),
	KEY `error_level` (`error_level`),
	KEY `date` (`date`)
';