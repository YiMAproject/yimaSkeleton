<?php
namespace Application\Mvc;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;

/**
 * Class BootstrapListeners
 * @package Application\Mvc
 *
 * Default Application Bootstrap Listeners
 */
class BootstrapListeners implements
    SharedListenerAggregateInterface,
    ListenerAggregateInterface
{
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        // Router for st-pages/controller/actions
        $events->attach(new ModuleRouteListener());
    }

    /**
     * Attach to an event manager
     *
     * @param  SharedEventManagerInterface $events
     * @param  integer $priority
     */
    public function attachShared(SharedEventManagerInterface $sharedManager, $priority = 1000000)
    {
        // disable layout ... {
        $sharedManager->attach(
            'Zend\Mvc\Controller\AbstractController', // because of controller event dispatch, we must use identifier
            MvcEvent::EVENT_DISPATCH,
            array($this, 'disableLayoutOnAjaxRequest'),
            -95
        );

        $sharedManager->attach(
            'Zend\Mvc\Controller\AbstractController',
            MvcEvent::EVENT_DISPATCH_ERROR,
            array($this, 'disableLayoutOnAjaxRequest'),
            -95
        );
        // ... }
    }

    // ------------------------------ SOME CALLBACK IMPLEMENTATION ---------------------------------------

    /**
     * Disable layout on ajax requests
     *
     * @param MvcEvent $e
     */
    public function disableLayoutOnAjaxRequest(MvcEvent $e)
    {
        $result = $e->getResult();
        if (!$result instanceof ModelInterface) {
            return;
        }

        /** @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $e->getRequest();
        if ($request->isXmlHttpRequest()) {
            $result->setTerminal(true);
        }
    }

    // -------------------------------------------------------------------------------------------

    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detachShared(SharedEventManagerInterface $events)
    {

    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        // TODO: Implement detach() method.
    }
}
