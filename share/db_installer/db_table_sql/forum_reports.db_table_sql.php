<?php
$data = '
	`id` int(11) NOT NULL auto_increment,
	`post_id` int(11) NOT NULL default \'0\',
	`user_id` int(11) NOT NULL default \'0\',
	`time` int(11) NOT NULL default \'0\',
	`text` text NOT NULL,
	`active` int(11) NOT NULL default \'1\',
	PRIMARY KEY  (`id`)
';