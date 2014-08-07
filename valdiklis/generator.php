<?php 

define('DEFAULT_WIDTH', 250);
define('DEFAULT_ID', '117118694151742553199');
	/* width settings */
	
	if (!isset($_GET['width'])) $width = DEFAULT_WIDTH;
	else {
		$width = intval($_GET['width']);
		if ($width > 400) $width = 400;
		elseif ($width < 180) $width = 180;		
	}
	$pic_width = $width - 22;	
	
	/* id settings */
	
	if (!isset($_GET['id'])) $id = DEFAULT_ID;
	else $id = $_GET['id'];

	/* initialisation */
	
	require_once('../backend/classes/DB.php');
	require_once('../backend/classes/PlusRobot.php');
	require_once('../backend/classes/Grid.php');
	require_once('../backend/googleplusapi/lib/GooglePlus/PlusPerson.php');
	require_once('../backend/classes/PlusPersonCore.php');
	require_once('../backend/classes/PlusPersonView.php');
	
	mb_internal_encoding('UTf-8');
	setlocale(LC_ALL, "en_US.utf8");
	
	$settings = @parse_ini_file('../settings/settings.ini', true);		
	if (isset($settings['general']['base_url'])) {
			Grid::$baseUrl = $settings['general']['base_url'];
	}	
	try {		
		$db = new DB(
		'mysql:dbname=' . $settings['mysql']['db'] .
		';host=' . $settings['mysql']['host'],
		$settings['mysql']['username'],
		$settings['mysql']['password'],
		array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
		$sql = 'SELECT plusperson.plusperson_id, googleplus_id, followers, first_name, last_name, profile_photo, introduction, subhead, category_name as category
			FROM plusperson LEFT JOIN pluscategory ON plusperson.category = pluscategory.category WHERE googleplus_id = ?';
		$robot = new PlusRobot($db);
		$person = $db->createObject($sql, array($id), 'PlusPersonView');
		if (!($person instanceof PlusPersonView)) {
			$person = $db->createObject($sql, array(DEFAULT_ID), 'PlusPersonView');
		}
	}
	catch (Exception $e) {}
	ob_start();

?><div class="clearfix plus-widget-profile">
		<a href="<?php echo $person->getProfileLink(); ?>">
			<img class="plus-widget-pic" src="<?php echo $person->getPhoto($pic_width); ?>" alt="<?php echo $person->getName(); ?>" />
		</a>
		
		<div class="plus-widget-name">
			<a href="<?php echo $person->getProfileLink(); ?>">
				<?php echo $person->getName(); ?>
			</a>
		</div>
		
		<div class="plus-widget-bio">
			<?php echo $person->getDescription(128); ?>
		</div>
	
		<div class="clearfix plus-widget-count">
			<ul title="Populiarumas tarp Google+ lietuvių" class=clearfix>
				<?php foreach($person->getFollowerTrafficLight() as $number => $reached) { ?>
					<li <?php if ($reached) echo 'class="reached"'; ?> title="Apie <?php echo round($number / 10, 0) * 10; ?> sekėjų"></li>
					<?php } ?>
			</ul>
			<span title="Sekėjų skaičius"><?php echo $person->getFollowerCount(); ?></span>
		</div>
	
		<a class="plus-widget-category" href="<?php echo Grid::buildLink(0, $person->getCategory()); ?>" >#<?php echo $person->getCategory(); ?></a>
	</div>
	<div class="clearfix plus-widget-foot">
		<a href="<?php echo Grid::$baseUrl ?>"><img class="plus-widget-lplius" src="<?php echo Grid::$baseUrl ?>valdiklis/lietuviai.png" alt="Lietuviai+"/></a>
		<a href="http://plus.google.com"><img class="plus-widget-gplius" src="<?php echo Grid::$baseUrl ?>valdiklis/google.png" alt="Google+"/></a>
	</div>
<?php

	DEFINE('CONTENTS', ob_get_contents());
	ob_end_clean();
	
?>