<?php
$data = '
	`id` int(3) unsigned NOT NULL auto_increment,
	`title` varchar(32) NOT NULL default \'\',
	`active` tinyint(1) NOT NULL default \'1\',
	`icon` varchar(250) default \'\',
	`prefix` varchar(250) default \'\',
	`suffix` varchar(250) default \'\',
	`is_admin` tinyint(1) unsigned NOT NULL default \'0\',
	`is_moderator` tinyint(1) unsigned NOT NULL default \'0\',
	`view_board` tinyint(1) unsigned NOT NULL default \'0\',
	`view_ip` tinyint(1) unsigned NOT NULL default \'0\',
	`view_member_info` tinyint(1) unsigned NOT NULL default \'0\',
	`view_other_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`view_post_closed` tinyint(1) unsigned NOT NULL default \'0\',
	`post_new_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`reply_own_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`reply_other_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`delete_own_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`delete_other_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`edit_own_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`edit_other_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`open_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`close_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`pin_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`unpin_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`move_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`approve_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`unapprove_topics` tinyint(1) unsigned NOT NULL default \'0\',
	`open_close_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`delete_own_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`delete_other_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`edit_own_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`edit_other_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`move_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`approve_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`unapprove_posts` tinyint(1) unsigned NOT NULL default \'0\',
	`split_merge` tinyint(1) unsigned NOT NULL default \'0\',
	`edit_own_profile` tinyint(1) unsigned NOT NULL default \'0\',
	`edit_other_profile` tinyint(1) unsigned NOT NULL default \'0\',
	`hide_from_list` tinyint(1) unsigned NOT NULL default \'0\',
	`avatar_upload` tinyint(1) unsigned NOT NULL default \'0\',
	`use_search` tinyint(1) unsigned NOT NULL default \'0\',
	`use_pm` tinyint(1) unsigned NOT NULL default \'0\',
	`max_messages` int(5) default \'50\',
	`email_friend` tinyint(1) unsigned NOT NULL default \'0\',
	`search_flood` mediumint(6) default \'20\',
	`make_polls` tinyint(1) unsigned NOT NULL default \'0\',
	`vote_polls` tinyint(1) unsigned NOT NULL default \'0\',
	PRIMARY KEY	(`id`)
';