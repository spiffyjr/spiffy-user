<?php

namespace SpiffyUserTest\Service;

use Zend\ServiceManager\ServiceManager;
use SpiffyUser\ModuleOptions;
use SpiffyUser\Service\LoginServiceFactory;
use SpiffyUserTest\Asset\ChainAdapter;
use SpiffyUserTest\Asset\LoginListener;

class LoginServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LoginServiceFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new LoginServiceFactory();
    }

    /**
     * @covers \SpiffyUser\Service\LoginServiceFactory::createService
     */
    public function testInstanceReturned()
    {
        $options = new ModuleOptions();
        $options->setLoginPlugins(array(new LoginListener()));
        $options->setLoginAdapters(array(new ChainAdapter()));

        $sm = new ServiceManager();
        $sm->setService('SpiffyUser\ModuleOptions', $options);

        $instance = $this->factory->createService($sm);

        $this->assertInstanceOf('SpiffyUser\Service\LoginService', $instance);
    }
}
