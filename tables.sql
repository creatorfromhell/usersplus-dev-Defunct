CREATE TABLE IF NOT EXISTS `up_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(40) NOT NULL,
  `group_permissions` text NOT NULL DEFAULT '',
  `group_admin` tinyint(1) NOT NULL DEFAULT '0',
  `group_preset` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `up_nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(40) NOT NULL,
  `node_description` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `node_name` (`node_name`)
) DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `up_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(40) CHARACTER SET utf8 NOT NULL,
  `user_password` varchar(64) CHARACTER SET utf8 NOT NULL,
  `user_email` varchar(220) NOT NULL DEFAULT '',
  `user_group` int(11) NOT NULL,
  `user_permissions` text NOT NULL DEFAULT '',
  `user_avatar` text NOT NULL DEFAULT '',
  `user_ip` varchar(80) NOT NULL DEFAULT '',
  `user_registered` date NOT NULL DEFAULT '0000-00-00',
  `logged_in` date NOT NULL DEFAULT '0000-00-00',
  `user_banned` tinyint(1) NOT NULL DEFAULT '0',
  `user_online` tinyint(1) NOT NULL DEFAULT '0',
  `user_activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) DEFAULT CHARSET=latin1;