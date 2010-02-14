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
	protected $config = null;

	function setUp() 
	{
		$this->config = Config::getInstance();
		$this->twitter = new twitter();
		
//		$this->twitter->username = $this->config->getUsername(); 
//		$this->twitter->password = $this->config->getPassword();
				
		$avail = $this->twitter->twitterAvailable();
		$this->assertTrue($avail);
	}

	public function tearDown()
	{
		unset($this->config);
		unset($this->twitter);
	}

	public function testSearch() 
	{
		$search_terms = $this->config->getSearchTerms();
		$search_string = TwitterBot::formatSearchString($search_terms);
		$search_results = $this->twitter->search(urlencode($search_string));
	
		foreach($search_results->results as $tweet) {
			$this->assertTrue(strpos(trim(strtolower($tweet->text)), 'hockey') > 0);
		}
	}

	public function testShowUser() 
	{
		$user_info = $this->twitter->showUser(false, false, false, $this->config->getUsername());
		$this->assertEquals($user_info->screen_name, $this->config->getUsername());
		$this->assertEquals($user_info->id, 109110810);
		$this->assertNull($user_info->location);
	}

	public function testShowFriendship() 
	{
		$source_user = $this->config->getUsername();
		$target_user = 'joefearnley';
		$friendship = $this->twitter->showFriendship($source_user, $target_user);
		
		$this->assertEquals($friendship->relationship->target->screen_name, 'joefearnley');
		$this->assertTrue($friendship->relationship->target->followed_by);
	}

	public function testPantsOnTheGround() 
	{
		$pants_on_the_group = true;
		$lookin_like_a_fool = true;
		$this->assertTrue($pants_on_the_group);
		$this->assertTrue($lookin_like_a_fool);
	}
}

?>
