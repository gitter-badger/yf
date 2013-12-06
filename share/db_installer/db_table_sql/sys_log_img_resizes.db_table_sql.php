<?php
$data = '
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
	`other_options` varchar(255) NOT NULL default \'\',
	`source_file` varchar(255) NOT NULL default \'\',
	`source_line` smallint(5) unsigned NOT NULL,
	`env_data` text NOT NULL,
	`user_id` int(10) unsigned NOT NULL default \'0\',
	`user_group` tinyint(3) unsigned NOT NULL,
	`is_admin` enum(\'0\',\'1\') NOT NULL default \'0\',
	`site_id` tinyint(3) unsigned NOT NULL default \'0\',
	`server_id` tinyint(3) unsigned NOT NULL default \'0\',
	`date` int(10) unsigned NOT NULL default \'0\',
	`ip` varchar(16) NOT NULL default \'\',
	`query_string` varchar(255) NOT NULL default \'\',
	`request_uri` varchar(255) NOT NULL default \'\',
	`user_agent` varchar(255) NOT NULL default \'\',
	`referer` varchar(255) NOT NULL default \'\',
	`object` varchar(255) NOT NULL default \'\',
	`action` varchar(255) NOT NULL default \'\',
	`success` enum(\'0\',\'1\') NOT NULL,
	`error_text` text NOT NULL,
	`process_time` float unsigned NOT NULL,
	`used_lib` varchar(32) NOT NULL,
	`tried_libs` varchar(32) NOT NULL,
	PRIMARY KEY	(`id`),
	KEY `date` (`date`)
';