<?php

namespace Dwo\SimpleAccessor;

/**
 * Class SimpleAccessor
 *
 * @author David Wolter <david@lovoo.com>
 */
class SimpleAccessor
{
    /**
     * @param object $objectOrArray
     * @param string $propertyPath
     * @param bool   $throwIfNotFound
     *
     * @return mixed
     */
    public static function getValueFromPath($objectOrArray, $propertyPath, $throwIfNotFound = false)
    {
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
     * @param object|array $objectOrArray
     * @param string       $property
     *
     * @return mixed
     */
    public static function getValue($objectOrArray, $property)
    {
        $propertyValue = null;

        if (is_object($objectOrArray)) {
            if (method_exists($objectOrArray, $method = 'get'.ucfirst($property))) {
                $propertyValue = $objectOrArray->$method();
            } elseif (method_exists($objectOrArray, $method = ucfirst($property))) {
                $propertyValue = $objectOrArray->$method();
            } elseif (property_exists($objectOrArray, $property)) {
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