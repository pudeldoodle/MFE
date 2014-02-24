<?php
if (!defined('_IN_YELAGO')) die();

class Session
{
	static $_session=null;

	public function get_instance()
	{
		if (self::$_session===null)
		{
			if(!@session_start())
			{
				//setcookie("PHPSESSID", "", time()-3600);
				session_start();
			}
			self::$_session = new Session();
			self::$_session->_id = session_id();
		}
		return self::$_session;
	}
	
	public function set($category, $key, $value)
	{
		$data = $data = array();
		$data[$key] = $value;
		$_SESSION[$category] = $data;
		
	}
	
	public function destroy($category, $key)
	{
		unset($_SESSION[$category][$key]);
	}

	public function get($category, $key)
	{
		if (isset($_SESSION[$category][$key]))
			return $_SESSION[$category][$key];
		return false;
	}
	
	public function get_session()
	{
		$tabTemp = array();
		foreach ($_SESSION as $key=>$val)
		{
			$tabTemp[$key] = $val;
		}
		return $tabTemp;
	}
}
?>