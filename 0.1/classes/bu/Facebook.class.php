<?php
if (!defined('_IN_YELAGO')) die();

class Facebook
{
	static $_facebook=null;
	
	public function get_instance()
	{
		if (self::$_facebook===null)
		{
			//require_once('../config.php');
			require_once(_LIBRARIES.'/facebook/facebook.php');
			$config['appId'] = _YEL_FACEBOOK_APP_ID;
			$config['secret'] = _YEL_FACEBOOK_APP_SECRET;
			print_r($config);
			self::$_facebook = new Facebook();
			$_facebook = $_facebook($config);
		}
		return self::$_facebook;
	}
}
?>