<?php
/**
 * This file separated that 3rd party application can instance
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

// Setup autoLoading
if (file_exists(APP_DIR_LIBRARIES .DS. 'autoload.php')) {
    require_once APP_DIR_LIBRARIES .DS. 'autoload.php';
}

require_once APP_DIR_LIBRARIES .DS. 'autoload_yima.php';

// Get profile name and define as global const {
$availableProfiles = include APP_DIR_CONFIG .DS. 'application.profiles.php';

$profile = null;
foreach ($availableProfiles as $prof => $callable) {
    $executeProfCallable = function ($callable) {
        switch ($callable) {
            case ($callable instanceof \Closure):
                return $callable();
                break;
            default: throw new \Exception('Invalid Profile Callable.');
        }
    };

    if ($executeProfCallable($callable)) {
        // run profiles callable
        $profile = $prof;
        break;
    }
}

/*$router = \Zend\Mvc\Router\Http\TreeRouteStack::factory(
    array(
        'routes' => array(
            'username_profile' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/username',
                    'defaults' => array(
                        'profile'   => 'username',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    )
);
$routeMatch = $router->match(new \Zend\Http\PhpEnvironment\Request());
if ($routeMatch && $routeMatch->getParam('profile')) {
    $profile = $routeMatch->getParam('profile');
    var_dump($router->assemble(array(), array('name' => $routeMatch->getMatchedRouteName())));
    $_SERVER['REQUEST_URI'] = '/yima/';
}

$profileAlias = null;
if (! isset($availableProfiles[$profile]) ) {
    $profileAlias = $profile;
    while (isset($availableProfiles['aliases'][$profileAlias])) {
        $profileAlias = $availableProfiles['aliases'][$profile];
    }
}*/

if (!$profile) throw new \Exception('No Profile Matched.');

define('APP_PROFILE', $profile);
// ... }