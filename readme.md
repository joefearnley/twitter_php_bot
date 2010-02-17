###PHP Twtter Bot

A twitter bot that befriends users based on given search terms. It utilizes a modified version of the [php-twitter library](http://code.google.com/p/php-twitter/).

The idea is to run it on a regular basis, like everyday, in the morning maybe. You would need a computer with access to the twitter api and some sort of job scheduler. Or I guess if you can figure how to send a message in a bottle or via western union to the twitter api. In which case please fork this and tell me how you do it. I think this could have been useful a couple of years ago. Backward thinking?

###Usage
Modify the the `php_twitter_bot.php` file by updating the `$username`, `$password`, and `$search_terms` variables with your twitter credentials.Then run the following.

    [~#] php php_twitter_php.php

###Requirements
* PHP 5.2