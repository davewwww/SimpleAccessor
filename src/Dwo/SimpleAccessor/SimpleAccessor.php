<?php

namespace Dwo\SimpleAccessor;

/**
 * Class SimpleAccessor
 *
 * @author Dave Www <davewwwo@gmail.com>
 */
class SimpleAccessor
{
    /**
     * @param mixed  $objectOrArray
     * @param string $propertyPath
     * @param bool   $throwIfNotFound
     *
     * @return mixed
     */
    public static function getValueFromPath($objectOrArray, $propertyPath, $throwIfNotFound = false)
    {
        if(null !== $value = self::getValue($objectOrArray, $propertyPath)) {
            return $value;
        }

        $paths = explode('.', $propertyPath);
        $lastPath = array_pop($paths);

        $pathReady = [];
        foreach ($paths as $path) {
            $pathReady[] = $path;

            $objectOrArray = self::getValue($objectOrArray, $path);

            if (null === $objectOrArray || is_scalar($objectOrArray)) {
                if ($throwIfNotFound) {
                    throw new \Exception(sprintf('path not found: %s', implode('.', $pathReady)));
                }

                return null;
            }
        }

        return self::getValue($objectOrArray, $lastPath);
    }

    /**
     * @param mixed  $objectOrArray
     * @param string $property
     *
     * @return mixed
     */
    public static function getValue($objectOrArray, $property)
    {
        $propertyValue = null;

        if (is_object($objectOrArray)) {

            $camelProp = str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
            $methods = array('get'.$camelProp, lcfirst($camelProp), 'is'.$camelProp, 'has'.$camelProp);

            foreach ($methods as $method) {
                if (method_exists($objectOrArray, $method)) {
                    $propertyValue = $objectOrArray->$method();
                    break;
                }
            }

            if (null === $propertyValue && property_exists($objectOrArray, $property)) {
                $propertyValue = $objectOrArray->$property;
            }
        } elseif (is_array($objectOrArray)) {
            if (isset($objectOrArray[$property])) {
                $propertyValue = $objectOrArray[$property];
            }
        }

        return $propertyValue;
    }
}