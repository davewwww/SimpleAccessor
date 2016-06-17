<?php

namespace Dwo\SimpleAccessor;

use ReflectionClass;

/**
 * Class AccessInfo
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class AccessInfo
{
    const METHOD = 'method';
    const PROPERTY = 'property';


    /**
     * @see PropertyAccessor::readProperty()
     *
     * @param object $object
     * @param string $property
     * @param array  $access
     *
     * @return mixed
     */
    public static function readProperty($object, $property, array $access)
    {
        if (AccessInfo::METHOD === $access['type']) {
            $value = $object->{$access['name']}();
        } elseif (AccessInfo::PROPERTY === $access['type']) {
            $value = $object->{$access['name']};
        } elseif (!$access['has_property'] && property_exists($object, $property)) {
            $value = $object->$property;
        } else {
            throw new \RuntimeException($access['name']);
        }

        return $value;
    }
    
    /**
     * @see PropertyAccessor::getReadAccessInfo()
     *
     * @param ReflectionClass $reflClass
     * @param string          $property
     *
     * @return array
     */
    public static function getReadAccessInfo(ReflectionClass $reflClass, $property)
    {
        $access = array();
        $access['has_property'] = $reflClass->hasProperty($property);

        //method
        $camelProp = str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
        $methods = array('get'.$camelProp, lcfirst($camelProp), 'is'.$camelProp, 'has'.$camelProp);
        foreach ($methods as $method) {
            if ($reflClass->hasMethod($method) && $reflClass->getMethod($method)->isPublic()) {
                $access['type'] = AccessInfo::METHOD;
                $access['name'] = $method;

                return $access;
            }
        }

        //property
        if ($reflClass->hasMethod('__get') && $reflClass->getMethod('__get')->isPublic()) {
            $access['type'] = AccessInfo::PROPERTY;
            $access['name'] = $property;
            $access['ref'] = false;
        } elseif ($access['has_property'] && $reflClass->getProperty($property)->isPublic()) {
            $access['type'] = AccessInfo::PROPERTY;
            $access['name'] = $property;
            $access['ref'] = true;
        } else {
            $access['type'] = 'not_found';
            $access['name'] = sprintf(
                'Neither the property "%s" nor one of the methods "%s()" '.
                'exist and have public access in class "%s".',
                $property,
                implode('()", "', array_merge($methods, ['__get'])),
                $reflClass->name
            );
        }

        return $access;
    }
}