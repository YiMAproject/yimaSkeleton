<?php
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

// Setup autoloading
if (file_exists(APP_DIR_LIBRARIES .DS. 'autoload.php')) {
    require_once APP_DIR_LIBRARIES .DS. 'autoload.php';
}

require_once APP_DIR_LIBRARIES .DS. 'autoload_anocomposer.php';

// startup preparation
if (file_exists(APP_DIR_LIBRARIES .DS. 'startup.php')) {
    require_once APP_DIR_LIBRARIES .DS. 'startup.php';
}

// Run the application!
try {
	// Get host name and set as const
	$config = include APP_DIR_CONFIG .DS. 'application.domains.config.php';

	$hostName = $_SERVER['SERVER_NAME'];
	$hostName = (substr($hostName, 0, 3) == 'www') ? substr($hostName, 4) : $hostName;
	if (! isset($config['domains'][$hostName]) ) {
		while (isset($config['domains']['canonical_domains'][$hostName])) {
			$hostName = $config['domains']['canonical_domains'][$hostName];
		}
	}
	define('APP_HOST',$hostName);

	// try to reach host specific config
	$defaultConf = \Raya\Appwork\Config\Config::getAppConfFromFile();

	// run application
	Zend\Mvc\Application::init($defaultConf)->run();
}
catch (Exception $e) {
	echo '<pre>';
	echo '<h2>It`s seems be an error</h2>';
	echo '<p style="color:red;font-weight:bold;">'.$e->getMessage().'</p>';

	throw $e;
}