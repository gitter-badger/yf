<?php
$data = '
	`user_id` int(10) unsigned NOT NULL auto_increment,
	`blog_title` varchar(255) NOT NULL,
	`blog_desc` varchar(255) NOT NULL,
	`custom_cats` text NOT NULL,
	`blog_links` text NOT NULL,
	`allow_comments` tinyint(3) unsigned NOT NULL,
	`privacy` tinyint(3) unsigned NOT NULL,
	`num_posts` int(10) unsigned NOT NULL,
	`num_comments` int(10) unsigned NOT NULL,
	`num_views` int(10) unsigned NOT NULL,
	`user_nick` varchar(255) NOT NULL,
	`allow_tagging` TINYINT UNSIGNED NOT NULL,
	PRIMARY KEY (`user_id`)
';