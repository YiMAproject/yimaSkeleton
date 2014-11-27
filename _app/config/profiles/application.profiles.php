<?php
use Poirot\Core;
use yimaBase\Mvc\Application;

/**
 * Use to known profiles by application an determine canonical names
 * when you are on localhost or any other profile name, application
 * use this names to load related profile config and run application
 * with this config.
 */
return array(
    'profiles' => [
        'console' => [
            'check' => function ()
                {
                    return (PHP_SAPI == 'cli');
                },
            /*
             It's alias of default, so exec not defined application use alias exec
             'exec'  => function() {

                },
            */
        ],
        'default' => [
            'check' => function ()
                {
                    return true;
                },
            'exec'  => function() {
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
                },
        ],
    ],

    'aliases' => [
        'console' => 'default',
    ],

);


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