<?php
$data = '
	`id` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(64) NOT NULL default \'\',
	`desc` varchar(255) NOT NULL default \'\',
	`stpl_name` varchar(255) NOT NULL,
	`method_name` varchar(255) NOT NULL,
	`type` enum(\'user\',\'admin\') NOT NULL,
	`active` enum(\'1\',\'0\') NOT NULL default \'1\',
	PRIMARY KEY  (`id`)
';