<?php
namespace xSampleNamespace\Package\SubPackage;

/**
 * @category   xSampleNamespace
 * @package    Package
 * @subpackage SubPackage
 */
class SampleClass
{
	public function __construct()
	{
		echo '__'.__NAMESPACE__.'__'.' '.__CLASS__.' constructed !! <br/>';
	}
}
