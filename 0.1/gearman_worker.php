<?php

/*print_r('worker!');

define('_IN_MFE', true);

session_start();

require_once('../config.php');
require_once(_CLASSES.'/Core.class.php');
require_once(_CLASSES.'/MySQL.class.php');
require_once(_LIBRARIES.'/facebook/facebook.php');
require_once(_LIBRARIES.'/gearman-admin/GearmanAdmin.php');
require_once('./functions/gm_admin_functions.php');
*/
$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction("gm_first","first");

print_r("\n*** starting worker ".__FILE__." ***\n");
//check_workers_by_server('gm_add_user');

while ($worker->work())
{
	print_r("*** waiting for work ***\n");
	if ($worker->returnCode() != GEARMAN_SUCCESS)
	{
		echo $worker->returnCode() . PHP_EOL;
		break;
	}
	//check_workers_by_server('gm_add_user');
};

//extends the token, reads its content and adds the content and the token in the database
function first($job)
{
	$data = unserialize($job->workload());
	print_r($data);
	return serialize($data);
}
?>