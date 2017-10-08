<?php 

require_once('vendor/autoload.php');

class Session
{

	public function __construct(){
		$session = new Gears\Session();
	}

	public static function set($key, $value){
		self::sessionStart(SESSION_NAME, SESSION_LIFE);
		$_SESSION[$key] = $value;
	}

	public static function get($key){
		self::sessionStart(SESSION_NAME, SESSION_LIFE);
		return $_SESSION[$key];
	}

	public static function destroy(){
		self::sessionStart(SESSION_NAME, SESSION_LIFE);
		$_SESSION = [];
		session_destroy();
	}

	private static function sessionStart($name, $limit = 0, $path = '/', $domain = null, $secure = null)
	{
		// Set the cookie name before we start.
		session_name($name . '_Session');

		// Set the domain to default to the current domain.
		$domain = isset($domain) ? $domain : isset($_SERVER['SERVER_NAME']);

		// Set the default secure value to whether the site is being accessed with SSL
		$https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

		// Set the cookie settings and start the session
		session_set_cookie_params($limit, $path, $domain, $secure, true);

		session_start();
		var_dump($_SESSION);echo '<hr>';

		// Make sure the session hasn't expired, and destroy it if it has
		if(self::validateSession())
		{
			// Check to see if the session is new or a hijacking attempt
			if(!self::preventHijacking())
			{
				// Reset session data and regenerate id
				$_SESSION = array();
				$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
				self::regenerateSession();

			// Give a 5% chance of the session id changing on any request
			}else{
				self::regenerateSession();
			}
		}else{
			$_SESSION = array();
			session_destroy();
			session_start();
		}
		var_dump($_SESSION);echo '<hr>';
	}

	private static function preventHijacking()
	{
		if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
			return false;

		if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
			return false;

		if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
			return false;

		return true;
	}

	private static function regenerateSession()
	{
		// If this session is obsolete it means there already is a new id
		if(isset($_SESSION['OBSOLETE']) && $_SESSION['OBSOLETE'] == true)
			return;

		// Set current session to expire in 10 seconds
		$_SESSION['OBSOLETE'] = true;
		$_SESSION['EXPIRES'] = time() + SESSION_LIFE;

		// Create new session without destroying the old one
		session_regenerate_id(false);

		// Grab current session ID and close both sessions to allow other scripts to use them
		$newSession = session_id();
		session_write_close();

		// Set session ID to the new one, and start it back up again
		session_id($newSession);
		session_start();

		// Now we unset the obsolete and expiration values for the session we want to keep
		unset($_SESSION['OBSOLETE']);
		unset($_SESSION['EXPIRES']);
	}


	private static function validateSession()
	{
		if( isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']) )
			return false;

		if(isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time())
			return false;

		return true;
	}
    
}