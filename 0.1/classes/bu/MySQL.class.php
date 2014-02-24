<?php
if (!defined('_IN_YELAGO')) die();

class MySQL
{
	static $_mysql=null;

	public function get_instance()
	{
		if (self::$_mysql===null)
		{
			require_once(_LIBRARIES.'/adodb5/adodb.inc.php');
			try
			{
				self::$_mysql = &ADONewConnection('mysql');
				self::$_mysql->Connect(_MYSQL_SERVER, _MYSQL_USER, _MYSQL_PASSWORD, _MYSQL_DATABASE);
				self::$_mysql->Execute("set names 'utf8'");
				self::$_mysql->debug = _MYSQL_DEBUG;
				self::$_mysql->LogSQL = _MYSQL_LOG;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$ADODB_LANG = 'en';
				$ADODB_FORCE_TYPE = 1;
				//$ADODB_CACHE_DIR = _COC_CACHE.'/mysql';
				self::$_mysql->LogSQL();
			}
			catch(Exception $e)
			{
				die('Mysql error...');
			}
		}
		return self::$_mysql;
	}
}
?>
