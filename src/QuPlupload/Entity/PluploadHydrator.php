<?php
/**
 * @Author: Cel Ticó Petit
 * @Contact: cel@cenics.net
 * @Company: Cencis s.c.p.
 */

namespace QuPlupload\Entity;

use Zend\Stdlib\Hydrator\ClassMethods;

class PluploadHydrator extends ClassMethods
{

    /**
     * @param object $object
     * @return array
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if (!$object instanceof PluploadEntity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Plupload\Entity\PluploadEntity');
        }
        $data = parent::extract($object);
        $data = $this->mapField('id_plupload', 'id', $data);
        return $data;
    }

    /**
     * @param array $data
     * @param object $object
     * @return object
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof PluploadEntity) {
            throw new Exception\InvalidArgumentException('$object must be an instance of Plupload\Entity\PluploadEntity');
        }
        $data = $this->mapField('id', 'id_plupload', $data);
        return parent::hydrate($data, $object);
    }

    protected function mapField($keyFrom, $keyTo, array $array)
    {
        $array[$keyTo] = $array[$keyFrom];
        unset($array[$keyFrom]);
        return $array;
    }
}