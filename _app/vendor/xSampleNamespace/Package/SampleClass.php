<?php
namespace xSampleNamespace\Package;

/**
 * @category   xSampleNamespace
 * @package    Package
 */
class SampleClass
{	
    public function __construct()
    {
    	new SubPackage\SampleClass();
    	
    	echo '__'.__NAMESPACE__.'__'.' '.__CLASS__.' constructed !! <br/>';       
   	}
}
