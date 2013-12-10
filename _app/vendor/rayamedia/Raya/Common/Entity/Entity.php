<?php
namespace  Raya\Common\Entity;

use Zend\Filter\Boolean;

use Countable, Iterator, ArrayAccess;
use Traversable;
use Zend\Stdlib\ArrayUtils;

class Entity implements Countable, Iterator, ArrayAccess
{
	/**
	 * Entity's properties
	 * 
	 * @var array
	 */
	protected $properties = array();
	
	/**
	 * Used as internal cache to avoid proccess repeatation
	 * 
	 * @var int
	 */
	protected $count = 0;
	
	/**
	 * @var int
	 */
	private $iteratorIndex = 0;
	
	/**
	 * Hengaami ke value e yek property = '' | NULL | 0 be jaaie aan
	 * in meghdaar bargasht mishavad
	 * 
	 */
	protected $defaultEmptyValue = NULL;
	
	/**
	 * Agar yek property set nashode bood dar zamaan e dastyaabi (get)
	 * peighaam e khataa namaaiesh dahad
	 * 
	 * @var Boolean
	 */
	protected $throwException = false;
	
	/**
	 * Agar dar haalat e strict mode boodim ejaazeie ezaafe kardan e property 
	 * jadid ro nakhaahim daasht 
	 * 
	 * @var boolean
	 */
	protected $strictMode = false;
	
	/**
	 * Used as internal cache to avoid proccess repeatation
	 * 
	 */
	protected $normalizedKeys = array();
	
	/**
	 * 
	 * @param array|traversable|toArray Object $data
	 */
	public function __construct($data = array())
	{
		if (! empty($this->properties) ) {
			$this->setStrictMode();
		}
		
		$this->setProperties($data);
	}
	
	/**
	 * Entity dar haalat e strict mode nemitavaanad property e digar i daasht e baashad
	 * 
	 * @param string $bool
	 * @return \Raya\Common\Entity\Entity
	 */
	public function setStrictMode($bool = true)
	{
		$this->strictMode = (boolean) $bool;
		return $this;
	}
	
	public function strictMode()
	{
		return $this->strictMode;
	}
	
	
	/**
	 * Set entities (property=>value) pair of this Entity
	 * 
	 * @param array|traversable|toArray Object $data
	 */
	public function setProperties($data)
	{
		$data = $this->extractData($data);
		
		foreach ($data as $prop=>$value) {
			$this->__set($prop, $value);
		}
		
		return $this;
	}
	
	/**
	 * Variable haa mitavaanand yek Setter Method daashte baashand
	 * variable_name ===> setVariableName
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		// tamaami e kelid haa be in soorat zakhire mishavand
		# variableName ==> variable_name
		$key = $this->normalizeKey($key);
		
		// check against strict mode 
		if ($this->strictMode() && !array_key_exists($key, $this->properties)) {
			throw new Exception\StrictModeException(
				'Denied By Strict Mode. "'.$key.'" cant added as new property!'
			);
		}
		
		$setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
		if (method_exists($this, $setter)) {
			// call setter method for property
			switch ($setter) {
				case 'setProperties': break;
				default: $value =  $this->{$setter}($value);
			}
		}
		
		if ($value instanceof Traversable || is_array($value)) {
			// convert traversable values to entity
			$value = new Entity($value);
		}
		
		$this->properties[$key] = $value;
		$this->count = count($this->properties);
	}
	
	public function __get($key) 
	{
		$key = $this->normalizeKey($key);
		
		if (! array_key_exists($key, $this->properties)) {
			// this property not seted, also we use getter just as filter to existance property
			
			if ($this->throwException()) {
				throw new Exception\PropertyNotFoundException(
					'The property "' . $key . '" does not exists'
				);
			}
			
			return ($this->getDefaultEmptyValue());
		}
		
		$return = $this->properties[$key];
		
		$getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
		if (method_exists($this, $getter)) {
			// call getter method for property
			switch ($setter) {
				case 'getProperties': break;
				// filter property value by getter method
				default: $return = $this->{$getter}($return);
			}
		}
		
		return $return;
	}
	
	public function set($key,$value)
	{
		$this->__set($key, $value);
		
		return $this;
	}
	
	/**
	 * Az tarighe in method dar soorati ke meghdaar e value propery empty
	 * bood mitavaan yek meghdaar e pish farz raa bargardaand
	 * (*) empty = false,0,'0',array(),'',null
	 * 
	 * @param string $key
	 * @param string $default
	 * @return Ambigous <multitype:, string>
	 */
	public function get($key, $default = NULL) 
	{
		$value = $this->__get($key);
		if (empty($value) && $value!==$this->getDefaultEmptyValue()) {
			// agar value baraabar ba false,0,'0',array(),'',null bood meghdaar e pishfarz raa bar migardaanad dar soorati ke $default set nashode bood
			$value = ($default !== $this->getDefaultEmptyValue()) ? $default : $this->getDefaultEmptyValue();
		}
		
		return $value;
	}
	
	public function __isset($key)
	{
		$key = $this->normalizeKey($key);
		
		return isset($this->properties[$key]);
	}
	
	public function __unset($key)
	{
		$key = $this->normalizeKey($key);
		
		if ($this->strictMode() && isset($this->properties[$key])) {
			$this->properties[$key] = NULL;
			return;
		}
		
		if (isset($this->{$key})) {
			unset($this->properties[$key]);
			$this->count = count($this->properties);
		}
	}
	
	/**
	 * Hengaami ke meghdaar e yek property khaali ast
	 * in meghdaar e pishfarz bargasht daade mishavad
	 */
	public function setDefaultEmptyValue($val)
	{
		$this->defaultEmptyValue = $val;
		
		return $this;
	}
	
	public function getDefaultEmptyValue()
	{
		return $this->defaultEmptyValue;
	}
	
	/**
	 * Agar yek property set nashode bood dar zamaan e dastyaabi (get)
	 * peighaam e khataa namaaiesh dahad
	 * 
	 * @param string $bool
	 * @return \Application\Model\Entity
	 */
	public function setThrowException($bool = true) 
	{
		$this->throwException = (boolean) $bool;
		
		return $this; 
	}
	public function throwException()
	{
		return $this->throwException;
	}
	
	public function toArray()
	{
		return ArrayUtils::iteratorToArray($this->properties);
	}
	
	/**
	 * normalize CamelCase keys to camel_case
	 *
	 * @param string $key
	 * @return string
	 */
	protected function normalizeKey($key)
	{
		if (isset($this->normalizedKeys[$key])) {
			return $this->normalizedKeys[$key];
		}
	
		$transform = function($letters) {
			$letter = array_shift($letters);
			return '_' . strtolower($letter);
		};
		$normalizedKey = preg_replace_callback('/([A-Z])/', $transform, $key);
		$this->normalizedKeys[$key] = $normalizedKey;
	
		return $normalizedKey;
	}
	
	/**
	 * Data ro az voroodi migirim va be daadeie ghaabele estefaade dar 
	 * class tabdil mikonim
	 * 
	 * @param  $data
	 * @throws Exception\InvalidArgumentException
	 * @return \Traversable | array
	 */
	protected function extractData($data)
	{
		if (is_object($data)) {
			if (method_exists($data, 'toArray')) {
				$data = call_user_func(array($data,'toArray'),$data);
			}
		}
		
		if (! is_array($data) && ! $data instanceof Traversable   ) {
			throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
		}
		
		$return = array();
		foreach ($data as $key => $value) {
			if (is_scalar($value)) {
				$return[$key] = $value;
				continue;
			}
		
			if ($value instanceof Traversable || is_array($value)) {
				$return[$key] = new Entity($value);
				continue;
			}
			
			$return[$key] = $value;
		}
	
		return $return;
	}
	
	// Implemented methods .........................................................................................................
	# implementing Countable
	/**
	 * @see Countable::count()
	 */
	public function count()
	{
		if ($this->count === NULL) {
			$this->count = count($this->properties);
		}
		
		return $this->count;
	}
	
	# implementing Iterator
	public function current () 
	{
		$key = $this->key();
		return $this->__get($key);
	}
	
	public function next () 
	{
		$this->iteratorIndex++;
		next($this->properties);
		
		return $this->current();
	}
	
	public function key () 
	{
		return key($this->properties);
	}
	
	public function valid () 
	{
		return $this->iteratorIndex < $this->count();
	}
	
	public function rewind () 
	{
		$this->iteratorIndex = 0;
		reset($this->properties);
		
		return $this->current();
	}
	
	# implementing ArrayAccess
	public function offsetExists($key)
	{
		return $this->__isset($key);
	}
	
	public function offsetGet($key)
	{
		return $this->__get($key);
	}
	
	public function offsetSet($key, $element)
	{
		$this->__set($key, $element);
	}
	
	public function offsetUnset($key)
	{
		$this->__unset($key);
	}
}
