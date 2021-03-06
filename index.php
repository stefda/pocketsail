<?php

/**********************************************************
 * PHP ERROR REPORTING LEVEL
 */
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

/**********************************************************
 * DEFINE APPLICATION CONSTANTS
 */
define('DOMAIN',  'pocketsail');
define('MAINURL',  'http://' . DOMAIN . '/');
define('CODEBASE',  '');
define('SELF',      'index.php');
define('BASEPATH',  'c:/dev/projects/pocketsail/web/');
define('SYSPATH',   BASEPATH . 'system/');
define('APPPATH',   BASEPATH . 'application/');
define('BASEURL',   'http://' . $_SERVER['HTTP_HOST'] . '/' . (defined('CODEBASE') ? CODEBASE : '/'));
define('TEMP', 'c:/dev/temp/');
define('DEBUG',  TRUE);

require_once SYSPATH . 'codelite/CodeLite.php';