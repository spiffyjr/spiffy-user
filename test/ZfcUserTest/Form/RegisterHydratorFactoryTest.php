<?php

namespace SpiffyUserTest\Form;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use SpiffyUser\Form\PasswordStrategy;
use SpiffyUser\Form\RegisterHydratorFactory;
use SpiffyUser\ModuleOptions;

class RegisterHydratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $sm;

    /**
     * @var RegisterHydratorFactory
     */
    protected $factory;


    public function setUp()
    {
        $this->sm      = new ServiceManager();
        $this->factory = new RegisterHydratorFactory();
        $options       = new ModuleOptions();

        $this->sm->setService('SpiffyUser\ModuleOptions', $options);
        $this->sm->setService('SpiffyUser\Form\PasswordStrategy', new PasswordStrategy($options));
    }

    /**
     * @covers \SpiffyUser\Form\RegisterHydratorFactory::createService
     */
    public function testSmHydrator()
    {
        $this->sm->setService('FooBar', new ClassMethods());
        $this->sm->get('SpiffyUser\ModuleOptions')->setRegisterHydrator('FooBar');

        $form = $this->factory->createService($this->sm);
        $this->assertInstanceOf('SpiffyUser\Form\RegisterHydrator', $form);
    }
}