<?php

namespace SpiffyUserTest\Form;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use SpiffyUser\Form\RegisterFormFactory;
use SpiffyUser\ModuleOptions;

class RegisterFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $sm;

    /**
     * @var RegisterFormFactory
     */
    protected $factory;


    public function setUp()
    {
        $this->sm      = new ServiceManager();
        $this->factory = new RegisterFormFactory();

        $this->sm->setService('SpiffyUser\ModuleOptions', new ModuleOptions());
    }

    /**
     * @covers \SpiffyUser\Form\RegisterFormFactory::createService
     */
    public function testStringHydrator()
    {
        $this->sm->get('SpiffyUser\ModuleOptions')->setRegisterHydrator('Zend\Stdlib\Hydrator\ClassMethods');

        $form = $this->factory->createService($this->sm);
        $this->assertInstanceOf('SpiffyUser\Form\RegisterForm', $form);
    }

    /**
     * @covers \SpiffyUser\Form\RegisterFormFactory::createService
     */
    public function testSmHydrator()
    {
        $this->sm->setService('FooBar', new ClassMethods());
        $this->sm->get('SpiffyUser\ModuleOptions')->setRegisterHydrator('FooBar');

        $form = $this->factory->createService($this->sm);
        $this->assertInstanceOf('SpiffyUser\Form\RegisterForm', $form);
    }
}