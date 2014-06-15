<?php
return array(
    // Navigation setup ... {
    'service_manager' => array(
        'factories' => array(
            # container of navigation default pages
            'navigation.default' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' 	 => 'Home',
                'module'	 => 'Application',
                'route'		 => 'home',
            ),
        ),
    ),
    // ... }

	'controllers' => array(
		'invokables' => array(
			'Application\Controller\Index' => 'Application\Controller\IndexController'
		),
	),

    'view_manager' => array(
        /* override by module.global.config.php
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        */
        'template_map' => array(
            #'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
        ),
        'template_path_stack' => array(
           __DIR__ . '/../view',
        ),
    ),

    'router' => array(
        'routes' => array(
        	# application Home Page
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        	// The following is a route to simplify getting started creating
        	// new controllers and actions without needing to create a new
        	// module. Simply drop new controllers in, and you can access them
        	// using the path /application/:controller/:action
        	'application' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/st-pages',
        			'defaults' => array(
        				'__NAMESPACE__' => 'Application\Controller',
        				'controller'    => 'Index',
        				'action'        => 'index',
        			),
        		),
        		'may_terminate' => true,
        		'child_routes' => array(
        			'default' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => '/[:controller[/:action]]',
        					'constraints' => array(
        						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
        						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
        					),
        					'defaults' => array(
        					),
        				),
        			),
        		),
        	),
        ),
    ),
	
	# translation enabled by text-domain defined to use for this module
	'translator' => array(
		'translation_file_patterns' => array(
			array(
				'type'     	  => 'phparray',
				'base_dir' 	  => realpath(__DIR__ . '/../language'),
				'pattern'  	  => '%s.php',
                //application translations using as default Text Domain
				#'text_domain' => 'default',
			),
		),
	),
);
