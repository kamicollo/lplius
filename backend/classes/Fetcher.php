<?php

/**
 * Object-oriented cURL wrapper. Created before Guzzle was popular.
 */

class Fetcher {

	private $options = array();
	private static $self = null;
	
	public function __construct(array $options = null) {
		$this->options = self::defaultOptions();
		if ($options !== null) $this->options += $options;
	}

	public function fetchOne($url, array $options = null) {
		if ($options !== null) $this->options += $options;
		$ch = curl_init($url);
		curl_setopt_array($ch, $this->options);
		$output = curl_exec($ch);
		$obj = new FetchResult($ch, $output, $this->options[CURLOPT_HEADER]);
		curl_close($ch);
		return $obj;
	}
	
	public static function quickFetch($url) {
		if (self::$self === null) {
			self::$self = new self();
		}
		return self::$self->fetchOne($url);
	}
	
	public function fetchMany($url, array $options = null) {
	 //TODO
	}
	
	private static function defaultOptions() {
		return array(
			CURLOPT_AUTOREFERER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CONNECTTIMEOUT => 3,
			CURLOPT_MAXREDIRS => 5,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_HTTPGET => true,
			CURLOPT_HEADER => true,			
			CURLOPT_FOLLOWLOCATION => true,
		);
	
	}
	
}

class FetchResult {

	protected $content = '';
	protected $header = '';
	protected $info = array();
	protected $error = 0;
	protected $errorMessage = '';

	public function __construct($ch, $content, $includesHeader) {
		$this->info = curl_getinfo($ch);
		$this->error = curl_errno($ch);
		$this->errorMessage = curl_error($ch);
		if ($includesHeader == false) {
				$this->content = $content;
		}
		else {
			$this->header = substr($content, 0, $this->getInfo('header_size'));
			$this->content = substr($content, $this->getInfo('header_size'));
		}
	}
	
	public function getInfo($param) {
		if (isset($this->info[$param])) {
			return $this->info[$param];
		}
		else {
			return null;
		}	
	}
	
	public function getContent() {
		return $this->content;
	}
	
	public function getHeader() {
		return $this->header;
	}
	
	public function getHTTPCode() {
		return $this->getInfo('http_code');
	}
	
	public function getError() {
		return $this->error;
	}
}
