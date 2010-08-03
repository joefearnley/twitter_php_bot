<?php

/**
 * TwitterBotTest.php
 *
 * @author joe fearnley
 * @date 01/21/2010
 * 
 * These are the tests for the TwitterBot class. The functions names
 * reflect which functions of the TwitterBot class they test.
 */ 

require_once 'PHPUnit/Framework.php';
require_once '../twitter/TwitterBot.php';

class TwitterBotTest extends PHPUnit_Framework_TestCase 
{
    private $twitter_bot;
 
    public function setUp() 
    {
        $this->twitter_bot = new TwitterBot('', '', array(''));
    }

    public function tearDown()
    {
        unset($this->twitter_bot);
    }

    public function testInit($search_string = '') 
    {
        // get the current number of friends
        $number_of_friends = count($this->twitter_bot->showFriends());
        $this->twitter_bot->init();
		
        // number of friends should be higher
        $this->assertTrue($number_of_friends < count($this->twitter_bot->showFriends()));

        // do another test with different search terms, then test friend count again.
        $number_of_friends = count($this->twitter_bot->showFriends());
        $this->twitter_bot->setSearchTerms(array('karate kid'));
        $this->twitter_bot->init();

        // should be following even more now 
        $this->assertTrue($number_of_friends < count($this->twitter_bot->showFriends()));
    }

    public function testFormatSearchString() {
        $this->twitter_bot->setSearchTerms(array('hockey','peter klima','cheifs'));
        $this->assertEquals($this->twitter_bot->formatSearchString(), 'hockey+peter klima+cheifs');

        $this->twitter_bot->setSearchTerms(array('looking','for','hookers'));
        $this->assertEquals($this->twitter_bot->formatSearchString(), 'looking+for+hookers');
    }
}

?>
