<?php

print_r('client!');

$client = new GearmanClient();
$client->addServer();

$data = 'hello';

$response = unserialize($client->doNormal('gm_first',serialize($data)));

print_r($response);

?>