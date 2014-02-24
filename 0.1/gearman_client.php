<?php

$client = new GearmanClient();
$client->addServer();

$data = "hello\n";

$response = unserialize($client->doNormal('gm_first',serialize($data)));

print_r($response."\n");

?>