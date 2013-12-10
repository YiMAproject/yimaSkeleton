<?php
/*
 SELECT *
FROM (

SELECT `mt` . * , (

SELECT `dms`.`content`
FROM `Application_dms` AS `dms`
WHERE `dms`.`model` = 'Application\\Model\\TableGateway\\Sample'
AND `dms`.`foreign_key` = `mt`.`sampletable_id`
AND `dms`.`field` = 'note'
) AS `note` , (

SELECT `dms`.`content`
FROM `Application_dms` AS `dms`
WHERE `dms`.`model` = 'Application\\Model\\TableGateway\\Sample'
AND `dms`.`foreign_key` = `mt`.`sampletable_id`
AND `dms`.`field` = 'image'
) AS `image`
FROM `sampletable` AS `mt`
) AS `bt`
WHERE `bt`.`sampletable_id` =1
 */

namespace Raya\Appwork\Db\TableGateway\Feature;

use Raya\Appwork\Db\TableGateway\Dms as DmsTable;
use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\TableGateway\Exception;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Expression;
use Raya\Appwork\Db\TableGateway\Provider\PrimaryKeyProviderInterface;

class DmsFeature extends AbstractFeature
{
	/**
	 * Field haaie dms ke baayad dar jadval e dms zakhire shavand
	 *
	 * @var array | string
	 */
	protected $dmsFields;
	
	/**
	 * Service e table-i- ke dms field haa ro negah midaarad
	 *
	 * @var Raya\Appwork\Db\TableGateway\AbstractTableGateway
	 */
	protected $dmsTable;
	
	/**
	 * @see preInsert
	 *
	 * @var array
	 */
	protected $storedValues;
	
	public function __construct($dmsFields = array() , $dmsTable = null)
	{
		if (! empty($dmsFields) ) {
			$this->setDmsFields($dmsFields);
		}
	
		if ($dmsTable == null) {
			$dmsTable = new DmsTable;
		}
	
		$this->setDmsTable($dmsTable);
	}
	
	/**
	 * Table-i- ke translation haaa raa negah midaarad
	 *
	 * @param string | AbstractTableGateway $tableGateway
	 */
	public function setDmsTable($tableGateway)
	{
		if (is_string($tableGateway) && class_exists($tableGateway)) {
			$tableGateway = new $tableGateway();  
		}
		
		if (!$tableGateway instanceof AbstractTableGateway) {
			throw new Exception\RuntimeException(sprintf(
				'Dms Table must instance of "AbstractTableGateway" but "%s" given.'
			,(is_object($tableGateway)) ? get_class($tableGateway) : gettype($tableGateway)));
		}
		
		/*
		 * If table dont have adapter yet
		 */
		if (! $tableGateway->getAdapter() && $tableGateway instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
			$tableGateway->setAdapter($this->tableGateway->adapter); 
		}
		
		$this->dmsTable = $tableGateway;
		return $this;
	}
	
	public function getDmsTable()
	{
		return $this->dmsTable;
	}
	
	
	/**
	 * Field haaee az in table ke baayad dar jadval e dms baashand
	 *
	 * @return array
	 */
	public function getDmsFields()
	{
		$fields = $this->dmsFields;
	
		if (is_string($fields)) {
			$fields = (array) $fields;
		} elseif ($fields == null) {
			$fields = array();
		}
	
		return $fields;
	}
	
	/**
	 * Field haaee ro ke baayad dms baashand ra be class moa`refi mikonad
	 *
	 * @param string | array $fields
	 */
	public function setDmsFields($fields)
	{
		if (is_string($fields)) {
			$fields = (array) $fields;  
		}
		
		if ($fields instanceof Traversable) {
			$fields = ArrayUtils::iteratorToArray($fields);
		}
		
		if (! is_array($fields) ) {
			throw new Exception\InvalidArgumentException(sprintf(
				'%s expects an array, or object implementing Traversable or Array; received "%s"',
				$method,
				(is_object($fields) ? get_class($fields) : gettype($fields))
			));
		}
		
		$this->dmsFields = $fields;
		return $this;
	}
	
	/**
	 * Add Dms Field(s) to current field(s)
	 *
	 * @param string | array $field
	 */
	public function addDmsField($field)
	{
		$currFields = $this->getDmsFields();
	
		$this->setDmsFields($field);
		$extraField = $this->getDmsFields();
	
		$this->setDmsFields(array_merge($extraField,$currFields));
	
		return $this;
	}
	
	// ............................................................................................................
	
	public function preSelect($select)
	{
		/*
		 SELECT `sampletable` . * , `dms__image`.`content` AS `image`
 		  FROM `sampletable`
		 LEFT JOIN `Application_dms` AS `dms__image`
 		  ON dms__image.foreign_key = `sampletable`.`sampletable_id`
      		 AND dms__image.model = 'Application\\Model\\TableGateway\\Sample'
     		 AND dms__image.field = 'image'
		*/
		
		/* agar aanche az column haaie table select mishavad
		 * jozve dms field haa nabood ehtiaaj nist ke preSelect 
		 * ejraa shavad.										-----------------V
		 */ 
		$rawState  = $select->getRawState();
		$columns   = $rawState['columns'];
		$vColumns  = array_values($columns);
		$dmsFields = $this->getDmsFields();
		
		if (! array_intersect($vColumns, $dmsFields) && !in_array('*',$vColumns) ) {
			return;
		}
		/* -------------------------------------------------------------------- */
		
		$tableGateway = $this->tableGateway;
		$tableName    = $tableGateway->getTable();
		$tableClass   = get_class($tableGateway);
		
		$tablePrimKey = $this->getPrimaryKey($tableGateway); 

		foreach ($this->getDmsFields() as $tf) {
			if ( ($key = array_search($tf,$columns)) !== false ) {
				// we have dms column in select and must remove from SELECT 'dms_column'
				unset($columns[$key]);
			} elseif (!in_array('*',$vColumns)) {
				// we don't need any other dms fields, cause not in SELECT
				continue;
			}
			
			$name = 'Dms__'.$tf;
			// get name of translation table
			$joinTable = $this->getDmsTable()->table;
			
			$expression = new Expression("
				$name.foreign_key = ?.?
				AND $name.model = ?
				AND $name.field = ?
				"
				,array(
					$tableName,$tablePrimKey,
					$tableClass,
					$tf
				)
				,array(
					Expression::TYPE_IDENTIFIER,
					Expression::TYPE_IDENTIFIER
				)
			);
			
			$select->join(
				array($name => $joinTable),//join table name
				$expression //conditions
				,array($tf => 'content'), // this way we dont need postSelect replacement
				'left'
			);
		}
		
		$select->columns($columns);
	}
	
	/**
	 * Tammai e column haaii ke be insert daade shode raa jostejoo karde
	 * va field haaye dms raa joda va baraaie postInsert negah midaarad.
	 * badihi ast ke digar filed haaye dms dar insert mojood nist banaa
	 * bar in dar feature haaye ba'd az in dide nemishavad
	 * exp.
	 * $projectTable->insert(array(
    		'title' 	  => $locale.' Title',						# locale
    		'description' => $locale.' Description', 				# locale
    		
    	(r)	'note'		  => '* this commented note on this post',  # locale
    			
    	(r)	'image' 	  => 'http://image/path/'.$locale.'.jpg',
    	(r)	'url' 	  	  => 'http://google.com',
    	));
    	
    	(r) : removed from columns rawSet
	 */
	public function preInsert($insert)
	{
		// reset on insert values
		$this->setStoredValues();
		
		/* value haaie ersaal shode ro mibinim agar haavie
		 * column dms bood aanhaa ro kenaar migzaarim
		 * va dar postInsert aanhaa raa be table translate ezaafe mikonim
		 */
		$rawData    = $insert->getRawState();
		$columns    = $rawData['columns'];
		$values     = $rawData['values'];
		$dmsColumns = $this->getDmsFields();

		$storedVal = array();// dms column must insert on postInsert
		foreach ($columns as $key=>$cl) {
			if (in_array($cl,$dmsColumns)) {
				$storedVal[$cl] = $values[$key];
				unset($columns[$key]);
				unset($values[$key]);
			}
		}
		
		$insert->values($values,'merge');
		$insert->columns($columns);
		
		$this->setStoredValues($storedVal);
	}
	
	public function postInsert($statement, $result)
	{
		$dmsColumns = $this->getStoredValues();
		if (empty($dmsColumns)) {
			// we dont have any dms columns
			return;
		}
		
		// for Inserting into translation table we need ID of last inserted row
		$lastID = $result->getGeneratedValue();
		$this->addDmsRows($dmsColumns,$lastID);
	}
	
	public function preUpdate($update)
	{
		// reset stored values
		$this->setStoredValues();
	
		// dar ebtedaa field haaii ke marboot be dms haa nistand dar jadval asli update shavad
		$rawState  = $update->getRawState();
		$dataset   = $rawState['set'];
		
		$tblColumns = array_diff_key($dataset, array_flip($this->getDmsFields()));
		
		// store dmsColumns for postUpdate
		$storedData           = array_diff_key($dataset, $tblColumns);
		$storedData['@where'] = clone $update->where;
		
		if (empty($tblColumns)) {
			// reson: Column not found: 1054 Unknown column 'note' in 'field list',exp. *note is dms field
			$tableGateway = $this->tableGateway;
			$tablePrimKey = $this->getPrimaryKey($tableGateway);
			$tblColumns   = array($tablePrimKey=>0);	
				
			// we don't want change anything in base table
			// store where part and change it to nothing happend.
			$update->where(array('1 = ?' => 0));
		}
		
		$update->set($tblColumns);
		$this->setStoredValues($storedData);
	}
	
	public function postUpdate($statement, $result)
	{
		$storedValues = $this->getStoredValues();
	
		$where = $storedValues['@where'];
		unset($storedValues['@where']);
	
		if (empty($storedValues)) {
			// we dont have any dms field
			return;
		}
		
		$tableGateway = $this->tableGateway;
	
		# we dont want use tableGateway baraaie inke feature haa raa niaaz nadaarim
		# be alave inke hamin feature rooie table hatman hast va az select e in estefaade mikonim, kaahesh performance
		$sql       = $tableGateway->getSql();
		$select    = $sql->select()->where($where);
		$statement = $sql->prepareStatementForSqlObject($select);
		$rows      = $statement->execute();
		
		if (! count($rows) > 0) {
			// we don't have any update match
			return;
		}
		
		// get query data
		$primaryKey = $this->getPrimaryKey($tableGateway);
		$model      = get_class($tableGateway);
	
		// @TODO $result Affected ro raa az PDOstatement migirad va nemishavad aan raa ta'ghir daad
	
		$dmsTable = $this->getDmsTable(); $affectedRows = 0;
		foreach ($rows as $row) {
			$foreignKey = $row[$primaryKey];
			foreach ($storedValues as $column => $val) {
				// delete previous dms field post and insert new one
				$dmsTable->delete(array(
					'foreign_key = ?' => $foreignKey,
					'model = ?' 	  => $model,
					'field = ?' 	  => $column,
				));
				
				$dmsTable->insert(array(
					'foreign_key' => $foreignKey,
					'model' 	  => $model,
					'field' 	  => $column,
					'content' 	  => $val,
				));$r = 1;
				
				/* (!) Zamani ke az update estefaade mikonim momken ast ke ghablan in dms vojood nadaashte baashad, 
				 *     va dar natije in field raa nakhaahim daasht.
				 *     
				 * $r = $dmsTable ->update(array('content' => $val),array(
					'foreign_key = ?' => $foreignKey,
					'field = ?' 	  => $column,
					'model = ?' 	  => $model,
				)); */
	
				$affectedRows = ($r) ? $affectedRows+$r : $affectedRows;
			}
		}
	}
	
	public function preDelete($delete)
	{
		$tableGateway = $this->tableGateway;
		$prKey = $this->getPrimaryKey($tableGateway);
	
		// baraaie hazf kadane translation haa yek baar baayad select konim va sepas
		// az rooie ID(primary key) be dast aamade az jadvale translation foreinKey haaye
		// moshaabeh ro hazf konim
		$where = $delete->where;
		# we dont want use tableGateway baraaie inke feature haa raa niaaz nadaarim
		# be alave inke hamin feature rooie table hatman hast va az select e in estefaade mikonim, kaahesh performance
		$sql       = $tableGateway->getSql();
		$select    = $sql->select()->where($where);
		$statement = $sql->prepareStatementForSqlObject($select);
		$rows      = $statement->execute();
	
		if (! count($rows) > 0) {
			return;
		}
			
		// primary key of must deleted item
		$ids  = array();
		foreach ($rows as $row) {
			$ids[] = $row[$prKey];
		}
	
		// delete from translation table
		$modelColumn = get_class($tableGateway);
		// "foreign_key IN(?) AND model = $modelColumn"
		$this->getDmsTable()->delete(array(
				'foreign_key'   => $ids,
				'model = ?'     => $modelColumn
		));
	}
	
	// ............................................................................................................
	
	public function addDmsRows($rows, $foreignID)
	{
		foreach ($rows as $column => $value) {
			$this->addDmsRow($column,$value,$foreignID);
		}
	}
	
	/**
	 * Add Dms data for a row with specific primary key
	 *
	 * @param int   $pk   | primary key of row
	 */
	public function addDmsRow($column, $value, $foreignID)
	{
		$tableGateway = $this->tableGateway;
	
		// write it
		$trData = array(
			'model' 	  => get_class($tableGateway),
			'foreign_key' => $foreignID,
			'field' 	  => $column,
			'content'	  => $value
		);
	
		// insert to dms table
		$dmsTable = $this->getDmsTable();
		$dmsTable->insert($trData);
	}
	
	/**
	 * @see preInsert
	 *
	 * @param array $values
	 */
	protected function setStoredValues($values = array())
	{
		$this->storedValues = $values;
	}
	
	protected function getStoredValues()
	{
		return $this->storedValues;
	}
	
	protected function getPrimaryKey($tableGateway)
	{
		if ($tableGateway instanceof PrimaryKeyProviderInterface) {
			$primaryKey = $tableGateway->getPrimaryKey(); 
		}
		if ($primaryKey) {
			return $primaryKey;
		}

		// try to catch primary key from metada ...................................................................
		
		$metadata = new Metadata($tableGateway->adapter);
		
		// localize variable for brevity
		$t = $tableGateway;
		
		// process primary key
		$pkc = null;
		foreach ($metadata->getConstraints($tableGateway->table) as $constraint) {
			/** @var $constraint \Zend\Db\Metadata\Object\ConstraintObject */
			if ($constraint->getType() == 'PRIMARY KEY') {
				$pkc = $constraint;
				break;
			}
		}
		
		if ($pkc === null) {
			throw new Exception\RuntimeException('A primary key for this column could not be found in the metadata.');
		}
		
		if (count($pkc->getColumns()) == 1) {
			$pkck = $pkc->getColumns();
			$primaryKey = $pkck[0];
		} else {
			$primaryKey = $pkc->getColumns();
		}
		
		return $primaryKey;
	}
	
}
