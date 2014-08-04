<?php
error_reporting(E_ALL);
require_once('classes/DB.php');
require_once('classes/PlusRobot.php');
require_once('googleplusapi/lib/GooglePlus/PlusPerson.php');
require_once('googleplusapi/lib/GooglePlus/PlusPost.php');
require_once('classes/PlusPersonCore.php');
require_once('classes/Fetcher.php');
try {
	$db = new DB('mysql:dbname=aurimas_lplus;host=localhost', 'aurimas_lplus', 'C9VfP0ON', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
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
			WHERE state = -1 ORDER BY modified_dt ASC LIMIT 100',
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
			file_put_contents('/home/aurimas/domains/lplius.lt/logs/lpliuslt-logs.txt', $e->__toString(), FILE_APPEND);
		}
	}
}
catch(Exception $e) {
		file_put_contents('/home/aurimas/domains/lplius.lt/logs/lpliuslt-logs.txt', $e->__toString(), FILE_APPEND);
}
