<?php

	require_once('backend/classes/DB.php');
	require_once('backend/classes/PlusRobot.php');
	require_once('backend/classes/NavigationItem.php');
	require_once('backend/classes/PageItem.php');
	require_once('backend/classes/Grid.php');
	require_once('backend/googleplusapi/lib/GooglePlus/PlusPerson.php');
	require_once('backend/googleplusapi/lib/GooglePlus/PlusPost.php');
	require_once('backend/classes/PlusPersonCore.php');
	require_once('backend/classes/PlusPersonView.php');
	require_once('backend/classes/Fetcher.php');
	mb_internal_encoding('UTf-8');
	setlocale(LC_ALL, "en_US.utf8");
	
	$settings = @parse_ini_file('settings/settings.ini', true);		
	if (isset($settings['general']['base_url'])) {
			Grid::$baseUrl = $settings['general']['base_url'];
	}	
	try {		
		$db = new DB('mysql:dbname=' . $settings['mysql']['db'] . ';host=' . $settings['mysql']['host'], $settings['mysql']['username'], $settings['mysql']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));		
		$Robot = new PlusRobot($db);

		//action logic
		if (isset($_POST['profile_id'])) { //actually, URL
			//add the newly provided url
			try {
				$Robot->addPerson($_POST['profile_id'], $_POST['category']);
				$PostMessage = new Exception('+Vartotojas sėkmingai pridėtas.');
			}
			catch (Exception $PostMessage) {
				//this should not happen.
			}
		}

	//view logic
		if (!isset($_GET['page'])) $_GET['page'] = 1;
		if (!isset($_GET['category'])) $_GET['category'] = NULL;
		else $_GET['category'] = str_replace('-', ' ', $_GET['category']);
		$grid = $Robot->prepareGrid($_GET['page'], $_GET['category']);
		if ($grid->isPopulated()) {
			define('VIEW', 'grid');
		}
		else {
			 define('VIEW', 'error');
		}
	}
	catch(Exception $e) {
		define('VIEW', 'error');
		$Robot = new PlusRobot();
		$grid = $Robot->prepareGrid(1, NULL);
	}

?>
