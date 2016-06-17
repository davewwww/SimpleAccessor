<?php

namespace Dwo\SimpleAccessor\Tests\Fixtures;

/**
 * @author Dave Www <davewwwo@gmail.com>
 */
class User
{
    protected $name;
    public $age;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}