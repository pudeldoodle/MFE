<?php

define('_IN_MFE', true);

require_once('../../config.php');

require_once(_LIBRARIES.'/gearman-admin/GearmanAdmin.php');

switch($_GET['a'])
{
	case 'json':
	{
		header('Access-Control-Allow-Credentials: true');
		//header('Access-Control-Allow-Origin: http://tools.yelago.com');
		header('Content-Type: application/json; charset=utf-8');
		$json = array();
		$gm_admin = new GearmanAdmin();
		$gm_status = $gm_admin->getStatus();
		$json['workers'] = array();
		foreach($gm_status->_functions as $function_name => $status)
		{
			$data = array(
				"worker" => $function_name,
				"queued_jobs" => intval($status[0]),
				"running_jobs" => intval($status[1]),
				"avalaible_workers" => intval($status[2])
			);
			array_push($json['workers'],$data);
		}
		$gm_workers = $gm_admin->getWorkers();
		$workers_by_server = array();
		foreach($gm_workers->_workers as $key => $value)
		{
			if(!isset($workers_by_server[$value->_ip]))
			{
				$workers_by_server[$value->_ip] = array();
				if(!isset($workers_by_server[$value->_ip][$value->_functions[0]])) $workers_by_server[$value->_ip][$value->_functions[0]] = 1;
				else $workers_by_server[$value->_ip][$value->_functions[0]]++;
			}
			else
			{
				if(!isset($workers_by_server[$value->_ip][$value->_functions[0]])) $workers_by_server[$value->_ip][$value->_functions[0]] = 1;
				else $workers_by_server[$value->_ip][$value->_functions[0]]++;
			}
		}
		$json['servers'] = array();
		$server_count = 0;
		foreach($workers_by_server as $server => $functions)
		{
			//$json['servers'][$server] = array();
			$json['servers'][$server_count]['ip'] = $server;
			$json['servers'][$server_count]['details'] = array();
			foreach($functions as $function_name => $number_of_workers)
			{
				$data = array(
					"worker" => $function_name,
					"total_workers" => $number_of_workers
				);
				array_push($json['servers'][$server_count]['details'],$data);
			}
			$server_count++;
		}
		print_r(json_encode($json));
	}break;
	
	default:
		$gm_admin = new GearmanAdmin();
		$gm_status = $gm_admin->getStatus();
		foreach($gm_status->_functions as $function_name => $status)
		{
			print_r("-------------------------\n");
			print_r("| WORKER : ".$function_name."\n");
			print_r("| jobs in queue : ".$status[0]."\n");
			print_r("| jobs running : ".$status[1]."\n");
			print_r("| available workers : ".$status[2]);
			print_r("-------------------------\n");
		}
		
		$gm_workers = $gm_admin->getWorkers();
		
		//print_r($gm_workers);
		$workers_by_server = array();
		foreach($gm_workers->_workers as $key => $value)
		{
			//print_r($value->_ip);
			//print_r($value->_functions[0]);
			if(!isset($workers_by_server[$value->_ip]))
			{
				
				$workers_by_server[$value->_ip] = array();
				
				if(!isset($workers_by_server[$value->_ip][$value->_functions[0]]))
				{
					$workers_by_server[$value->_ip][$value->_functions[0]] = 1;
				}
				else
				{
					$workers_by_server[$value->_ip][$value->_functions[0]]++;
				}
			}
			else
			{
				if(!isset($workers_by_server[$value->_ip][$value->_functions[0]]))
				{
					$workers_by_server[$value->_ip][$value->_functions[0]] = 1;
				}
				else
				{
					$workers_by_server[$value->_ip][$value->_functions[0]]++;
				}
			}
		}
		//print_r($workers_by_server);
		foreach($workers_by_server as $server => $functions)
		{
			//if($server=='54.194.76.201')
			//{
				print_r("-------------------------\n");
				print_r("| SERVER : ".$server."\n");
				foreach($functions as $function_name => $number_of_workers)
				{
					print_r("| function : ".$function_name." - ".$number_of_workers." workers\n");
				}
				print_r("-------------------------\n");
			//}
		}
}
?>