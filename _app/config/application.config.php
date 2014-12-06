<?php
return array(
    'service_manager_config' => [
        'initializers' => [
            'yimaBase\Mvc\Application\DefaultServiceInitializer'
        ],
    ],

    /*
     * Listeners config is a service that fetch from serviceManager
     * and attach to EventManager in Application::initialize()
     */
    'listeners' => [
        
    ],

    'application_config' => [
        # modules name that used in application must list here
        'modules' => [
            'AssetManager',
            'Application',
        ],

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
        'module_listener_options' => [
            'module_paths' => [
                APP_DIR_CORE,
                APP_DIR_MODULES,
            ],
            'config_glob_paths'    => [
                # in file haa bar asaase tartib e gharaar giri include mishavand
                APP_DIR_CONFIG .DS. 'modules.{,local.}config.php',
                APP_DIR_CONFIG .DS. '{,*.}{global,local}.php',
                APP_DIR_CONFIG .DS. '{,*.}{,*.}{global.,local.}config.php',
                APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. 'modules.override.{,local.}config.php',
                APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. '{,*.}{global,local}.php',
                APP_DIR_CONFIG .DS. 'profiles' .DS. APP_PROFILE .DS. '{,*.}{,*.}{global.,local.}config.php',
            ],
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
        ],
    ],
);
