<?php
/**
 * What this file actually does ?!!
 *
 * This is very first level configuration file that
 * given to app. to start the engine.
 *
 * Desc :
 * X1) This file present as a "ApplicationConfig" service on serviceManager
 *    serviceManager->get('ApplicationConfig');
 *
 */
return array(
    # modules name that used in application must list here
    'modules' => array(
        'AssetManager',
        'Application',
    ),

    /* X1: every key present here can fetch later */
	'db' => array(
		'prefix' => '',
	),

    # ----------------------------------------------------

    /**
     * This way you can register some service (factory, invokable, ..)
     * to serviceManager.
     *
     * also change default behavior of program for ModuleManager, EventManager
     * by registering new one instead serviceManager defaults.
     *
     * @see \Zend\Mvc\Service\ServiceManagerConfig
     */
    'service_manager' => array(
        'invokables' => array(
            // Set Some Default Listeners
            'ExceptionMvcStrategyListener' => 'yimaBase\Mvc\View\Listener\ExceptionMvcStrategyListener',
            'SendExceptionListener'        => 'yimaBase\Mvc\Listener\SendExceptionListener',
        ),
        'factories'  => array (
            // just for study case reason ...

            // you can replace application startup or default ServiceManagerConfig with your own services
            'ModuleManager' => 'Zend\Mvc\Service\ModuleManagerFactory', // default

            /* used by default Module Manager
             *
             * > by default containing all services for serviceManager and
             *   using during running app. Controllers, view, and more more ...
             *
             * > serviceListener listen for Module.php and load some config file
             *   by execute related method and get some config.
             *   you can register some by "service_listener_options" key
             *
             * > attached to eventManager by default
             *   setDefaultConfig catched from within modules
             *   for services that present to listener
             */
            'ServiceListener' => 'Zend\Mvc\Service\ServiceListenerFactory', // default
        ),
    ),
    # ----------------------------------------------------

    /*
     * Listeners config is a service that fetch from serviceManager
     * and attach to EventManager in Application::init()
     */
    'listeners' => array(
        'ExceptionMvcStrategyListener',
        'SendExceptionListener',
    ),

    // ---------------------
    // DON`T EDIT LINES BELOW
    // if you want some changes override configs by domain spec file
    // ===========================

    /**
     * Used by default Module Manager,
     *
     * DefaultListenerAggregate will use it.
     * DefaultListenerAggregate setup eventManager for LoadingModules
     */
    'module_listener_options' => array(
		'module_paths' => array(
			APP_DIR_CORE,
			APP_DIR_MODULES,
		),
        'config_glob_paths'    => array(
            # in file haa bar asaase tartib e gharaar giri include mishavand
            APP_DIR_CONFIG .DS. 'modules.{,local.}config.php',
            APP_DIR_CONFIG .DS. '{,*.}{global,local}.php',
            APP_DIR_CONFIG .DS. '{,*.}{,*.}{global.,local.}config.php',
            APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. 'modules.override.{,local.}config.php',
            APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. '{,*.}{global,local}.php',
            APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. '{,*.}{,*.}{global.,local.}config.php',
        ),
		# caching modules merged config
		'config_cache_enabled'  => false,
		'cache_dir'    		   	=> APP_DIR_CACHE,
		'config_cache_key' 	    => 'modulesMergedConfig_'.APP_PROFILE, // name of cache file
		
		# also these options can beign set
		/* 
		'config_static_paths'  => array(
			
		),
		'extra_config'         => array(
		),
		*/
	),
);
