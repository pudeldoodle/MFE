<?php
function check_workers_by_server($function_name)
{
	$gm_admin = new GearmanAdmin();
	$gm_admin->refreshWorkers();
	$gm_workers = $gm_admin->getWorkers();
	
	//print_r($gm_workers);
	
	$workers_by_server = array();
	foreach($gm_workers->_workers as $key => $value)
	{
		if(!isset($workers_by_server[$value->_ip]))
		{
			$workers_by_server[$value->_ip] = array();
			if(!isset($workers_by_server[$value->_ip][$value->_functions[0]]))
				$workers_by_server[$value->_ip][$value->_functions[0]] = 1;
			else
				$workers_by_server[$value->_ip][$value->_functions[0]]++;
		}
		else
		{
			if(!isset($workers_by_server[$value->_ip][$value->_functions[0]]))
				$workers_by_server[$value->_ip][$value->_functions[0]] = 1;
			else
				$workers_by_server[$value->_ip][$value->_functions[0]]++;
		}
	}
	
	//print_r($workers_by_server);
	
	foreach($workers_by_server as $server => $functions)
	{
		if($server == "172.28.8.200")
		{
			if($functions[$function_name]>_MFE_MAX_WORKERS_BY_SERVER) die;
		}
	}
}
?>