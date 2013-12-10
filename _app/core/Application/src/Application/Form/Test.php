<?php
namespace Application\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;

class Test extends Form
{
    public function prepareElements()
    {
    	$this->add(array(
    		'type' => 'cForm\Element\TextareaCharCount',
    		'name' => 'textcount',
    		'options' => array(
    			'label'   => 'Text Count',
    			'allowed' => 200,
    			//'warning' => 20,
    			//'counter_text' => 'Character left:',  
    		),
    		'attributes' => array(
    			'id' => 'textcountid', //id is required if not set, will generated inside helper 
    		),
    	));
    	
    	$this->add(array(
    		'type' => 'Zend\Form\Element\Text',
    		'name' => 'text',
    		'options' => array(
    			'label' => 'Text',
    		),
    	));
    	
    	$this->add(array(
    		'type' => 'Zend\Form\Element\Password',
    		'name' => 'password',
    		'options' => array(
    			'label' => 'Password',
    		),
    	));
    	
    	$this->add(array(
    		'type' => 'Zend\Form\Element\Textarea',
    		'name' => 'textarea',
    		'options' => array(
    			'label' => 'Description',
    		),
    		'attributes' => array(
    			//'cols' => 25,
    			'rows' => 4,
    			'maxlength' => 255, 
    		),
    	));
    	
        $this->add(array(
        	'type' => 'Zend\Form\Element\File',
        	'name' => 'file',
        	'options' => array(
        		'label' => 'File input',
        		'value' => 'We Cant put value from here',
        	),
        ));
        
        /* $this->add(array(
        	'type' => 'Zend\Form\Element\Checkbox',
        	'name' => 'checkbox',
        	'options' => array(
        		'label' => 'Checkbox',
        		'use_hidden_element' => false,
        		'unchecked_value' => 'off',
        		'checked_value'   => 'on',
        	),
        	'attributes' => array(
        		'checked'  => 'checked',
        	),
        )); */
        
        $this->add(array(
        	'type' => 'Zend\Form\Element\Select',
        	'name' => 'select',
        	'options' => array(
        		'label' => 'Select',
        		'options' => array(
        			'value' => 'label',
        			array(
        				'value' => 'Value2',
        				'label' => 'Label2',
        				'selected' => 'selected',
        				//'disabled' => 'disabled',
        			),
        		),
        	),
        	'attributes' => array(
        		'multiple' => 'multiple',
        		'size'     => 4, 
        	),
        ));
        
        $this->add(array(
        	'type' => 'Zend\Form\Element\Radio',
        	'name' => 'radio',
        	'options' => array(
        		'label' => 'Radio Box',
        		'options' => array(
        			'value' => 'label',
        			array(
        				'value' => 'Value2',
        				'label' => 'Label2',
        				'selected' => 'selected',
        				//'disabled' => 'disabled',
        			),
        		),
        	),
        	'attributes' => array(
        	),
        ));
        
        $this->add(array(
        	'type' => 'Zend\Form\Element\MultiCheckbox',
        	'name' => 'multicheckbox',
        	'options' => array(
        		'label' => 'This is MultiCheckbox',
        		'use_hidden_element' => false,
        		'options' => array(
        			'value' => 'label',
        			array(
        				'value' => 'Value2',
        				'label' => 'Label2',
        				//'selected' => 'selected',
        				//'disabled' => 'disabled',
        			), 
        		),
        	),
        	'attributes' => array(
        	),
        ));
        
        // Security ........................................................................................
        $this->add(array(
        	'type'    => 'Zend\Form\Element\Captcha',
        	'name' 	  => 'captcha',
        	'options' => array(
        		'label'   => 'Prove your humanity!!',
        		'captcha' => array( 
        			'class'   => 'dumb', // dumb, figlet, image, recaptcha 
        			'options' => array(
        				'wordlen' => 7,
        				'UseNumbers' => false, 
        			),
        		),
        	),
        ));
        // Set the enviroment variable for GD
        putenv('GDFONTPATH=' . realpath(APP_DIR_CORE .DS. 'cForm' .DS. 'data')); // to loading fonts with GD
        $this->add(array(
        	'type'    => 'Zend\Form\Element\Captcha',
        	'name' 	  => 'captcha',
        	'options' => array(
        		'label'   => 'Prove your humanity!!',
        		'captcha' => array(
        			'class'   => 'image', // dumb, figlet, image, recaptcha
        			'options' => array(
        				'font' => 'Roboto-Bold-webfont',
        				//'DotNoiseLevel'  => , 
        				//'LineNoiseLevel' => ,
        				//'GcFreq' => , //Set garbage collection frequency
        				//'FontSize' => ,
        				//'Height' => ,
        				//'Width' =>,
        				'ImgDir' => APP_DIR_APPLICATION .DS. 'template' .DS. 'www' .DS. 'tmp',//Set captcha image storage directory
        				'ImgUrl' => '/tmp/', //Set captcha image base URL
        				//'ImgAlt' => ,
        				//'Suffix' => , //Set captcha image filename suffix
        			),
        		),
        	),
        ));
        $this->add(array(
        	'type' => 'Zend\Form\Element\Hidden',
        	'name' => 'hidden',
        	'options' => array(
        		'value' => 'Hidden Element Value',
        	),
        ));
        $this->add(new Element\Csrf('security'));
        
        // Buttons ............................................................................................
        $this->add(array(
        	'name'  => 'send',
        	'attributes' => array(
        		'type'  => 'submit',
        		'value' => 'Submit',
        	),
        ));
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type'  => 'reset', // also can put 'submit'
                'value' => 'Reset Title',
            ),
        ));
        $this->add(array(
        	'type'  => 'Zend\Form\Element\Button',
        	'name'  => 'send',
        	'options' => array(
                'label' => 'Button Title',
           	 ),
        ));
        
        // validation only done on this items
        //$this->setValidationGroup('name', 'email', 'subject', 'message');
        // to reset
        //$this->setValidationGroup(\Zend\Form\FormInterface::VALIDATE_ALL);
    }
}
