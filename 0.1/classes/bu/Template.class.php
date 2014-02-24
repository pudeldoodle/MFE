<?php
if (!defined('_IN_YELAGO')) die();

class Template
{
	static $_template=null;
	
	public function get_instance()
	{
		if (self::$_template===null)
		{
			//require_once('./config.php');
			require_once(_LIBRARIES.'/smarty/libs/Smarty.class.php');
			self::$_template = new Smarty();
			self::$_template->template_dir = _TPL;
		}
		return self::$_template;
	}
}
?>