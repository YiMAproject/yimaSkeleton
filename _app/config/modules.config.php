<?php
/**
 * Global Configuration Override
 *
 * This file has been born to application from application config file (application.global.config)
 * "module_listener_options" see application config for more...
 *
 * Used in default moduleManager / ConfigListener during loadModules
 *
 * during Creating "Application" Service values here are accessible
 * through "Config" service by a service called Zend\Mvc\Service\ConfigFactory
 * by default ServiceListener
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'view_manager' => array(
        'layout'           => 'default', 		// default value is 'layouy/layout' by ViewManager

        // Set Exceptions Specific LayoutScript
        # used with ExceptionMvcStrategyListener
        'layout_exception' => [
            'yimaBase\Mvc\Exception\RouteNotFoundException' => 'spec/404',
        ],

        /* @TODO DEPRECATED */
		'display_not_found_reason' 	=> true, 			// default is *false* by viewManager
		'not_found_template' 		=> 'spec/404', 		// default is '404' by viewManager
        'display_exceptions' 		=> true, 			// default is *false* by viewManager
        'exception_template' 		=> 'spec/error', 	// default is 'error' by viewManager


        'template_map'			    => array(), 		// used as construct parameter for ViewResolver\TemplateMapResolver($map)
        'template_path_stack'		=> array(			// used as ->addPaths() method on ViewResolver\TemplatePathStack()
            APP_DIR_APPLICATION .DS. 'themes',
        ),


		//'doctype'                 => '',              // if set used by doctype helper in ViewHelperManagerFactory service
		//'base_path'			    => ''				// if set used by basePath helper in ViewHelperManagerFactory service elsewhere get from request
	),

    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'aliases' => array(
            'db.adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),

    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                # Assetic for default template files
                APP_DIR_APPLICATION.DS.'themes'.DS.'www',
            ),
        ),
    ),

    'db' => array(
        'driver'         => 'Pdo',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
);