<?php

//print_r('worker!');

define('_IN_MFE', true);

session_start();

require_once('../../config.php');
require_once(_CLASSES.'/Core.class.php');
require_once(_CLASSES.'/MySQL.class.php');
require_once(_LIBRARIES.'/facebook/facebook.php');
require_once(_LIBRARIES.'/gearman-admin/GearmanAdmin.php');
require_once('./functions/gm_admin_functions.php');

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction("gm_validate_token","gm_validate_token");

print_r("\n*** starting worker ".__FILE__." ***\n");
check_workers_by_server('gm_validate_token');

while ($worker->work())
{
	print_r("*** waiting for work ***\n");
	if ($worker->returnCode() != GEARMAN_SUCCESS)
	{
		echo $worker->returnCode() . PHP_EOL;
		break;
	}
	check_workers_by_server('gm_validate_token');
};

function gm_validate_token($job)
{
	$time_start = microtime(true);

	$core = Core::get_instance();
	$core->init();
	$mysql = MYSQL::get_instance();
	require_once('./functions/fb_functions.php');
	
	$data = unserialize($job->workload());
	$fb_id = $data['fb_id'];
	$access_token = $data['access_token'];
	
	$debug_token = debug_token($access_token,$app_token,$facebook);
	
	$perms = array('user_events','friends_events');
	$perms_ok = count_perms($perms,$debug_token,$fb_id,$access_token,$app_token,$facebook);
	//print_r($perms_ok);
	
	if($perms_ok === true)
	{
		print_r("on continue\n");
	}
	
}
?>