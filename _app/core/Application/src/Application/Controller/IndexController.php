<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

    }

    public function pageAction()
    {
    	$serviceLocator = $this->getServiceLocator();
    	$pModel         = $serviceLocator->get('RayPage\Model\Page');

    	$locale         = $serviceLocator->get('locale');

    	//$pModel->insert(array('title'=>'title', 'content'=>'this is content'));

    	die('>_');
    }

}
