<?php

namespace SpiffyUserTest\Service;

use ArrayObject;
use Zend\Stdlib\Hydrator\ClassMethods;
use SpiffyUser\Form\RegisterForm;
use SpiffyUser\ModuleOptions;
use SpiffyUser\Service\RegisterService;
use SpiffyUserTest\Asset\User;

class RegisterServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegisterForm
     */
    protected $form;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var RegisterService
     */
    protected $service;

    public function setUp()
    {
        $this->options = new ModuleOptions();
        $this->options->setEntityClass('SpiffyUserTest\Asset\User');

        $this->service = new RegisterService($this->options);
        $this->service->getRegisterForm()->setHydrator(new ClassMethods());
    }

    /**
     * @covers \SpiffyUser\Service\RegisterService::__construct
     * @covers \SpiffyUser\Service\RegisterService::register
     */
    public function testInvalidRegister()
    {
        $result = $this->service->register(array('invalid' => 'stuff'));
        $this->assertNull($result);
    }

    /**
     * @covers \SpiffyUser\Service\RegisterService::register
     */
    public function testValidRegister()
    {
        $user = new User();
        $form = $this->getMock('SpiffyUser\Form\RegisterForm');
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));

        $form->expects($this->once())
             ->method('getData')
             ->will($this->returnValue($user));

        $this->service->setRegisterForm($form);
        $result = $this->service->register(array('data' => 'data'));

        $this->assertEquals($user, $result);
    }

    /**
     * @covers \SpiffyUser\Service\RegisterService::register
     */
    public function testInvalidUserRegister()
    {
        $this->setExpectedException('SpiffyUser\Service\Exception\InvalidUserException');

        $form = $this->getMock('SpiffyUser\Form\RegisterForm');
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue(new ArrayObject()));

        $this->service->setRegisterForm($form);
        $this->service->register(array('data' => 'data'));
    }

    /**
     * @covers \SpiffyUser\Service\RegisterService::setRegisterForm
     * @covers \SpiffyUser\Service\RegisterService::getRegisterForm
     */
    public function testGetSetRegisterForm()
    {
        $this->assertInstanceOf('SpiffyUser\Form\RegisterForm', $this->service->getRegisterForm());

        $form = new RegisterForm();
        $this->service->setRegisterForm($form);
        $this->assertEquals($form, $this->service->getRegisterForm());
    }

    /**
     * @covers \SpiffyUser\Service\RegisterService::getUserPrototype
     */
    public function testUserPrototype()
    {
        $user = $this->service->getUserPrototype();
        $this->assertInstanceOf('SpiffyUserTest\Asset\User', $user);
    }

    /**
     * @covers \SpiffyUser\Service\RegisterService::getUserPrototype
     */
    public function testMissingUserPrototype()
    {
        $this->setExpectedException('SpiffyUser\Service\Exception\InvalidUserException');
        $this->options->setEntityClass('DoesNotExist');
        $this->service->getUserPrototype();
    }

    /**
     * @covers \SpiffyUser\Service\RegisterService::getUserPrototype
     */
    public function testInvalidUserPrototype()
    {
        $this->setExpectedException('SpiffyUser\Service\Exception\InvalidUserException');
        $this->options->setEntityClass('ArrayObject');
        $this->service->getUserPrototype();
    }
}
