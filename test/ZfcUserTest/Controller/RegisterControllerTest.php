<?php

namespace SpiffyUserTest\Controller;

use SpiffyUser\Form\RegisterForm;
use SpiffyUser\ModuleOptions;
use SpiffyUserTest\Asset\User;

class RegisterControllerTest extends AbstractControllerTestCase
{
    /**
     * @covers \SpiffyUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionRedirectsWhenLoggedIn()
    {
        $this->loginUser();

        $this->dispatch('/user/register');
        $this->assertControllerName('SpiffyUser\Controller\RegisterController');
        $this->assertActionName('register');
        $this->assertRedirectTo('/user');
    }

    /**
     * @covers \SpiffyUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionDoesNotRedirect()
    {
        $this->logoutUser();

        $this->dispatch('/user/register');
        $this->assertControllerName('SpiffyUser\Controller\RegisterController');
        $this->assertActionName('register');
        $this->assertNotRedirect();
    }

    /**
     * @covers \SpiffyUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionPrgResponse()
    {
        $this->logoutUser();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $request->setMethod('POST');

        $this->dispatch('/user/register');
        $this->assertRedirectTo('/user/register');
    }

    /**
     * @covers \SpiffyUser\Controller\RegisterController::registerAction
     */
    public function testRegisterActionWithFalsePrg()
    {
        $this->logoutUser();

        $controller = $this->getController('SpiffyUser\Controller\RegisterController');
        $container  = $controller->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->dispatch('/user/register');
        $this->assertNotRedirect();
    }

    /**
     * @covers \SpiffyUser\Controller\RegisterController::registerAction
     */
    public function testValidRegistration()
    {
        $this->logoutUser();

        $controller = $this->getController('SpiffyUser\Controller\RegisterController');
        $container  = $controller->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->getMockService()
            ->expects($this->once())
            ->method('register')
            ->will($this->returnValue(new User()));

        $this->dispatch('/user/register');
        $this->assertRedirect('/user');
    }

    /**
     * @covers \SpiffyUser\Controller\RegisterController::registerAction
     */
    public function testInvalidRegistration()
    {
        $this->logoutUser();

        $controller = $this->getController('SpiffyUser\Controller\RegisterController');
        $container  = $controller->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->getMockService()
             ->expects($this->once())
             ->method('register')
             ->will($this->returnValue(null));

        $this->dispatch('/user/register');
        $this->assertNotRedirect();
    }

    /**
     * @covers \SpiffyUser\Controller\RegisterController::getRegisterService
     * @covers \SpiffyUser\Controller\RegisterController::setRegisterService
     */
    public function testGetRegisterService()
    {
        $controller = $this->getController('SpiffyUser\Controller\RegisterController');
        $this->assertInstanceOf('SpiffyUser\Service\RegisterService', $controller->getRegisterService());
    }

    protected function getMockService()
    {
        /** @var \SpiffyUser\Controller\RegisterController $controller */
        $controller = $this->getController('SpiffyUser\Controller\RegisterController');
        $service    = $this->getMock(
            'SpiffyUser\Service\RegisterService',
            array(),
            array(
                new ModuleOptions()
            )
        );

        $controller->setRegisterService($service);
        return $service;
    }
}