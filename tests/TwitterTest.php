<?php

/**
 * TwitterTest.php
 *
 * @author joe fearnley
 * @date 01/21/2010
 * 
 * I modified the Twitter class by adding some additional
 * functions. These are the units tests for those functions.
 */

require_once 'PHPUnit/Framework.php';
require_once '../twitter/Twitter.php';
require_once '../twitter/TwitterBot.php';
require_once '../config/Config.php';	

class TwitterTest extends PHPUnit_Framework_TestCase 
{
	protected $twitter = null;
	protected $summize = null;
	protected $config = null;
	
	function setUp() 
	{	
		$this->config = Config::getInstance();
		$this->twitter = new twitter();
		$this->summize = new summize();
		
//		$this->twitter->username = $this->config->getUsername(); 
//		$this->twitter->password = $this->config->getPassword();

//		$this->summize->username = $this->config->getUsername(); 
//		$this->summize->password = $this->config->getPassword();
				
		$avail = $this->twitter->twitterAvailable();
		$this->assertTrue($avail);
	}

	public function tearDown()
	{
		unset($this->config);
		unset($this->twitter);
		unset($this->summize);
	}
	
	function testSearch() 
	{
		$search_terms = $this->config->getSearchTerms();
		$search_string = TwitterBot::formatSearchString($search_terms);
		$search_results = $this->summize->search(urlencode($search_string));

		foreach($search_results->results as $tweet) {
			$this->assertTrue(strpos(trim(strtolower($tweet->text)), 'hockey') > 0);
		}
	}

    function testSearchSingle() 
	{
	}

	function testShowUserByUserName() 
	{
	}

	function testShowFriendship() 
	{
	}

	function testPantsOnTheGround() 
	{
	}
}

?>
