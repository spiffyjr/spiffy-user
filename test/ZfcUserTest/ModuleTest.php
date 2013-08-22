<?php

namespace SpiffyUserTest;

use SpiffyUser\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Module
     */
    protected $module;

    public function setUp()
    {
        $this->module = new Module();
    }

    public function testGetConfig()
    {
        $this->assertEquals(
            include __DIR__ . '/../../config/module.config.php',
            $this->module->getConfig()
        );
    }

    public function testGetAutoloaderConfig()
    {
        $expected = array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'SpiffyUser' => realpath(__DIR__ . '/../../src/SpiffyUser')
                )
            )
        );

        $this->assertEquals($expected, $this->module->getAutoloaderConfig());
    }
}
