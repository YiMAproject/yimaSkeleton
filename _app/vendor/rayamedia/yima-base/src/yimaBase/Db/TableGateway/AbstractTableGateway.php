<?php
namespace yimaBase\Db\TableGateway;

use Zend\Db\TableGateway\AbstractTableGateway as ZendTableAbstract;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Adapter\Adapter;
use yimaBase\Db\TableGateway\Provider\PrimaryKeyProviderInterface;
use yimaBase\Db\TableGateway\Exception;

/**
 * Class AbstractTableGateway
 *
 * @package yimaBase\Db\TableGateway
 */
abstract class AbstractTableGateway extends ZendTableAbstract implements
	AdapterAwareInterface,	     # using global adapter when service invoked
	PrimaryKeyProviderInterface	 # to avoid using metadata and reduce performance on some features that need primaryKey
{
	# db table name
    protected $table = '';

    /**
     * Construct
     */
    final public function __construct()
    {
        // inject global db adapter into table from Feature, global adapter set via Application
    	$this->featureSet = new Feature\FeatureSet(
            array(
            new Feature\GlobalAdapterFeature,
    	    )
        );
    
    	// init Table, adding features, .....
    	$this->init();
    	
    	$this->initialize();
    }
    
    /**
     * Init Table 
     * 
     * AddFeatures and .....
     */
     public function init() {}
    
    /**
     * We can call featureSet methods
     *
     * $projectTable   = $serviceLocator->get('Application\SampleTable');
     * $projectTable->apply('setLocale', array('fa_IR'));
     * 
     * @throws Exception\InvalidFeatureException
     */
    public function __call($method, $args)
    {
    	$featureSet = $this->featureSet;
    	if (method_exists($featureSet, $method)) {
    		switch ($method) {
    			case 'preInitialize':	case 'postInitialize':
    			case 'preSelect':		case 'postSelect':
    			case 'preInsert':		case 'postInsert':
    			case 'preUpdate':		case 'postUpdate':
    			case 'preDelete':		case 'postDelete':
    				throw new Exception\InvalidFeatureException(sprintf(
    					'Method %s is internal method and run during initialization.'
    				,$method));
    				break;
    		}
    		
    		$ret = call_user_func_array(array($featureSet, $method), $args);

    		return ($ret instanceof $featureSet) ? $this : $ret;
    	}
    	
    	return parent::__call($method, $args);
    }
    
    // Implemented Features .........................................................................................
        
    # implemented AdapterAwareInterface
    /**
     * @note Ehtemaal daarad be dalil e "Visibility from other objects" dar 
     * table haaii ke feature e globalAdapter raa daarad dochar e moshkel shavim
     *
     * @see yimaBase\Db\TableGateway\Feature\TablePrefixFeature
     * @see \Zend\Db\Adapter\AdapterAwareInterface::setDbAdapter()
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
    
    /* implemented PrimaryKeyProviderInterface */
    public function getPrimaryKey()
    {
    	if (isset($this->primaryKey)) {
    		return $this->primaryKey;
    	}
    	
    	return null;
    }
}
