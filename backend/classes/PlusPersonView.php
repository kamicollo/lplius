<?php

/**
 * Class that builds on the main Google+ profile entity model, used to work in the frontend.
 */

class PlusPersonView extends PlusPersonCore {

	public function getSecretClass() {
		if ($this->plusperson_id == 1) return 'id=ernesta';
		elseif ($this->plusperson_id == 2) return 'id=aurimas';
		else return '';
	}

	public function getSecretLink() {
		if ($this->plusperson_id == 1) return '<a href=http://ernes7a.lt><img class=corner id=ernesta-corner src="images/ernesta.png" alt=Ernesta></a>';
		elseif ($this->plusperson_id == 2)  return '<a href=http://aurimas.eu><img class=corner id=aurimas-corner src="images/aurimas.png" alt=Aurimas></a>';
		else return '';
	}

	public function getName() {
		return $this->first_name . ' ' . $this->last_name;
	}

	public function getDescription($length = 47) {
		$description = $this->trim(strip_tags($this->subhead), $length);
		if (!empty($description)) return $description;
		else return $this->trim(strip_tags($this->introduction), $length);
	}

	public function getFollowerTrafficLight() {
		$breakdown = PlusRobot::getTopUserBreakdown();
		$traffic_light = array();
		foreach ($breakdown as $follower_count) {
			$traffic_light[$follower_count] = $follower_count	<= $this->followers;
		}
		return $traffic_light;
	}

	protected function trim($text, $length) {
		if (mb_strlen($text) > $length) {
			$sub = mb_substr($text, 0, $length);
			$sub = mb_substr($sub, 0, mb_strrpos($sub, ' '));
			$string = mb_substr($sub, 0, mb_strlen($sub)  - 1);
			$last_symbol = mb_substr($sub, -1);
			if (preg_match('/\pL/u', $last_symbol)) {
				$string = $string . $last_symbol;
			}
			return  $string . '...';
		}
		else return $text;
	}


	public function getPhoto($size = 200) {
		return 'https:' . $this->profile_photo . '?sz=' . $size;
	}

	public function getFollowerCount() {
		if ($this->followers > 9999 && $this->followers < 100000) {
			return round($this->followers / 1000, 1) . 'k';
		}
		elseif ($this->followers >= 100000) {
			return round($this->followers / 1000, 0) . 'k';
		}
		else {
			return $this->followers;
		}
	}

	public function getProfileLink() {
		return 'https://plus.google.com/u/0/' . $this->googleplus_id;
	}
	
	public function getCategory() {
		return $this->category;
	}



}

?>
