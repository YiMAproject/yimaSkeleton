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
