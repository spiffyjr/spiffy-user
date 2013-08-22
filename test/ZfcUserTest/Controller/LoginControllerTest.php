<?php

namespace SpiffyUserTest\Controller;

use SpiffyUser\Form\LoginForm;
use SpiffyUser\ModuleOptions;
use SpiffyUserTest\Asset\User;
use Zend\Authentication\Result;
use Zend\Http\Response;

class LoginControllerTest extends AbstractControllerTestCase
{
    /**
     * @covers \SpiffyUser\Controller\LoginController::logoutAction
     */
    public function testLogoutAction()
    {
        $this->loginUser();

        $this->dispatch('/user/logout');
        $this->assertControllerName('SpiffyUser\Controller\LoginController');
        $this->assertActionName('logout');
        $this->assertRedirectTo('/user/login');

        $this->assertNull($this->getAuth()->getIdentity());
    }

    /**
     * @covers \SpiffyUser\Controller\LoginController::loginAction
     */
    public function testLoginActionRedirectsWhenLoggedIn()
    {
        $this->loginUser();

        $this->dispatch('/user/login');
        $this->assertControllerName('SpiffyUser\Controller\LoginController');
        $this->assertActionName('login');
        $this->assertRedirectTo('/user');
    }

    /**
     * @covers \SpiffyUser\Controller\LoginController::loginAction
     */
    public function testLoginActionDoesNotRedirect()
    {
        $this->logoutUser();

        $this->dispatch('/user/login');
        $this->assertControllerName('SpiffyUser\Controller\LoginController');
        $this->assertActionName('login');
        $this->assertNotRedirect();
    }

    /**
     * @covers \SpiffyUser\Controller\LoginController::loginAction
     */
    public function testLoginActionPrgResponse()
    {
        $this->logoutUser();

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $request->setMethod('POST');

        $this->dispatch('/user/login');
        $this->assertRedirectTo('/user/login');
    }

    /**
     * @covers \SpiffyUser\Controller\LoginController::loginAction
     */
    public function testLoginActionWithFalsePrg()
    {
        $this->logoutUser();

        $container = $this->getController('SpiffyUser\Controller\LoginController')->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->dispatch('/user/login');
        $this->assertNotRedirect();
    }

    /**
     * @covers \SpiffyUser\Controller\LoginController::loginAction
     */
    public function testValidLogin()
    {
        $this->logoutUser();

        $container = $this->getController('SpiffyUser\Controller\LoginController')->plugin('prg')->getSessionContainer();
        $container->post = array(
            'identity'   => 'identity',
            'credential' => 'credential'
        );

        $this->getMockService()
             ->expects($this->once())
             ->method('login')
             ->will($this->returnValue(new Result(Result::SUCCESS, new User(), array())));

        $this->dispatch('/user/login');
        $this->assertRedirect('/user');
    }

    /**
     * @covers \SpiffyUser\Controller\LoginController::getLoginService
     * @covers \SpiffyUser\Controller\LoginController::setLoginService
     */
    public function testGetRegisterService()
    {
        $controller = $this->getController('SpiffyUser\Controller\LoginController');
        $this->assertInstanceOf('SpiffyUser\Service\LoginService', $controller->getLoginService());
    }

    protected function getMockService()
    {
        /** @var \SpiffyUser\Controller\LoginController $controller */
        $controller   = $this->getController('SpiffyUser\Controller\LoginController');
        $loginService = $this->getMock('SpiffyUser\Service\LoginService');

        $controller->setLoginService($loginService);

        return $loginService;
    }
}