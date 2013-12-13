<?php
// Define Consts
require 'indefine.php';

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
	// try to reach host specific config
    $defaultConf = include  APP_DIR_CONFIG .DS. 'application.global.config.php';
    $hostConFile = APP_DIR_CONFIG .DS. 'domains' .DS. APP_HOST .DS. 'application.override.config.php';
    if (file_exists($hostConFile)) {
        $hostConf = include $hostConFile;
        $defaultConf = array_merge($defaultConf, $hostConf);
    }

	// run application
	Zend\Mvc\Application::init($defaultConf)->run();
}
catch (Exception $e) {
	echo '<pre>';
	echo '<h2>It`s seems be an error</h2>';
	echo '<p style="color:red;font-weight:bold;">'.$e->getMessage().'</p>';

	throw $e;
}
