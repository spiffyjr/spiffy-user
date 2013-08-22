<?php

namespace SpiffyUserTest\Form;

use ArrayObject;
use SpiffyUser\Form\PasswordStrategy;
use SpiffyUser\Form\RegisterHydrator;
use SpiffyUser\ModuleOptions;
use SpiffyUserTest\Asset\Entity;

class RegisterHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegisterHydrator
     */
    protected $hydrator;

    /**
     * @var PasswordStrategy
     */
    protected $strategy;

    public function setUp()
    {
        $this->strategy = new PasswordStrategy(new ModuleOptions());
        $this->hydrator = new RegisterHydrator($this->strategy);
    }

    /**
     * @covers \SpiffyUser\Form\RegisterHydrator::__construct
     * @covers \SpiffyUser\Form\RegisterHydrator::extract
     */
    public function testExtract()
    {
        $entity = new Entity();
        $this->hydrator->hydrate(array('foo' => 'bar', 'password' => 'baz'), $entity);

        $this->assertEquals('bar', $entity->getFoo());
        $this->assertEquals('$2y$14$Y2hhbmdlX3RoZV9kZWZhdOpaaa2x51qGanveY9TapJ.CFsqSNG.7S', $entity->getPassword());
    }
}