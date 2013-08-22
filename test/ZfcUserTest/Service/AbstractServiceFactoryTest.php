<?php

namespace SpiffyUser\Service;

use Zend\ServiceManager\ServiceManager;
use SpiffyUserTest\Asset\Entity;
use SpiffyUserTest\Asset\ServiceFactory;

class AbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \SpiffyUser\Service\AbstractServiceFactory::get
     */
    public function testGetHandlesStrings()
    {
        $sm      = new ServiceManager();
        $factory = new ServiceFactory('SpiffyUserTest\Asset\Entity');
        $user    = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyUserTest\Asset\Entity', $user);
    }

    /**
     * @covers \SpiffyUser\Service\AbstractServiceFactory::get
     */
    public function testGetHandlesServices()
    {
        $sm = new ServiceManager();
        $sm->setService('test', new Entity());

        $factory = new ServiceFactory('test');
        $user    = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyUserTest\Asset\Entity', $user);
    }
}
