<?php
namespace Application\Service\View;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Application\View\Helper\StaticServer;

class StaticServerHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();

        $conf     = $serviceManager->get('Config');
        $conf     = ( isset($conf['static_server']) && is_array($conf['static_server']) )
                  ? $conf['static_server']
                  : array();

        $stServer = new StaticServer($conf);

        return $stServer;
    }
}
