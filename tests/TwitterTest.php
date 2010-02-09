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
		$search_string = '';
		$search_terms = $this->config->getSearchTerms();
	
		for($i = 0; $i < sizeof($search_terms); $i++) {
			$search_string = $search_string . '+' . $search_terms[$i];
		}

		if($search_string[0] == '+') {
			$search_string[0] = '';
		}

		$search_results = $this->summize->search($search_string);
		$this->assertTrue($search_results);
		
		foreach($search_results->results as $tweet) {
			$this->assertTrue($tweet);
			$this->assertTrue(strpos($tweet, 'hockey') === true);
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
