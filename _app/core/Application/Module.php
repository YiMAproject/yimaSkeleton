<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class Module
{
    public function onBootstrap(MvcEvent $e)
	{
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
		
		// DB: Using Global db Adapter for TableGateWay\Featured\GlobalAdapterFeature
		$sl = $e->getApplication()->getServiceManager();
		$adapter = $sl->get('Zend\Db\Adapter\Adapter');
		GlobalAdapterFeature::setStaticAdapter($adapter);


        /* --- To Avoid error if intl extension not installed on server. --- */

        /**
         * @TODO: grab default locale from anywhere and set here ..
         */
        // used by ViewHelpers and ... (mvc), exp. $this->translate()
        $translator = $sl->get('MvcTranslator');
        $translator->setLocale('en_US');
	}
	
    public function getServiceConfig() 
    {
    	return array (
    		// DB: Using Global db Adapter on each services Implemented AdapterAwareInterface
    		'initializers' => array (
    			function ($instance, $sm) {
    				if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
    					$instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
    				}
    			}
    		), 
    	);
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array (
                'staticServer'   => 'Application\Service\View\StaticServerHelperFactory',
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
