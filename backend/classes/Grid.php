<?php

/**
 * Monstrous pagination and whatnot class. But it works!
 */

class Grid {

	public $prevPage = false;
	public $nextPage = false;
	public $totalPages = 1;
	public $currentPage = 1;
	public $currentCategory = '';
	private $children = array();
	private $navItems = array();
	private $populated = false;
	
	public static $baseUrl = 'http://lplius.lt/';
	
	public function __construct($children) {
		if(!empty($children)) $this->populated = true;
		$this->children = $children;
	}
	
	public static function buildLink($page = 0, $category = '') {
		$category = strtolower($category);
		$category = str_replace(' ', '-', $category);
		$category = str_replace(array('ą', 'č', 'ę', 'ė', 'į', 'š', 'ų', 'ū', 'ž'), array('a', 'c', 'e', 'e', 'i', 's', 'u', 'u', 'z'), $category);
		if (($page <= 1) && (empty($category))) return self::$baseUrl;
		elseif (empty($category)) return self::$baseUrl . $page . '/';
		elseif ($page <= 1) return self::$baseUrl . $category . '/';
		else return self::$baseUrl . $category  . '/' . $page . '/';
	}
	
	public function isPopulated() {
		return $this->populated;
	}
	
	public function getChildren() {
		return $this->children;
	}
	
	public function getCategories() {
		return $this->allCategories;
	}
	
	public function getTotalUserCount() {
		$count = $this->totalUserCount % 100;
		if (($count > 9) && ($count < 20)) return $this->totalUserCount . ' Google+ profilių';
		elseif ($count % 10 == 0) return $this->totalUserCount . ' Google+ profilių';
		elseif ($count % 10 == 1) return $this->totalUserCount . ' Google+ profilis';
		else return $this->totalUserCount . ' Google+ profiliai';
	}
	
	public function getNavigation() {
		return $this->navItems;
	}
	
	public function saveNavigationItem(NavigationItem $item) {
		$this->navItems[] = $item;
	}
	
	public function isCurrentNav(NavigationItem $item) {
		$name = strtolower($item->getName());
		$category = str_replace(array('ą', 'č', 'ę', 'ė', 'į', 'š', 'ų', 'ū', 'ž'), array('a', 'c', 'e', 'e', 'i', 's', 'u', 'u', 'z'), $name);
		if ($category == $this->currentCategory) return true;
		elseif (($this->currentCategory === NULL) && ($item instanceof HomeItem)) return true;
		else return false;
	}
	
	public function isCurrentPage(PageItem $item) {
		if ($item instanceof DotItem) return false;
		elseif ($item->getName() == $this->currentPage) return true;
		else return false;
	}
	
	public function getPrevLink() {
		if ($this->prevPage) return 'href="' . self::buildLink($this->currentPage - 1, $this->currentCategory) . '"';
		else return '';
	}
	
	public function getNextLink() {
		if ($this->nextPage) return 'href="' . self::buildLink($this->currentPage + 1, $this->currentCategory)  . '"';
		else return '';
	}
	
	public function getPagination() {
		$pagination = array();
		if ($this->totalPages <= 7) {
			for($i = 1; $i <= $this->totalPages; $i++) {
				$pagination[] = new PageItem($i, $this->currentCategory);
			}
		}
		else {
			//here be structured kind dragons
			$pagination[] = new PageItem(1, $this->currentCategory); //always here
			if ($this->currentPage > 4) $pagination[] = new DotItem();
			
			if ($this->currentPage <= 4) {
				for ($i = 2; $i <= 5; $i++) {
					$pagination[] = new PageItem($i, $this->currentCategory);
				}
			}
			elseif ($this->currentPage > $this->totalPages - 4) {
				for ($i = $this->totalPages - 4; $i <= $this->totalPages - 1; $i++) {
					$pagination[] = new PageItem($i, $this->currentCategory);
				}
			}
			else {
				for ($i = $this->currentPage - 1; $i <= $this->currentPage + 1; $i++) {
					$pagination[] = new PageItem($i, $this->currentCategory);
				}
			}
			
			if ($this->currentPage <= $this->totalPages - 4) $pagination[] = new DotItem();
			$pagination[] = new PageItem($this->totalPages, $this->currentCategory); //always here
		}
		return $pagination;
	}
	
	

}

?>
