<?php

declare(strict_types=1);

namespace App\Tests;

use ReflectionClass;
use ReflectionProperty;

trait TestHelper
{
    public static function getPropertyValue(mixed $object, string $property)
    {
        /** @var ReflectionProperty $reflectionProperty */
        $reflectionProperty = (new ReflectionClass($object))->getProperty($property);

        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
