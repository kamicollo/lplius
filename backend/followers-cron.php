<?php

/**
 * This script manages updating of follower counts.
 * By default, last 100 users with oldest update date will be updated in 1 run.
 */


require_once('classes/DB.php');
require_once('classes/PlusRobot.php');
require_once('googleplusapi/lib/GooglePlus/PlusPerson.php');
require_once('googleplusapi/lib/GooglePlus/PlusPost.php');
require_once('classes/PlusPersonCore.php');
require_once('classes/Fetcher.php');
$settings = @parse_ini_file('../settings/settings.ini', true);
try {
	$db = new DB('mysql:dbname=' . $settings['mysql']['db'] . ';host=' . $settings['mysql']['host'], $settings['mysql']['username'], $settings['mysql']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
	$Robot = new PlusRobot($db);
	if (!isset($_GET['dead'])) {
		$persons = $db->CreateObjects(
			'SELECT googleplus_id, plusperson_id, category, followers 
			FROM plusperson 
			WHERE state = 1 AND modified_dt < ?
			ORDER BY modified_dt ASC LIMIT 100',
			array(date('Y-m-d H:i:s', time() - 3600)),
		 'PlusPersonCore');
	}
	else {
		$persons = $db->CreateObjects(
			'SELECT googleplus_id, plusperson_id, category, followers 
			FROM plusperson 
			WHERE state = -1 ORDER BY modified_dt ASC LIMIT 10',
			array(),
		 'PlusPersonCore');
	}
	
	foreach ($persons as $person) {
		try {
			print_f($person);			
			$person->updateFromGooglePlusService();
			$Robot->savePerson($person);
			$person->raw_data = '';
			print_f($person);			
		}
		catch(PlusException $e) {
			print_f($e->__toString());
			switch ($e->getCode()) {
				case 20: //redirection - delete as it is a non Google+, but just Google profile
				case 10: //all Google errors - delete
					$db->getVar('UPDATE plusperson SET state = -1 WHERE plusperson_id = ?', array($e->getId()));
					print_f('setting for deletion!');
					break;
			}					
		}
		catch(Exception $e) {		
			file_put_contents($settings['general']['log_dir'], $e->__toString(), FILE_APPEND);
		}
	}
}
catch(Exception $e) {
		file_put_contents($settings['general']['log_dir'], $e->__toString(), FILE_APPEND);
}
