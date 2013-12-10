<?php
namespace Raya\Appwork\Db\TableGateway;

use Raya\Appwork\Db\TableGateway\Feature\TablePrefixFeature;
use Raya\Appwork\Db\TableGateway\Exception;
use Zend\Db\TableGateway\AbstractTableGateway as ZendTableAbstract;
use Zend\Db\TableGateway\Feature;

use Zend\Db\Adapter\Adapter;
use Raya\Appwork\Db\TableGateway\Provider\PrimaryKeyProviderInterface;

class AbstractTableGateway extends ZendTableAbstract implements 
	\Zend\Db\Adapter\AdapterAwareInterface,	 # using global adapter when service invoked
	PrimaryKeyProviderInterface				 # to avoid using metadata and reduce performance on some features that need primaryKey
{
	# db table name
    protected $table = '';
    
    protected $canonicalNamesReplacements = array(' ','.','-');
    protected $canonicalPrefix;
	
    final public function __construct()
    {
    	$this->featureSet = new Feature\FeatureSet(array(
            # using global db adapter for table gateway Feature, global adapter set via Application
            new Feature\GlobalAdapterFeature,
    	));
    
    	// init Table, adding features, .....
    	$this->init();
    	
    	$this->initialize();
    	
    	$prefix = $this->getPrefix();
    	$table = $this->table;
    	$this->table = $prefix.$table;
    }
    
    /**
     * Init Table 
     * 
     * AddFeatures and .....
     */
    public function init() { }
    
    /**
     * Be in soorat mitavaanim feature haaie har table ro dar dastras daashte baashim
     * va az tarighe barnaame va na faghat dar dastresi va az daroon e class e table
     * tanzim konim.
     * 
     * $projectTable   = $serviceLocator->get('Application\SampleTable');
     * $projectTable->apply('setLocale', array('fa_IR'));
     * 
     * @throws Exception\InvalidFeatureException
     */
    public function __call($method,$args)
    {
    	$featureSet = $this->featureSet;
    	if (method_exists($featureSet,$method)) {
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
    		
    		$ret = call_user_func_array(array($featureSet,$method),$args);
    		return ($ret instanceof $featureSet) ? $this : $ret;
    	}
    	
    	return parent::__call($method, $args);
    }
    
    // _-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-__-_
    
    public function getPrefix()
    {
    	if (isset($this->canonicalPrefix)) {
    		return $this->canonicalPrefix;
    	}
    
    	$defaultConf = \Raya\Appwork\Config\Config::getAppConfFromFile();
    
    	$prefix = (isset($defaultConf['db']['prefix'])) ? $defaultConf['db']['prefix'] : '';
    	$prefix = $this->canonicalizePrefix($prefix);
    
    	return $this->canonicalPrefix;
    }
    
    protected function canonicalizePrefix($name)
    {
    	// this is just for performance instead of using str_replace
    	return $this->canonicalPrefix = strtolower(strtr($name, $this->canonicalNamesReplacements));
    }
    
    // Implemanted Features ......................................................................................... 
        
    # implemented AdapterAwareInterface
    /**
     * @note Ehtemaal daarad be dalil e "Visibility from other objects" dar 
     * table haaii ke feature e globalAdapter raa daarad dochar e moshkel shavim
     * @see Raya\Appwork\Db\TableGateway\Feature\TablePrefixFeature
     *  
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