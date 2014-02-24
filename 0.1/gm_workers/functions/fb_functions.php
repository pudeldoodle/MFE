<?php

$params = array(
	'appId'=>_MFE_FACEBOOK_APP_ID,
	'secret'=>_MFE_FACEBOOK_APP_SECRET
);
$facebook = new Facebook($params);

$core->_set_app_token();
$app_token = $core->get_app_access_token();

function debug_token($access_token,$app_token,$facebook)
{
	
	$debug_token = $facebook->api(
		'/debug_token',
		'GET',
		array(
			'input_token' => $access_token,
			'access_token' => $app_token
		)
	);
	return $debug_token;
}

function count_perms($perms,$debug_token,$fb_id,$access_token,$app_token,$facebook)
{
	$perms_count = count($perms);    /*   \\(^_^)//   */ 

	foreach ($debug_token['data']['scopes'] as $key => $scope)
	{
		$perms_key = array_search($scope, $perms);

		if ($perms_key!==FALSE)
		{
			unset($perms[$perms_key]);
			$perms_count--;
		}
	}
	if ($debug_token['data']['is_valid'] && $perms_count == 0)
	{
		$facebook->setAccessToken($access_token);
		$facebook->setExtendedAccessToken();
		$access_token = $facebook->getAccessToken();
		$debug_token = debug_token($access_token,$app_token,$facebook); //debug received token
		
		//print_r($debug_token['data']['user_id']);
		
		if ($debug_token['data']['is_valid'] && $debug_token['data']['user_id'] == $fb_id)
		{
			return true;
		}
	}
	else if(!$debug_token['data']['is_valid'])
	{
		return 'token is invalid';
	}
	else if($perms_count>0)
	{
		return 'missing permissions';
	}
	
}
?>