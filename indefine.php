<?php
/**
 * This file separated that 3rd party application can insance
 * system folder structure by require-ing- this file
 */

// Define Consts
define('REQUEST_MICROTIME', microtime(true));
define('DS',DIRECTORY_SEPARATOR);

define('APP_DIR_ROOT'       , __DIR__ );
define('APP_DIR_APPLICATION', APP_DIR_ROOT .DS. '_app');
define('APP_DIR_LIBRARIES',   	APP_DIR_APPLICATION .DS. 'vendor');
define('APP_DIR_MODULES', 		APP_DIR_APPLICATION .DS. 'vendor');
define('APP_DIR_CONFIG', 		APP_DIR_APPLICATION .DS. 'config');
define('APP_DIR_CORE', 			APP_DIR_APPLICATION .DS. 'core');
define('APP_DIR_TEMP', 			APP_DIR_APPLICATION .DS. 'tmp');
define('APP_DIR_CACHE', 			APP_DIR_TEMP .DS. 'cache');

// Get host name and define as global const {
$config = include APP_DIR_CONFIG .DS. 'application.domains.config.php';

$hostName = (PHP_SAPI != 'cli') ? $_SERVER['SERVER_NAME'] : 'localhost';
$hostName = (substr($hostName, 0, 3) == 'www') ? substr($hostName, 4) : $hostName;
if (! isset($config['domains'][$hostName]) ) {
    while (isset($config['domains']['canonical_domains'][$hostName])) {
        $hostName = $config['domains']['canonical_domains'][$hostName];
    }
}
define('APP_HOST',$hostName);
// ... }