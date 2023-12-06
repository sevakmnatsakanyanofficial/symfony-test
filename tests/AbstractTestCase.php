<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function setEntityId(object $entity, int $value)
    {
        $class = new \ReflectionClass($entity);
        $prop = $class->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($entity, $value);
        $prop->setAccessible(false);
    }
}
