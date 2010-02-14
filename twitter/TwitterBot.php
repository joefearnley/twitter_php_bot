<?php

/**
 * TwitterBot.php
 *
 * @author joe fearnley
 * @date 12/23/09
 * 
 */ 

require_once '../twitter/Twitter.php';

class TwitterBot extends Twitter {

	private $search_terms = array();

	public function TwitterBot($username, $password, $search_terms) 
	{ 
		$this->username = $username;
		$this->password = $password;
		$this->search_terms = $search_terms;
	}

	public function getUsername() {
		return $this->username;
	}
	
	public function getSearchTerms() {
		return $this->search_terms;
	}
	
	public function init()
	{
		$search_terms = $this->search_terms;
		$search_string = $this->formatSearchString($search_terms);
		$search_results = $this->search(urlencode($search_string));

		foreach($search_results->results as $tweet) {
			$user_info = $this->twitter->showUser(false, false, false, $this->username);
			$friendship = $this->showFriendship($source_user, $target_user);

			if(!$friendship->relationship->target->followed_by) {
				$this->followUser($user_info->id, false);


			}
		}
	}

	public function formatSearchString($search_terms = array()) 
	{
		$search_string = '';
		for($i = 0; $i < sizeof($search_terms); $i++) {
			$search_string = $search_string . '+' . $search_terms[$i];
		}

		$search_string[0] = ($search_string[0] == '+') ? '' : $search_string[0];
		return trim($search_string);
	}
}

?>
