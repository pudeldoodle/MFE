<?php

$client = new GearmanClient();
$client->addServer();

$data = "hello\n";
$id = 'mon_id_unique';

//$response = unserialize($client->doNormal('gm_first',serialize($data)));

$handle = $client->doBackground('gm_first',serialize($data),$id);

//print_r($handle);

$stat = $client->jobStatus($handle);

//$client->runTasks();

/*$done = false;
do
{
   sleep(1);
   $stat = $client->jobStatus($handle);
   $completion = ($stat[2] / $stat[3]) * 100;
   if (!$stat[0]) // the job is known so it is not done
   {
      $done = true;
	  $completion = 100;
   }
   echo "Completion: " . $completion . "%\n";
}
while(!$done);

echo "done!\n";*/

?>