<?php
namespace Raya\Appwork\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\TableGateway\Exception;
use Zend\Db\Sql\Expression;

/**
 * In Class baraaie Table haaii ke az Zend\Db\TableGateway\AbstractTableGateway Estefaade mishavad
 *
 * 	@note be dalile inke Abstract feature az Zend AbstractTable extend shode,
 * 			   agar class i dashte baashim ke yek extend  az Zend AbstractTable baashad
 * 			   digar tavasote $this->tableGateway->$protectedProp dastresi nakhaahim daasht
 * 			  "Visibility from other objects"
 * 
 */
class TablePrefixFeature extends AbstractFeature
{
	protected $canonicalNamesReplacements = array(' ','.','-');
	
	protected $canonicalPrefix;
	
	public function postInitialize() 
	{
		$prefix = $this->getPrefix();
		$table = $this->tableGateway->table;
		$this->tableGateway->table = $prefix.$table;
	}
	
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
}
