<?php

namespace SpiffyUserTest\Service;

use Zend\EventManager\EventManager;
use SpiffyUserTest\Asset\LoginListener;
use SpiffyUserTest\Asset\PluginService;
use SpiffyUserTest\Asset\RegisterListener;

class AbstractPluginServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \SpiffyUser\Service\AbstractPluginService::setEventManager
     */
    public function testSetEventManager()
    {
        $service = new PluginService();
        $em      = new EventManager();
        $service->setEventManager($em);

        $expected = array(
            'SpiffyUser\Service\AbstractPluginService',
            'SpiffyUserTest\Asset\PluginService'
        );

        $this->assertEquals($expected, $em->getIdentifiers());
    }

    /**
     * @covers \SpiffyUser\Service\AbstractPluginService::registerPlugin
     * @covers \SpiffyUser\Service\AbstractPluginService::getEventManager
     */
    public function testRegisterPlugin()
    {
        $service = new PluginService();
        $service->registerPlugin(new LoginListener());

        $this->setExpectedException('SpiffyUser\Service\Exception\InvalidPluginException');
        $service->registerPlugin(new RegisterListener());
    }
}
