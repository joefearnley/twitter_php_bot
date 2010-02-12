<?php

/**
 * TwitterBot.php
 *
 * @author joe fearnley
 * @date 12/23/09
 * 
 */ 
class TwitterBot {
	
	function __construct() 
	{ 
		// create a twitter object...
	}

	public function formatSearchString($search_terms = array()) {	

		$search_string = '';
	
		for($i = 0; $i < sizeof($search_terms); $i++) {
			$search_string = $search_string . '+' . $search_terms[$i];
		}

		if($search_string[0] == '+') {
			$search_string[0] = '';
		}

		return trim($search_string);
	}
}

?>
