<?php
namespace Application;

use Application\Mvc\BootstrapListeners;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

/**
 * Class Module
 *
 * @package Application
 */
class Module
{
    /**
     * Listen to the bootstrap event
     *
     * @param MvcEvent $e
     * @return array
     */
    public function onBootstrap(MvcEvent $e)
    {
        // attach default listeners ... {
        $eventManager  = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();

        $bootStrapListeners = new BootstrapListeners();
        $bootStrapListeners->attachShared($sharedManager);
        $bootStrapListeners->attach($eventManager);
        // ... }

        // DB: Using Global db Adapter for TableGateWay\Featured\GlobalAdapterFeature {
        $sl = $e->getApplication()->getServiceManager();
        $adapter = $sl->get('Zend\Db\Adapter\Adapter');
        GlobalAdapterFeature::setStaticAdapter($adapter);
        // ... }
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
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

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
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
