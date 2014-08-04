<?php

class PageItem {
	
	private $number = 0;

	public function __construct($number, $category) {
		$this->number = $number;
		$this->category = $category;
	}

	public function getLink() {
		return Grid::buildLink($this->number, $this->category);
	}
	
	public function getName() {
		return $this->number;
	}
	
}

class DotItem extends PageItem {

	public function __construct() {}
	
	public function getLink() {
		return '';
	}
	
	public function getName() {
		return '...';
	}

}


?>
