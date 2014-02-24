<?php
if (!defined('_IN_YELAGO')) die();

class Core
{
	static $_core=null;
	
	private $_facebook=null;
	
	private $_app_access_token;
	
	private $_action;
	
	public function get_instance()
	{
		if (self::$_core===null)
		{
			self::$_core=new Core();
			require_once(_LIBRARIES.'/facebook/facebook.php');
			$params = array(
				'appId'=>_YEL_FACEBOOK_APP_ID,
				'secret'=>_YEL_FACEBOOK_APP_SECRET
			);
			self::$_core->_facebook = new Facebook($params);
			self::$_core->_set_app_token();
		}
		return self::$_core;
	}
	
	public function init()
	{
		//require_once(_CLASSES.'/Session.class.php');
		//require_once(_CLASSES.'/MySQL.class.php');
		//require_once(_CLASSES.'/User.class.php');
		//require_once(_CLASSES.'/Template.class.php');
		//require_once(_CLASSES.'/User.class.php');
		
		if (isset($_POST['a'])) self::$_core->_action = $_POST['a'];
	}
	
	public function get_action()
	{
		return self::$_core->_action;
	}
	
	public function get_app_access_token()
	{
		return self::$_core->_app_access_token;
	}
	
	private function _set_app_token()
	{
		$token_url = "https://graph.facebook.com/oauth/access_token?"
			."client_id=" . _YEL_FACEBOOK_APP_ID
			."&client_secret=" . _YEL_FACEBOOK_APP_SECRET
			."&grant_type=client_credentials";
		$app_token = file_get_contents($token_url);
		$app_token = explode('=', $app_token);
		$this->_app_access_token = $app_token[1];
	}
	
	public function debug_access_token($token)
	{
		$debug = self::$_core->_facebook->api('/debug_token','GET',array('input_token' => $token, 'access_token' => $this->get_app_access_token()));
		return $debug;
	}
}