<?php

/**
 * Class that manages adding new users to the database, updating their follower count and etc.
 */

class PlusRobot { //The factory

	private static $database;
	private static $TopUserBreakdown = array();
	private $db;

	/* helper function for DB interaction. Does not really belong here, but it's the only one that works with DB... */
	public static function getTopUserBreakdown() {
		if (!empty(self::$TopUserBreakdown)) return self::$TopUserBreakdown;
		elseif (empty(self::$database)) return array(0,0,0,0,0,0,0,0,0,0);
		else {
			$count = self::$database->getVar('SELECT count(plusperson_id) FROM plusperson', array());
			$step = floor($count / 10);
			$list = array();
			for($i = 1; $i <= 9; $i++) {
				$offset = $i * $step;
				$score = self::$database->getVar("SELECT followers FROM plusperson ORDER BY followers DESC LIMIT $offset, 1", array());
				$list[] = $score;
			}
			$list[] = 0;
			$list = array_reverse($list);
			self::$TopUserBreakdown = $list;
			return self::$TopUserBreakdown;
		}
	}

	public function __construct(DB $DB) {
		$this->db = $DB;
		self::$database = $DB;
	}
	
	public function isBlacklisted($id) {
		$blacklisted = array(
			'100990873244877381511',
		);
		return in_array($id, $blacklisted);
	}

	public function addPerson($url, $category) {
		$id = FALSE;
		//parse the ID
			preg_match('/[0-9]{21}/', $url, $matches);
			if (!isset($matches[0])) { //if invalid url / id provided
				throw new Exception('+Įvesta netinkama vartotojo profilio nuoroda.');
			}			
			else {
				$id = $matches[0];
				//check against blacklisted IDs
				if ($this->isBlacklisted($id)) { throw new Exception('Šis vartotojas nesutinka būti pridėtas.'); }
				
			//try to get the object from DB -check if does not exist yet
				$person = $this->db->CreateObject('SELECT * FROM plusperson WHERE googleplus_id = ?', array($id), 'PlusPersonCore');
				if (false === $person) {
					//check if category ID is valid
					$cat_name = $this->db->getVar("SELECT category_name FROM pluscategory WHERE category = ?", array($category));
					if (NULL === $cat_name) {
						throw new Exception('+Kategoriją pasirinkti būtina.', 1);
					}
					else {
					//does not exist yet - add
						$person = new PlusPersonCore($id, $category);
						$person->updateFromGooglePlusService();
						$this->savePerson($person);
						$mail_message = $person->first_name . ' ' . $person->last_name . "\n url: https://plus.google.com/" . $person->googleplus_id . "\n kategorija: " . $cat_name;
						mail('info@lplius.lt', 'Naujas Lietuviai+ vartotojas ' . $person->googleplus_id, wordwrap($mail_message, 70));
					}
				}
				else {
					throw new Exception('+Toks vartotojas jau egzistuoja.', 2);
				}
			}
	}

	public function savePerson(PlusPersonCore $person) {
		$plusperson_id = 0;
		$plusperson_id = $this->db->insertOne('plusperson', $person->dbValues(), array('created_dt', 'plusperson_id'));

		//save follower count separately, too
		if (0 == $plusperson_id) $plusperson_id = $person->plusperson_id;
		$this->db->insertOne('plusfollowers', array('time' => date('Y-m-d H:i:s'), 'followers' => $person->followers, 'plusperson_id' => $plusperson_id));
	}

	public function prepareGrid($page, $category, $postsperpage = 15) {
		if ($this->db === NULL) return new Grid(array());
		if (NULL == $page) $page = 0;
		else $page = intval($page) - 1;
		if ($page < 0) $page = 999999;

		$sql = 'SELECT plusperson.plusperson_id, googleplus_id, followers, first_name, last_name, profile_photo, introduction, subhead, category_name as category
			FROM plusperson LEFT JOIN pluscategory ON plusperson.category = pluscategory.category';

		if (NULL !== $category) $sql_where = ' WHERE category_name = ?'; //add category clause
		else $sql_where = '';

		//create final SQL statement and get the objects
		$sql_final = $sql . $sql_where . ' ORDER BY followers DESC, plusperson.plusperson_id ASC LIMIT ' . $page * $postsperpage . ', ' . $postsperpage;
		$persons = $this->db->createObjects($sql_final, array($category), 'PlusPersonView');
		if (empty($persons)) {
			$grid = new Grid(array());
		}
		else {
			$grid = new Grid($persons);
			if ($page !== 0) $grid->prevPage = true;
			$total = $this->db->getVar('SELECT count(plusperson_id) FROM plusperson LEFT JOIN pluscategory ON plusperson.category = pluscategory.category' . $sql_where, array($category));
			if ($total > (1 + $page) * $postsperpage) $grid->nextPage = true;
			$grid->totalPages = ceil($total / $postsperpage);
			$grid->currentPage = $page + 1;
			$grid->currentCategory = $category;
		}
		//add navigation data to grid

		//add home link
		$home = new HomeItem();
		$grid->saveNavigationItem($home);

		//get the active categories
		$categories = $this->db->createObjects('SELECT pluscategory.category as id, category_name as name FROM pluscategory JOIN plusperson ON plusperson.category = pluscategory.category  ORDER BY category_name ASC', array(), 'CategoryItem');
		if (!empty($categories)) array_walk($categories, array($grid, 'saveNavigationItem'));

		//get all categories
		$categories = $this->db->createObjects('SELECT pluscategory.category as id, category_name as name FROM pluscategory ORDER BY category_name ASC', array(), 'CategoryItem');
		$grid->allCategories = $categories;

		//add total user count
		$grid->totalUserCount = $this->db->getVar('SELECT count(plusperson_id) from plusperson', array());
		//print_f($grid);
		return $grid;
	}
}

?>
