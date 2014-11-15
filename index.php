<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(__DIR__);

// Define Const and autoLoading
require 'indefine.php';

// startup preparation
$appBootstrap = APP_DIR_CONFIG .DS. 'profiles' .DS. 'bootstrap.php';
(!file_exists($appBootstrap)) ?: include $appBootstrap;

// Run the application!
try {
	// try to reach profile specific config
    $defaultConf = include  APP_DIR_CONFIG .DS. 'application.config.php';

    $domainConfFiles = APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. 'application.override.{,local.}config.php';
    foreach (glob($domainConfFiles, GLOB_BRACE) as $file) {
        // merge with default config
        ob_start();
        $hostConf = include $file;
        ob_get_clean();
        $defaultConf = \Zend\Stdlib\ArrayUtils::merge($defaultConf, $hostConf);
    }

    // bootstrap profile
    $profBootstrap = APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. 'bootstrap.php';
    (!file_exists($profBootstrap)) ?: include $profBootstrap;


	// run application
	Zend\Mvc\Application::init($defaultConf)->run();
}
catch (Exception $e) {
    ob_start();
    include 'error'.DS.'general.phtml';
    ob_end_flush();
}
