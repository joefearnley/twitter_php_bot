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

class TwitterTest extends PHPUnit_Framework_TestCase {

	private $twitter = null;

	function setUp() 
	{
		include_once('../twitter/Twitter.php');
		include_once('../config/config.php');
		
		$twitter = new twitter($config['username'], $config['password']);
		$avail = $twitter->twitterAvailable();
		//$this->assertTrue($avail);
	}

	function testSearch() 
	{
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
