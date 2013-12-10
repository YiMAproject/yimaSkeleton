<?php
namespace Application\Controller\Admin;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function dashboardAction()
    {
    	$form = new \Application\Form\Test();
    	$form->prepareElements();
    	
    	if ($this->getRequest()->isPost()) {
    		$data = $this->params()->fromPost();
    		$form->setData($data);
    		 
    		// Validate the form
    		if ($form->isValid()) {
    			$validatedData = $form->getData();
    		} else {
    			$messages = $form->getMessages();
    		}    		
    	}
    	
    	return array(
    		'form' => $form, 		
    	);
    }
}
