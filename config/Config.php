<?php

class Config {
	
	private static $username;
	private static $password;
	private static $search_terms;
	private static $instance;

	private function __construct() 
	{ 
		$this->username = '';
 		$this->password = '';	
		$this->search_terms = array('');
	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new Config();
		}

		return self::$instance;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}
	
	public function getSearchTerms() {
		return $this->search_terms;
	}
}
?>
