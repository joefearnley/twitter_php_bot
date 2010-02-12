<?php

/**
 * TwitterBotTest.php
 *
 * @author joe fearnley
 * @date 01/21/2010
 * 
 * These are the tests for the TwitterBot class.
 */ 

require_once 'PHPUnit/Framework.php';
require_once '../twitter/Twitter.php';
require_once '../twitter/TwitterBot.php';
require_once '../config/Config.php';

class TwitterBotTest extends PHPUnit_Framework_TestCase 
{	
	protected $twitter = null;
	protected $summize = null;
	protected $config = null;
	protected $twitter_bot = null;
 
	public function setUp() 
	{	
		$this->config = Config::getInstance();
		$this->twitter = new twitter();
		$this->summize = new summize();
		$this->twitter_bot = new TwitterBot();
	}

	public function tearDown()
	{
		unset($this->config);
		unset($this->twitter);
		unset($this->summize);
		unset($this->twitter_bot);
	}

	public function testFormatSearchString($search_string = '') {
		$search_terms = array('hockey','peter klima','cheifs');
		$this->assertEquals($this->twitter_bot->formatSearchString($search_terms), 'hockey+peter klima+cheifs');

		$search_terms = array('looking','for','hookers');
		$this->assertEquals($this->twitter_bot->formatSearchString($search_terms), 'looking+for+hookers');
	}
}

?>
