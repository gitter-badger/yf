<?php
// YF_FORUM tables
$data = my_array_merge((array)$data, array(
"forum_announce"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`title` varchar(255) NOT NULL default '',
	`post` text NOT NULL,
	`forum` text NOT NULL,
	`author_id` mediumint(8) unsigned NOT NULL default '0',
	`html_enabled` tinyint(1) NOT NULL default '0',
	`views` int(10) unsigned NOT NULL default '0',
	`start_time` int(10) unsigned NOT NULL default '0',
	`end_time` int(10) unsigned NOT NULL default '0',
	`active` tinyint(1) NOT NULL default '1',
	PRIMARY KEY	(`id`)
",
"forum_categories"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`parent` int(10) unsigned NOT NULL default '0',
	`name` varchar(255) NOT NULL default '',
	`desc` varchar(255) NOT NULL default '',
	`status` char(1) NOT NULL default 'a',
	`order` int(10) unsigned NOT NULL default '0',
	`icon` varchar(255) NOT NULL default '',
	`language` varchar(12) NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
"forum_forums"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`parent` int(10) unsigned NOT NULL default '0',
	`category` int(10) unsigned NOT NULL default '0',
	`name` varchar(255) NOT NULL default '',
	`desc` varchar(255) NOT NULL default '',
	`created` int(10) unsigned NOT NULL default '0',
	`status` char(1) NOT NULL default 'a',
	`icon` varchar(255) NOT NULL default '',
	`order` int(10) unsigned NOT NULL default '0',
	`num_views` int(10) unsigned NOT NULL default '0',
	`num_topics` int(10) unsigned NOT NULL default '0',
	`num_posts` int(10) unsigned NOT NULL default '0',
	`last_post_id` int(10) unsigned NOT NULL default '0',
	`last_post_date` int(10) unsigned NOT NULL default '0',
	`language` varchar(12) NOT NULL default '0',
	`options` CHAR(10) NOT NULL,
	`user_groups` varchar(255) NOT NULL default '',
	PRIMARY KEY	(`id`)
",
"forum_groups"	=> "
	`id` int(3) unsigned NOT NULL auto_increment,
	`title` varchar(32) NOT NULL default '',
	`icon` varchar(250) default '',
	`prefix` varchar(250) default '',
	`suffix` varchar(250) default '',
	`is_admin` tinyint(1) unsigned NOT NULL default '0',
	`is_moderator` tinyint(1) unsigned NOT NULL default '0',
	`view_board` tinyint(1) unsigned NOT NULL default '0',
	`view_ip` tinyint(1) unsigned NOT NULL default '0',
	`view_member_info` tinyint(1) unsigned NOT NULL default '0',
	`view_other_topics` tinyint(1) unsigned NOT NULL default '0',
	`view_post_closed` tinyint(1) unsigned NOT NULL default '0',
	`post_new_topics` tinyint(1) unsigned NOT NULL default '0',
	`reply_own_topics` tinyint(1) unsigned NOT NULL default '0',
	`reply_other_topics` tinyint(1) unsigned NOT NULL default '0',
	`delete_own_topics` tinyint(1) unsigned NOT NULL default '0',
	`delete_other_topics` tinyint(1) unsigned NOT NULL default '0',
	`edit_own_topics` tinyint(1) unsigned NOT NULL default '0',
	`edit_other_topics` tinyint(1) unsigned NOT NULL default '0',
	`open_topics` tinyint(1) unsigned NOT NULL default '0',
	`close_topics` tinyint(1) unsigned NOT NULL default '0',
	`pin_topics` tinyint(1) unsigned NOT NULL default '0',
	`unpin_topics` tinyint(1) unsigned NOT NULL default '0',
	`move_topics` tinyint(1) unsigned NOT NULL default '0',
	`approve_topics` tinyint(1) unsigned NOT NULL default '0',
	`unapprove_topics` tinyint(1) unsigned NOT NULL default '0',
	`open_close_posts` tinyint(1) unsigned NOT NULL default '0',
	`delete_own_posts` tinyint(1) unsigned NOT NULL default '0',
	`delete_other_posts` tinyint(1) unsigned NOT NULL default '0',
	`edit_own_posts` tinyint(1) unsigned NOT NULL default '0',
	`edit_other_posts` tinyint(1) unsigned NOT NULL default '0',
	`move_posts` tinyint(1) unsigned NOT NULL default '0',
	`approve_posts` tinyint(1) unsigned NOT NULL default '0',
	`unapprove_posts` tinyint(1) unsigned NOT NULL default '0',
	`split_merge` tinyint(1) unsigned NOT NULL default '0',
	`edit_own_profile` tinyint(1) unsigned NOT NULL default '0',
	`edit_other_profile` tinyint(1) unsigned NOT NULL default '0',
	`hide_from_list` tinyint(1) unsigned NOT NULL default '0',
	`avatar_upload` tinyint(1) unsigned NOT NULL default '0',
	`use_search` tinyint(1) unsigned NOT NULL default '0',
	`use_pm` tinyint(1) unsigned NOT NULL default '0',
	`max_messages` int(5) default '50',
	`email_friend` tinyint(1) unsigned NOT NULL default '0',
	`search_flood` mediumint(6) default '20',
	`make_polls` tinyint(1) unsigned NOT NULL default '0',
	`vote_polls` tinyint(1) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
"forum_moderators"	=> "
	`id` mediumint(8) unsigned NOT NULL auto_increment,
	`forums_list` text NOT NULL,
	`member_name` varchar(32) NOT NULL default '',
	`member_id` mediumint(8) unsigned NOT NULL default '0',
	`view_ip` tinyint(1) unsigned NOT NULL default '0',
	`delete_own_topics` tinyint(1) unsigned NOT NULL default '0',
	`delete_other_topics` tinyint(1) unsigned NOT NULL default '0',
	`edit_own_topics` tinyint(1) unsigned NOT NULL default '0',
	`edit_other_topics` tinyint(1) unsigned NOT NULL default '0',
	`open_topics` tinyint(1) unsigned NOT NULL default '0',
	`close_topics` tinyint(1) unsigned NOT NULL default '0',
	`pin_topics` tinyint(1) unsigned NOT NULL default '0',
	`unpin_topics` tinyint(1) unsigned NOT NULL default '0',
	`move_topics` tinyint(1) unsigned NOT NULL default '0',
	`open_close_posts` tinyint(1) unsigned NOT NULL default '0',
	`delete_own_posts` tinyint(1) unsigned NOT NULL default '0',
	`delete_other_posts` tinyint(1) unsigned NOT NULL default '0',
	`edit_own_posts` tinyint(1) unsigned NOT NULL default '0',
	`edit_other_posts` tinyint(1) unsigned NOT NULL default '0',
	`move_posts` tinyint(1) unsigned NOT NULL default '0',
	`split_merge` tinyint(1) unsigned NOT NULL default '0',
	`edit_own_profile` tinyint(1) unsigned NOT NULL default '0',
	`edit_other_profile` tinyint(1) unsigned NOT NULL default '0',
	`make_polls` tinyint(1) unsigned NOT NULL default '0',
	`vote_polls` tinyint(1) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
"forum_posts"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`parent` int(10) unsigned NOT NULL default '0',
	`forum` int(10) unsigned NOT NULL default '0',
	`topic` int(10) unsigned NOT NULL default '0',
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_name` varchar(64) NOT NULL default '',
	`created` int(10) unsigned NOT NULL default '0',
	`status` char(1) NOT NULL default 'a',
	`subject` varchar(255) NOT NULL default '',
	`text` longtext NOT NULL,
	`new_topic` tinyint(1) unsigned NOT NULL default '0',
	`edit_name` varchar(64) NOT NULL default '',
	`edit_time` int(10) unsigned NOT NULL default '0',
	`show_edit_by` tinyint(1) unsigned NOT NULL default '0',
	`use_sig` tinyint(1) unsigned NOT NULL default '1',
	`use_emo` tinyint(1) unsigned NOT NULL default '1',
	`icon_id` smallint(5) unsigned NOT NULL default '0',
	`poster_ip` varchar(16) NOT NULL default '',
	`language` varchar(12) NOT NULL default '0',
	`activity` int(10) unsigned NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `user_id` (`user_id`),
	KEY `status` (`status`),
	KEY `created` (`created`),
	KEY `topic` (`topic`)
",
"forum_ranks"	=> "
	`id` smallint(5) unsigned NOT NULL auto_increment,
	`title` varchar(50) NOT NULL default '',
	`min` mediumint(8) NOT NULL default '0',
	`special` tinyint(1) default NULL,
	`image` varchar(255) default NULL,
	`language` varchar(12) NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
"forum_read_msgs"	=> "
	`user_id` int(10) unsigned NOT NULL default '0',
	`data` text NOT NULL,
	PRIMARY KEY	(`user_id`)
",
"forum_reports"	=> "
	`id` int(11) NOT NULL auto_increment,
	`post_id` int(11) NOT NULL default '0',
	`user_id` int(11) NOT NULL default '0',
	`time` int(11) NOT NULL default '0',
	`text` text NOT NULL,
	`active` int(11) NOT NULL default '1',
	PRIMARY KEY  (`id`)
",
"forum_sessions"	=> "
	`id` varchar(32) NOT NULL default '0',
	`user_id` mediumint(8) unsigned NOT NULL default '0',
	`user_name` varchar(64) default NULL,
	`user_group` smallint(3) unsigned default NULL,
	`ip_address` varchar(16) default NULL,
	`user_agent` varchar(64) default NULL,
	`login_date` int(10) unsigned NOT NULL default '0',
	`last_update` int(10) unsigned default NULL,
	`login_type` tinyint(1) unsigned default NULL,
	`location` varchar(40) default NULL,
	`in_forum` smallint(5) unsigned NOT NULL default '0',
	`in_topic` int(10) unsigned default NULL,
	PRIMARY KEY	(`id`),
	KEY `in_topic` (`in_topic`),
	KEY `in_forum` (`in_forum`)
",
"forum_topics"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`forum` int(10) unsigned NOT NULL default '0',
	`name` varchar(255) NOT NULL default '',
	`desc` varchar(255) NOT NULL default '',
	`user_id` int(10) unsigned NOT NULL default '0',
	`user_name` varchar(255) NOT NULL default '',
	`created` int(10) unsigned NOT NULL default '0',
	`status` char(1) NOT NULL default 'a',
	`num_views` int(10) unsigned NOT NULL default '0',
	`num_posts` int(10) unsigned NOT NULL default '0',
	`first_post_id` int(10) unsigned NOT NULL default '0',
	`last_post_id` int(10) unsigned NOT NULL default '0',
	`last_poster_id` int(10) unsigned NOT NULL default '0',
	`last_poster_name` varchar(64) NOT NULL default '',
	`last_post_date` int(10) unsigned NOT NULL default '0',
	`icon_id` tinyint(2) unsigned NOT NULL default '0',
	`pinned` tinyint(1) NOT NULL default '0',
	`moved_to` varchar(64) NOT NULL default '',
	`approved` tinyint(1) NOT NULL default '0',
	`language` varchar(12) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `forum` (`forum`,`pinned`)
",
"forum_tracker"	=> "
	`id` mediumint(8) NOT NULL auto_increment,
	`user_id` mediumint(8) NOT NULL default '0',
	`topic_id` int(10) NOT NULL default '0',
	`start_date` int(10) default NULL,
	`last_sent` int(10) NOT NULL default '0',
	`topic_track_type` varchar(100) NOT NULL default 'delayed',
	PRIMARY KEY	(`id`),
	KEY `topic_id` (`topic_id`)
",
"forum_users"	=> "
	`id` int(10) unsigned NOT NULL auto_increment,
	`status` char(1) default 'a',
	`name` varchar(24) NOT NULL default '',
	`pswd` varchar(32) NOT NULL default '',
	`user_lastvisit` int(10) unsigned NOT NULL default '0',
	`user_regdate` int(10) unsigned NOT NULL default '0',
	`group` tinyint(4) default NULL,
	`user_posts` mediumint(8) unsigned NOT NULL default '0',
	`user_timezone` float NOT NULL default '0',
	`user_dateformat` varchar(14) NOT NULL default 'd M Y H:i',
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
	`view_sig` tinyint(1) unsigned NOT NULL default '1',
	`view_images` tinyint(1) unsigned NOT NULL default '1',
	`view_avatars` tinyint(1) unsigned NOT NULL default '1',
	`posts_per_page` tinyint(3) unsigned NOT NULL default '0',
	`topics_per_page` tinyint(3) unsigned NOT NULL default '0',
	`dst_status` tinyint(1) unsigned NOT NULL default '0',
	`language` varchar(12) NOT NULL default '0',
	PRIMARY KEY	(`id`)
",
));

