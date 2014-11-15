<?php
namespace Application;

use Application\Mvc\BootstrapListeners;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
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
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $moduleManager
     * @throws \Exception
     * @return void
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
        // initial current user
        $events = $moduleManager->getEventManager();
        $events->attach(
            ModuleEvent::EVENT_LOAD_MODULES_POST,
            array($this,'onLoadModulesPost'),
            -10
        );

        // init requirements
        if (!getenv('HTTP_MOD_REWRITE'))
            throw new \Exception('It seems that you don\'t have "MOD_REWRITE" enabled on the server.');
    }

    /**
     * Initialize workflow
     *
     * @param ModuleEvent $e
     * @internal param ModuleManagerInterface $moduleManager
     * @return void
     */
    public function onLoadModulesPost(ModuleEvent $e)
    {
        /** @var $moduleManager \Zend\ModuleManager\ModuleManager */
        $moduleManager = $e->getTarget();
        $sm = $moduleManager->getEvent()->getParam('ServiceManager');

        // DB: Using Global db Adapter for TableGateWay\Featured\GlobalAdapterFeature {
        // we want db adapter injected before others
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        GlobalAdapterFeature::setStaticAdapter($adapter);
        // ... }
    }

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
