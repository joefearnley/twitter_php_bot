<?php

/**
 * TwitterBot.php
 *
 * @author joe fearnley
 * @date 12/23/09
 * 
 * @version 0.1
 */ 

require_once '../twitter/Twitter.php';

class TwitterBot extends Twitter {

    /**
     * Terms sent to Twitter to search on.
     * @access private
     * @var array
     */
    private $search_terms = array();

    /**
     * Default constructor. Sets used variables to null.
     */    
    public function TwitterBot() 
    {
        $this->username = null;
        $this->password = null;
        $this->search_terms = null;
    }
    
    /**
     * Overloaded constructor.
     * 
     * @param string username
     * @param string password
     * @param string search_terms
     */   
    public function TwitterBot($username, $password, $search_terms) 
    {
        $this->username = $username;
        $this->password = $password;
        $this->search_terms = $search_terms;
    }

    /**
     * Accessor for username.
     */  
    public function getUsername() {
        return $this->username;
    }

    /**
     * Accessor for search_terms.
     */  
    public function getSearchTerms() {
        return $this->search_terms;
    }

    /**
     * Mutator for search_terms.
     */   
	public function setSearchTerms($terms) {
		$this->search_terms = $terms;
	}
	
    /**
     * Main function that runs the bot.
     */   
    public function init()
    {
        $search_terms = $this->search_terms;
        $search_string = $this->formatSearchString($search_terms);
        $search_results = $this->search(urlencode($search_string));

        foreach($search_results->results as $tweet) {
            $friendship = $this->showFriendship($this->username, $tweet->from_user);

            if(!$friendship->relationship->target->followed_by) {
                $this->followUser($friendship->relationship->target->id, true);
            }
        }
    }

    /**
     * Format the search string to the format of 'term1+term2+term3'.
     * 
     * @param string search_terms
     */   
    public function formatSearchString($search_terms = array()) 
    {
        $search_string = '';
        for($i = 0; $i < sizeof($search_terms); $i++) {
            $search_string = $search_string . '+' . $search_terms[$i];
        }

        $search_string[0] = ($search_string[0] == '+') ? '' : $search_string[0];
        return trim($search_string);
    }

    /**
     * Get the user's followees and unfollow them.
     */
    public function unfollowAllFriend()
    {
        $found_friend_ids = $this->twitter_bot->showFriends();
        foreach($found_friend_ids as $friend_id) {
            $this->leaveUser($friend_id);
        }
    }
}

?>