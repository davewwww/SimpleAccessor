<?php

namespace Dwo\SimpleAccessor\Tests\Fixtures;

/**
 * @author David Wolter <david@lovoo.com>
 */
class User
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}