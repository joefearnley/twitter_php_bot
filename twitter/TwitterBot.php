<?php

/**
 * TwitterBot.php
 *
 * @author joe fearnley
 * @date 12/23/09
 * 
 * @version 0.1
 */ 

require_once 'Twitter.php';

class TwitterBot extends Twitter {

    /**
     * Terms sent to Twitter to search on.
     * @access private
     * @var array
     */
    private $search_terms = array();
    
    /**
     * Results per page for search api.
     * @access private
     * @var int
     */
    private $search_results_per_page = 15;

    /**
     * @access private
     * @var int
     */
    private $number_of_followees;
    
    /**
     * @access private
     * @var string
     */
    private $username;

    /**
     * @access private
     * @var string
     */
    private $password;

    /**
     * Overloaded constructor.
     * 
     * @param string username
     * @param string password
     * @param string search_terms
     */   
    public function TwitterBot($username=false, $password=false, $search_terms=false) 
    {
        $this->username = $username;
        $this->password = $password;
        $this->search_terms = $search_terms;
    }

    /**
     * Accessor for username.
     */  
    public function getUsername() 
    {
        return $this->username;
    }

    /**
     * Accessor for search_terms.
     */  
    public function getSearchTerms() 
    {
        return $this->search_terms;
    }

    /**
     * Mutator for search_terms.
     */   
    public function setSearchTerms($terms=array())
    {
        $this->search_terms = $terms;
    }
	
    /**
     * Mutator for results per page. This defaults to 15, so set it if you want more.
     */  
    public function setSearchResultsPerPage($rpp) 
    {
        return $this->search_results_per_page = $rpp;
    }

    /**
     * Main function that runs the bot.
     */   
    public function init()
    {
        $search_results = $this->search(urlencode($this->formatSearchString()), $search_results_per_page);
        foreach($search_results->results as $tweet) {
            $friendship = $this->showFriendship($this->username, $tweet->from_user);

            if(!$friendship->relationship->target->followed_by) {
                $this->followUser($friendship->relationship->target->id, true);
            }
        }
    }

    /**
     * Format the search string to the format of 'term1+term2+term3'.
     */   
    public function formatSearchString() 
    {
        $search_string = '';
        for($i = 0; $i < sizeof($this->search_terms); $i++) {
            $search_string = $search_string . '+' . $this->search_terms[$i];
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

    /**
     * Get the number of user followees.
     */
    public function getNumberOfFollowees()
    {
        return count($this->showFriends());   
    }  
}

?>
