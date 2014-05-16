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
	}
	
    public function getServiceConfig() 
    {
    	return array (
    		// DB: Using Global db Adapter on each services Implemented AdapterAwareInterface
    		'initializers' => array (
    			function ($instance, $sm) {
    				if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
    					$instance->setDbAdapter(
                            $sm->get('Zend\Db\Adapter\Adapter')
                        );
    				}
    			}
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
