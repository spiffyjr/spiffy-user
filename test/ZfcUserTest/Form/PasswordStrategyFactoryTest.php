<?php

namespace SpiffyUserTest\Form;

use Zend\ServiceManager\ServiceManager;
use SpiffyUser\Form\PasswordStrategyFactory;
use SpiffyUser\ModuleOptions;

class PasswordStrategyFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \SpiffyUser\Form\PasswordStrategyFactory::createService
     */
    public function testInstanceReturned()
    {
        $sm = new ServiceManager();
        $sm->setService('SpiffyUser\ModuleOptions', new ModuleOptions());

        $factory  = new PasswordStrategyFactory();
        $strategy = $factory->createService($sm);
        $this->assertInstanceOf('SpiffyUser\Form\PasswordStrategy', $strategy);
    }
}