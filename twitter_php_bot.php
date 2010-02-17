<?php

    /**
     * twitter_php_bot.php
     *
     * Authenticate to twitter, and follow users based on search parameter. 
     * A twitter "bot" if you will. Evil I suppose. So don't use it if that's what you believe.
     * 
     * @author joe fearnley
     * @date 01/20/2010
     * 
     */

    require_once '../twitter/TwitterBot.php';

    $username = '';
    $password = '';
    $search_terms = array('', '', '');

    $twitter_bot = new TwitterBot($username, $password, $search_terms);
    $twitter_bot->init();

?>