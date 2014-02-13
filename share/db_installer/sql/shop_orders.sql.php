<?php
$data = '
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT \'0\',
  `date` int(11) unsigned NOT NULL DEFAULT \'0\',
  `total_sum` decimal(12,2) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `address` text NOT NULL,
  `house` text NOT NULL,
  `apartment` text NOT NULL,
  `floor` text NOT NULL,
  `porch` text NOT NULL,
  `intercom` text NOT NULL,
  `comment` text NOT NULL,
  `payment` tinyint(1) NOT NULL DEFAULT \'0\',
  `transaction_id` varchar(40) NOT NULL,
  `delivery` enum(\'0\',\'1\') NOT NULL DEFAULT \'0\',
  `region` int(11) NOT NULL DEFAULT \'0\',
  `status` varchar(16) NOT NULL,
  `delivery_time` varchar(16) NOT NULL DEFAULT \'\',
  `delivery_price` varchar(10) NOT NULL DEFAULT \'\',
  `is_manual_delivery_price` int(11) NOT NULL DEFAULT \'0\',
  `merge_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
';