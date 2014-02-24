<?php
define('_IN_MFE', true);

require_once('../config.php');

require_once(_CLASSES.'/Core.class.php');
require_once(_CLASSES.'/MySQL.class.php');

$core = Core::get_instance();
$core->init();

$mysql = MYSQL::get_instance();

//header('Content-Disposition: attachment;filename="result.json"');

// sets the header type to json utf-8
header('Content-Type: application/json; charset=utf-8');

// allows authorized domain for cross-domain requests
// header('Access-Control-Allow-Origin: http://alexandre.opengraphy.com');

$result = array();

// gets the body content, parses it and pastes into an array
$php_input_parsed = array();
$php_input = file_get_contents('php://input');
parse_str($php_input,$php_input_parsed);

//print_r($php_input_parsed);

$php_input_json = json_decode($php_input);
//print_r($php_input_json);

$client = new GearmanClient();
$client->addServer();

if((!$_POST && empty($php_input_parsed)) && !$_GET)
{
	$result['error']['message'] = 'no parameters';
}
else
{
    switch($core->get_action())
    {
		case 'test':
		{
			$result['success']['message'] = 'action test defined';
			if(isset($_GET['access_token']) && isset($_GET['fb_id']))
			{
				$data['access_token'] = $_GET['access_token'];
				$data['fb_id'] = $_GET['fb_id'];
				$response = $client->doNormal('gm_validate_token',serialize($data),$data['fb_id']);
				$result['success']['response'] = $response;
			}
		}break;
    	default:
        {
            $result['error']['message'] = 'no action defined';
        }
    }
}
if(is_array($result['success']))
{
	header('HTTP/1.1 201 Created', true, 201);
}
else
{
	header('HTTP/1.1 401 Bad Request', true, 401);
}

print_r(json_encode($result));

?>