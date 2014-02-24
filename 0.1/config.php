<?php
if (!defined('_IN_YELAGO')) die();

define('_MYSQL_SERVER', '172.28.9.200');
define('_MYSQL_USER', 'opengraphy');
define('_MYSQL_PASSWORD', 'superopengraphy2014');
//define('_MYSQL_DATABASE', 'yelago');
define('_MYSQL_DATABASE', 'yelago_key');
define('_MYSQL_DEBUG', false);
define('_MYSQL_LOG', false);

define('_ROOT', '/var/www/dev/v1');
define('_LIBRARIES', _ROOT.'/libraries');
define('_CLASSES', _ROOT.'/classes');
define('_TPL', _ROOT.'/tpl');
define('_LOGS', _ROOT.'/logs');

define('_YEL_FACEBOOK_APP_ID','505210162886565');
define('_YEL_FACEBOOK_APP_SECRET','cb9359d6f2caf458b9197411feb99937');

define('_YEL_MAX_WORKERS_BY_SERVER',5);
?>