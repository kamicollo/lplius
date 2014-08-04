<?php

abstract class NavigationItem {

	public $name;

	public function getName() {
		return $this->name;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getLink() {
		return Grid::BuildLink(0, 0);
	}
}

class CategoryItem extends NavigationItem {

	public $id;
		
	public function getLink() {
		return Grid::BuildLink(0, strtolower($this->getName()));
	}
}

class HomeItem extends NavigationItem {
	public $name = 'Visi';
}

?>

