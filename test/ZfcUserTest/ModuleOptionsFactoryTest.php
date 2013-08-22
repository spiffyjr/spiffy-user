<?php

namespace SpiffyUserTest\Options;

use Zend\ServiceManager\ServiceManager;
use SpiffyUser\ModuleOptionsFactory;

class ModuleOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \SpiffyUser\ModuleOptionsFactory::createService
     */
    public function testCreateService()
    {
        $factory = new ModuleOptionsFactory();
        $sm      = new ServiceManager();

        $sm->setService('Configuration', array());
        $sm->setAllowOverride(true);

        $this->assertInstanceOf('SpiffyUser\ModuleOptions', $factory->createService($sm));

        $sm->setService('Configuration', array(
            'spiffy_user' => array(
                'entityClass' => 'foo\bar'
            )
        ));

        $options = $factory->createService($sm);

        $this->assertInstanceOf('SpiffyUser\ModuleOptions', $options);
        $this->assertEquals('foo\bar', $options->getEntityClass());
    }
}