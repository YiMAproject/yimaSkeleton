<?php
namespace
{
    use Poirot\Core;
    use yimaBase\Mvc\Application;

    (!defined('PHP_VERSION_ID') or PHP_VERSION_ID < 50306 ) and
    exit('Needs at least PHP5.3 but you have ' . phpversion() . '.');

    /**
     * This makes our life easier when dealing with paths. Everything is relative
     * to the application root now.
     */
    chdir(__DIR__);

    /**
     * Application Consistencies and AutoLoad
     *
     */
    require 'index.consist.php';

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
            $defaultConf = Core\array_merge($defaultConf, $hostConf);
        }

        // bootstrap profile
        $profBootstrap = APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. 'bootstrap.php';
        (!file_exists($profBootstrap)) ?: include $profBootstrap;

        // run application
        $APP = Application::init($defaultConf);
        $APP->run();
    }
    catch (Exception $e) {
        try {
            $APP->getEventManager()
                ->trigger(
                    Application::EVENT_DISPATCH_ERROR
                    , $APP->getMvcEvent()
                );
        } catch (Exception $e) {
            ob_start();
            include 'error'.DS.'general.phtml';
            ob_end_flush();
        }
    }
}
