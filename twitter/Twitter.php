<?php
/**
 * Wrapper class around the Twitter API for PHP
 * Based on the class originally developed by David Billingham
 * and accessible at http://twitter.slawcup.com/twitter.class.phps
 * @author David Billingham <david@slawcup.com>
 * @author Aaron Brazell <aaron@technosailor.com>
 * @author Keith Casey <caseysoftware@gmail.com>
 *
 * @author joe fearnley - Removed unused functions and added a couple.
 *
 * @version 1.1
 * @package php-twitter
 * @subpackage classes
 */

class twitter {

    /**
     * Authenticating Twitter user
     * @var string
    */
    var $username='';
	
    /**
     * Autenticating Twitter user password
     * @var string
     */
    var $password='';

    /**
     * Recommend setting a user-agent so Twitter knows how to contact you inc case of abuse. Include your email
     * @var string
     */
    var $user_agent='';

    /**
     * Can be set to JSON (requires PHP 5.2 or the json pecl module) or XML - json|xml
     * @var string
     */
    var $type='json';

    /**
     * It is unclear if Twitter header preferences are standardized, but I would suggest using them.
     * More discussion at http://tinyurl.com/3xtx66
     * @var array
     */
    var $headers=array('Expect:', 'X-Twitter-Client: ','X-Twitter-Client-Version: ','X-Twitter-Client-URL: ');

    /**
     * @var array
     */
    var $responseInfo=array();
	
    /**
     * @var boolean
     */
     var $suppress_response_code = false;
	 
    /**
     * @var boolean
     */
     var $debug = false;
     
    function twitter()
    {
    }

    /**
     * Returns the authenticating user's friends ids. So we don't have to call the api to check 
     * every relationship.
     * 
     * @param string $screen_name. The user name of the Twitter user to query.
     * 
     * @return string
     */
    function showFriends()
    {
        if( !in_array( $this->type, array( 'xml','json'))) {
            return false;
        }
	        
        $request = 'http://twitter.com/friends/ids/' . $this->username . '.json';
        return $this->objectify( $this->process($request) );
    }
	
    /**
     * Returns the authenticating user's friends, each with current status inline.  It's also possible to request 
     * another user's friends list via the id parameter below.
     * 
     * @param integer|string $id Optional. The user ID or name of the Twitter user to query.
     * @param integer $page Optional. 
     * @return string
     */
    function friends( $id = false, $page = false )
    {
        if( !in_array( $this->type, array( 'xml','json' ) ) )
            return false;

        $args = array();
	    if( $id )
	        $args['id'] = $page;
        if( $page )
            $args['page'] = (int) $page;
	    
	    $qs = '';
        if( !empty( $args ) )
        $qs = $this->_glue( $args );
	        
        $request = ( $id ) ? 'http://twitter.com/statuses/friends/' . $id . '.' . $this->type . $qs : 'http://twitter.com/statuses/friends.' . $this->type . $qs;
        return $this->objectify( $this->process($request) );
	}

    /**
     * Checks to see if a friendship already exists
     * @param string|integer $user_a Required. The username or ID of a Twitter user
     * @param string|integer $user_b Required. The username or ID of a Twitter user
     * @return string
     */
    function isFriend( $user_a, $user_b )
    {
        if( !in_array( $this->type, array( 'xml','json' ) ) )
            return false;

        $qs = '?user_a=' . rawurlencode( $user_a ) . '&amp;' . rawurlencode( $user_b );
        $request = 'http://twitter.com/friendships/exists.' . $this->type . $qs;
        return $this->objectify( $this->process($request) );
    }

    /**
     * Get detailed information about users friendship
     *
     * @param string|integer $target_id Required. The ID of a Twitter user
     * @param string|integer $target_screen_name Required. The ID of a Twitter user
     * @return string
     */
    function showFriendship( $source_user_name,  $target_user_name)
    {
        if( !in_array( $this->type, array( 'xml','json' ))) {
            return false;
        }

        $qs = '?source_screen_name='.rawurlencode($source_user_name).'&target_screen_name='.rawurlencode($target_user_name);
        $request = 'http://twitter.com/friendships/show.'.$this->type.$qs;
        return $this->objectify( $this->process($request) );
    }

    /**
     * Sends a request to follow a user specified by ID
     * @param integer|string $id The twitter ID or screenname of the user to follow
     * @param boolean $notifications Optional. If true, you will recieve notifications from the users updates
     * @return string
     */
    function followUser( $id, $notifications = false )
    {
        if( !in_array( $this->type, array( 'xml','json' ) ) )
            return false;
	    
        $request = 'http://twitter.com/friendships/create/' . $id . '.' . $this->type;
        $postargs = array( 'id' => $id, 'follow' => $notifications);
        return $this->objectify( $this->process($request, $postargs) );
    }
	
	
    /**
     * Unfollows a user
     * @param integer|string $id the username or ID of a person you want to unfollow
     * @return string
     */
    function leaveUser( $id )
    {
        if( !in_array( $this->type, array( 'xml','json' ) ) )
            return false;
	        
        $request = 'http://twitter.com/friendships/destroy/' . $id . '.' . $this->type;
        return $this->objectify( $this->process($request) );
	}
    
    /**
     * Returns extended information of a given user, specified by ID or screen name as per the required
     * id parameter below.  This information includes design settings, so third party developers can theme
     * their widgets according to a given user's preferences.	 
     * @param integer $id Optional. The user ID.
     * @param string $email Optional. The email address of the user being requested (can use in place of $id)
     * @param integer $user_id Optional. The user ID (can use in place of $id)
     * @param string $screen_name Optional. The screen name of the user being requested (can use in place of $id)
     * @return string
     */
    function showUser( $id, $email = false, $user_id = false, $screen_name=false )
    {
        if( !in_array( $this->type, array( 'xml','json' ) ) ) {
            return false;
        }

        if( $user_id ) :
            $qs = '?user_id=' . (int) $user_id;
        elseif ( $screen_name ) :
            $qs = '?screen_name=' . (string) $screen_name;
        elseif ( $email ) :
            $qs = '?email=' . (string) $email;
        else :
            $qs = (int) $id;
        endif;

        $request = 'http://twitter.com/users/show.' . $this->type .  $qs;
        return $this->objectify( $this->process($request) );
    }

    /**
     * Search twitter for tweets containing the terms;
     *
     * @author joe fearnley
     *
     * @param $terms
     * @param $rpp
     * 
     * @return twitter api object
     */
    function search($terms='', $rpp=false) 
    {
        if( $terms ==  '') {
            return false;
        }

        $qs = '?q=' . $terms;

        if($rpp) {
            $qs .= '&rpp=' . $rpp;
        }

        $request = 'http://search.twitter.com/search.' . $this->type . $qs;	
        return $this->objectify( $this->process($request) );
    }

    /**
     * Rate Limit API Call. Sometimes Twitter needs to degrade. Use this non-ratelimited API call to work your logic out
     * @return integer|boolean 
     */
    function ratelimit()
    {
        if( !in_array( $this->type, array( 'xml','json' ) ) )
            return false;
        $request = 'http://twitter.com/account/rate_limit_status.' . $this->type;
        return $this->objectify( $this->process($request) );
    }

    /**
     * Rate Limit statuses (extended). Provides helper data like remaining-hits, hourly limit, reset time and reset time in seconds
     * @deprecated
     */
    function ratelimit_status()
    {
        return $this->ratelimit();
    }

    /**
     * Detects if Twitter is up or down. Chances are, it will be down. ;-) Here's a hint - display CPM ads whenever Twitter is down
     * @return boolean
     */
    function twitterAvailable()
    {
        if( !in_array( $this->type, array( 'xml','json' ) ) )
            return false;
 
        $request = 'http://twitter.com/help/test.' . $this->type;
        if( $this->objectify( $this->process($request) ) == 'ok' )
               return true;

        return false;
    }

    /**
     * Internal function where all the juicy curl fun takes place
     * this should not be called by anything external unless you are
     * doing something else completely then knock youself out.
     * @access private
     * @param string $url Required. API URL to request
     * @param string $postargs Optional. Urlencoded query string to append to the $url
	 */
    function process($url,$postargs=false)
    {
        $url = ( $this->suppress_response_code ) ? $url . '&suppress_response_code=true' : $url;
        $ch = curl_init($url);

        if($this->username !== false && $this->password !== false)
            curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        if( $this->debug ) :
            curl_setopt($ch, CURLOPT_HEADER, true);
        else :
            curl_setopt($ch, CURLOPT_HEADER, false);
        endif;
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        if($postargs !== false)
        {
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
        }

        $response = curl_exec($ch);
        $this->responseInfo=curl_getinfo($ch);
        curl_close($ch);


        if( $this->debug ) :
            $debug = preg_split("#\n\s*\n|\r\n\s*\r\n#m", $response);
            echo'<pre>' . $debug[0] . '</pre>'; exit;
        endif;
        
        if( intval( $this->responseInfo['http_code'] ) == 200 )
            return $response;    
        else
            return false;
	}

    /**
     * Function to prepare data for return to client
     * @access private
     * @param string $data
     */
    function objectify( $data )
    {
        if( $this->type ==  'json' ) {
            return json_decode( $data );
        } else if( $this->type == 'xml' ) {
            if( function_exists('simplexml_load_string') ) :
                $obj = simplexml_load_string( $data );			        
            endif;
            return $obj;
        } else {
            return false;
        }
	}
	
    /**
     * Function to piece together a cohesive query string
     * @access private
     * @param array $array
     * @return string
     */
    function _glue( $array )
    {
        $query_string = '';
        foreach( $array as $key => $val ) :
            $query_string .= $key . '=' . rawurlencode( $val ) . '&';
        endforeach;
        return '?' . substr( $query_string, 0, strlen( $query_string )-1 );
    }
}

?>