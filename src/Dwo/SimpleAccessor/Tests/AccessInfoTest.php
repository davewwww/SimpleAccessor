<?php

namespace Dwo\SimpleAccessor\Tests;

use Dwo\SimpleAccessor\AccessInfo;
use Dwo\SimpleAccessor\Tests\Fixtures\User;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class AccessInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReadAccessInfoMethod()
    {
        $user = new User('foo');

        $access = AccessInfo::getReadAccessInfo(new \ReflectionClass($user), 'name');

        self::assertEquals(true, $access['has_property']);
        self::assertEquals('method', $access['type']);
        self::assertEquals('getName', $access['name']);

        self::assertEquals('foo', AccessInfo::readProperty($user, 'name', $access));
    }

    public function testGetReadAccessInfoProperty()
    {
        $user = new User('foo');
        $user->age = 23;

        $access = AccessInfo::getReadAccessInfo(new \ReflectionClass($user), 'age');

        self::assertEquals(true, $access['has_property']);
        self::assertEquals('property', $access['type']);
        self::assertEquals('age', $access['name']);

        self::assertEquals(23, AccessInfo::readProperty($user, 'age', $access));
    }
}