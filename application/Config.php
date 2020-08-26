<?php

define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_LAYOUT'	   , 'default');

define("DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"] . "");

define('BASE_URL', '/');

define('PUBLIC_URL', '/public/');
define('PUBLIC_CSS', '/public/css/');
define('PUBLIC_JS' , '/public/js/');

define('VIEWS_URL', '/views/');
define('VIEWS_JS' , '/views/layout/default/js/');

define('BASE_ENTITIES', ROOT . 'entities' . DS);
define('BASE_UTILS', ROOT . 'utils' . DS);

define('APP_NAME', '');
define('APP_SLOGAN', '');
define('APP_COMPANY', '');
define('APP_DEVELOPER', '');
define('APP_MAIL', '');
define('SESSION_TIME', 99999999999999999999999999999999);
define('HASH_KEY', '50a2a9c1f4156');