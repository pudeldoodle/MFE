<?php

print_r('hu!');

var_dump( gearman_version() );

$client = new GearmanClient();
$client->addServer();

?>