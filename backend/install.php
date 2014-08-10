<?php

require_once('classes/DB.php');
$settings = @parse_ini_file('../settings/settings.ini', true);
try {
	$db = new DB('mysql:dbname=' . $settings['mysql']['db'] . ';host=' . $settings['mysql']['host'], $settings['mysql']['username'], $settings['mysql']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
	
	$db->exec("
		CREATE TABLE IF NOT EXISTS `pluscategory` (
			`category` tinyint(4) NOT NULL,
			`category_name` char(50) NOT NULL,
			PRIMARY KEY (`category`),
			KEY `category_name` (`category_name`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
	);
	
	$db->exec("
		CREATE TABLE IF NOT EXISTS `plusfollowers` (
			`plusperson_id` int(11) NOT NULL,
			`followers` bigint(20) NOT NULL,
			`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`plusperson_id`,`time`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
	);
	
	$db->exec("
		CREATE TABLE IF NOT EXISTS `plusperson` (
			`plusperson_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`googleplus_id` varchar(32) COLLATE utf8_bin NOT NULL,
			`profile_photo` varchar(255) COLLATE utf8_bin NOT NULL,
			`first_name` varchar(128) COLLATE utf8_bin NOT NULL,
			`last_name` varchar(128) COLLATE utf8_bin NOT NULL,
			`introduction` text COLLATE utf8_bin NOT NULL,
			`subhead` text COLLATE utf8_bin NOT NULL,
			`category` tinyint(4) NOT NULL,
			`followers` bigint(20) NOT NULL,
			`raw_data` mediumtext COLLATE utf8_bin NOT NULL,
			`fetched_relationships` int(11) NOT NULL DEFAULT '0',
			`created_dt` datetime NOT NULL,
			`modified_dt` datetime NOT NULL,
			`state` smallint(6) NOT NULL DEFAULT '1',
			`hasposts` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`plusperson_id`),
			UNIQUE KEY `googleplus_id` (`googleplus_id`),
			KEY `category` (`category`),
			KEY `followers` (`followers`),
			KEY `state` (`state`),
			KEY `hasposts` (`hasposts`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;"
	);
	
	echo "all good to go now.";
}
catch(Exception $e) {
		echo 'something went wrong!' . PHP_EOL;
		print_r($e->getMessage());
}


