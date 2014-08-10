<?php

/**
 * Main Entity model for Google+ profiles
 */

class PlusPersonCore extends PlusPerson {

	public $plusperson_id = 0;
  public $googleplus_id = "";
  public $profile_photo = "";
  public $first_name = "";
  public $last_name = "";
  public $introduction = "";
  public $subhead = "";
  public $category = 0;
  public $raw_data = "";
  public $fetched_relationships = 0;
  public $created_dt = "";
  public $modified_dt = "";
  public $followers = 0;
  public $hasposts = 0;

	public function __construct($id = '', $category = '') {
		if ($id !== '') {
			$this->googleplus_id = $id;
			$this->category = $category;
		}
	}

	public function getId() {
		return $this->googleplus_id;
	}


	public function loadByGooglePlusID( $id ) {
		throw new Exception('method not allowed! - no interaction with DB here');
  }

  public function updateFromGooglePlusService() {
  	if ($this->googleplus_id != "") {
  		$profile_url = 'https://plus.google.com/_/profiles/get/' . $this->googleplus_id;
			$jsondata = GoogleUtil::FetchGoogleJSON($profile_url);
			if ($jsondata === null) { //Google returned 404
			    throw new PlusException('+Pasiūlytas Google+ vartotojas nerastas.', 10, $jsondata, $this->plusperson_id);
			}
			elseif ( (isset($jsondata[0][0])) &&  ($jsondata[0][0] == 'er') ) { //received a json array with error from Google!				
				if ($jsondata[0][4][1] == 'REDIRECTED') { //redirect error: to be handled by robot
					throw	new PlusException('Redirected user', 20, $jsondata, $this->plusperson_id);
				}
				else { //unknown Google+ error
					throw new PlusException('Nežinoma Google grąžinta klaida', 10, $jsondata, $this->plusperson_id);				
				}
			}
			else { //everything looks fine - update.
				$updatedFollowers = $this->loadFromGooglePlusJSON($jsondata);
				if ($updatedFollowers == false) {
					//try to find the followers by scraping
					$this->getFollowersFromGooglePlusPage();
				}
				//find if the person has public posts
				$posts = PlusPost::FetchActivityStream($this->googleplus_id);
				if (!empty($posts)) $this->hasposts = 1;
    	}
    }
    else {
    	//how can it come here?
    }
  }
  
  protected function getFollowersFromGooglePlusPage() {
  echo 'https://plus.google.com/u/0/' . $this->googleplus_id .  '/posts';
  	$html = Fetcher::quickFetch('https://plus.google.com/' . $this->googleplus_id .  '/posts');
  	if (($html->getError() == 0) && ($html->getHTTPCode() == 200)) { //only try this if everything's OK
			preg_match('/Turi [^<]+ ratuose \((\d+)\)/',$html->getContent(), $matches);
			if (isset($matches[1])) $this->followers = $matches[1];
  	}
  	else {
  		throw new Exception('CURL or Google+ page access error!', ($html->getError() == 0) ? $html->getHTTPCode() : $html->getError());
  	}
  }

  protected function loadFromGooglePlusJSON($data) {
  	parent::loadFromGooglePlusJSON($data);
  	if ($this->first_name == $this->last_name)	$this->last_name = '';
  	return $this->getFollowers($data);
  }
  
  protected function getFollowers($data) {  	
	  $followers = 0;
	  if (isset($data[0][1][3][2][0])) $followers = $data[0][1][3][2][0];
	  elseif (isset($data[0][1][2][39][1])) $followers = $data[0][1][2][39][1];
	  
	  if ($followers > 0) {
		  $this->followers = $followers;
		  return true;
	  }
	  return false;
  }

  public function dbValues() {
	  return array(
		  'googleplus_id' => $this->googleplus_id,
		  'first_name' => $this->first_name,
		  'last_name' => $this->last_name,
		  'subhead' => $this->subhead,
		  'introduction' => $this->introduction,
		  'profile_photo' => $this->profile_photo,
		  'category' => $this->category,
		  'raw_data' => $this->raw_data,
		  'followers' => $this->followers,
		  'fetched_relationships' => $this->fetched_relationships,
		  'plusperson_id' => $this->plusperson_id,
		  'state' => 1,
		 	'hasposts' => $this->hasposts,
		  'modified_dt' => date('Y-m-d H:i:s'),
		  'created_dt' => date('Y-m-d H:i:s')
  	);
  }
}

class PlusException extends Exception {
	
	public $data = array();
	public $id = 0;
	
	public function __construct($message = null, $code = 0, $data = array(), $id = null) {
		parent::__construct($message, $code);
		$this->data = $data;
		$this->id = $id;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getId() {
		return $this->id;
	}
	
}

?>
