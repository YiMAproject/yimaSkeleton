<?php
namespace Raya\Appwork\Hydrator;

use Zend\Stdlib\Hydrator\AbstractHydrator;
use Raya\Appwork\Exception;

class CommonEntity extends AbstractHydrator
{
    /**
     * Extract values from an Entity object 
     *
     * @param  object $object
     * @return array
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function extract($object)
    {
        if (!is_object($object)) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object)', __METHOD__
            ));
        }

        return $object->toArray();
    }

    /**
     * Hydrate an Entity object
     *
     * @param  array $data
     * @param  object $object
     * @return object
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function hydrate(array $data, $object)
    {
        if (!is_object($object)) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object)', __METHOD__
            ));
        }

        $object->setProperties($data);
        
        return $object;
    }
}
