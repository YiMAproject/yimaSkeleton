<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class IndexController
 * @package Application\Controller
 *
 * note: you can put same controller/actions for Application Module
 *       like this that accessible from url:
 *       [basePath]/application/:Controller/:action
 */
class IndexController extends AbstractActionController
{
    public function indexAction()
    {

    }

    /**
     * Test page action
     *
     * accessible from url:
     *  [basePath]/application/index/test
     */
    public function testAction()
    {
        d_e('This is test page action.');
    }
}
