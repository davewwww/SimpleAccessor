<?php

namespace Dwo\SimpleAccessor\Tests;

use Dwo\SimpleAccessor\SimpleAccessor;
use Dwo\SimpleAccessor\Tests\Fixtures\User;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class SimpleAccessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function test($object, $path, $result)
    {
        $error = sprintf('at path "%s" expects: %s', $path, json_encode($result));
        $this->assertEquals($result, SimpleAccessor::getValueFromPath($object, $path), $error);
    }

    public function provider()
    {
        $stdClass = new \stdClass();
        $user = new User('mr. x');

        $obj1 = clone $stdClass;
        $obj1->foo = 'bar';
        $obj1->bar = $ar1 = ['foobar' => 'bar'];
        $obj1->user = $user;

        $obj2 = clone $stdClass;
        $obj2->lorem = $obj1;
        $obj2->ipsum = $ar = [33, $ar1, 'user' => $user];

        $obj3 = clone $stdClass;
        $obj3->sun = $obj2;
        $obj3->moon = ['obj' => $obj2];

        return array(
            //obj 1
            array($obj1, 'foo', 'bar'),
            array($obj1, 'bar', $ar1),
            array($obj1, 'bar.foobar', 'bar'),
            array($obj1, 'foo.foo', null),
            array($obj1, 'user.name', 'mr. x'),
            array($obj1, 'lorem', null),
            //obj 2
            array($obj2, 'lorem.foo', 'bar'),
            array($obj2, 'ipsum', $ar),
            array($obj2, 'ipsum.0', 33),
            array($obj2, 'ipsum.user.name', 'mr. x'),
            array($obj2, 'lorem', $obj1),
            array($obj2, 'foo', null),
            array($obj2, 'foo.foo', null),
            array($obj2, 'bar', null),
            //obj 3
            array($obj3, 'sun.lorem.foo', 'bar'),
            array($obj3, 'sun.lorem', $obj1),
            array($obj3, 'sun.ipsum', $ar),
            array($obj3, 'sun.ipsum.1', $ar1),
            array($obj3, 'sun.ipsum.1.foobar', 'bar'),
            array($obj3, 'sun', $obj2),
            array($obj3, 'moon.obj.ipsum.1.foobar', 'bar'),
        );
    }
}