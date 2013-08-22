<?php

namespace SpiffyUserTest\Service;

use Zend\ServiceManager\ServiceManager;
use SpiffyUser\ModuleOptions;
use SpiffyUser\Service\RegisterServiceFactory;
use SpiffyUserTest\Asset\RegisterListener;

class RegisterServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegisterServiceFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new RegisterServiceFactory();
    }

    /**
     * @covers \SpiffyUser\Service\RegisterServiceFactory::createService
     */
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setRegisterPlugins(array(new RegisterListener()));

        $sm = new ServiceManager();
        $sm->setService('SpiffyUser\ModuleOptions', $options);

        $instance = $this->factory->createService($sm);

        $this->assertInstanceOf('SpiffyUser\Service\RegisterService', $instance);
    }
}
