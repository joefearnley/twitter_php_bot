<?php

class Config {
	
	private static $username = '';
	private static $password = '';
	private static $search_terms = array();

	function getConfig() {
		$config = array();
		$config['username'] = $this->username;
		$config['password'] = $this->password;
		$config['search_terms'] = $this->search_terms;
	
		return $config;
	}
}
?>
