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
require_once '../twitter/TwitterBot.php';	

class TwitterTest extends PHPUnit_Framework_TestCase 
{
	private $twitter_bot = null;

	function setUp() 
	{
		$this->twitter_bot = new TwitterBot('', '', array('hockey'));
		$avail = $this->twitter_bot->twitterAvailable();
		$this->assertTrue($avail);
	}

	public function tearDown()
	{
		unset($this->twitter_bot);
	}

	public function testSearch() 
	{
		$search_terms = $this->twitter_bot->getSearchTerms();
		$search_string = TwitterBot::formatSearchString($search_terms);
		$search_results = $this->twitter_bot->search(urlencode($search_string));

		foreach($search_results->results as $tweet) {
			$this->assertContains('hockey', trim(strtolower($tweet->text)));	
		}
	}

	public function testShowUser() 
	{
		$user_info = $this->twitter_bot->showUser(false, false, false, $this->twitter_bot->getUsername());
		$this->assertEquals($user_info->screen_name, $this->twitter_bot->getUsername());
		$this->assertEquals($user_info->id, 109110810);
		$this->assertNull($user_info->location);
	}

	public function testShowFriendship() 
	{
		$source_user = $this->twitter_bot->getUsername();
		$target_user = 'joefearnley';
		$friendship = $this->twitter_bot->showFriendship($source_user, $target_user);
		
		$this->assertEquals($friendship->relationship->target->screen_name, 'joefearnley');
		$this->assertTrue($friendship->relationship->target->followed_by);
	}

	function testShowFriends() 
	{
		$known_friend_ids = array(15909178, 10230752); 
		$found_friend_ids = $this->twitter_bot->showFriends();
		
		foreach($found_friend_ids as $friend_id) {
			$this->assertTrue(in_array($friend_id, $known_friend_ids));
		}
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