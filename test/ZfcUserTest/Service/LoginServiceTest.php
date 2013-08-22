<?php

namespace SpiffyUserTest\Service;

use SpiffyUser\Form\LoginForm;
use SpiffyUser\ModuleOptions;
use SpiffyUser\Service\LoginService;
use SpiffyUserTest\Asset\LoginListener;

class LoginServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LoginForm
     */
    protected $form;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var LoginService
     */
    protected $service;

    public function setUp()
    {
        $this->service = new LoginService();
    }

    /**
     * @covers \SpiffyUser\Service\LoginService::login
     */
    public function testLogin()
    {
        $result = $this->service->login(array('identity' => 'test', 'credential' => 'test'));
        $this->assertInstanceOf('Zend\Authentication\Result', $result);
    }

    /**
     * @covers \SpiffyUser\Service\LoginService::logout
     */
    public function testLogout()
    {
        $auth = $this->service->getAuthenticationService();
        $auth->getStorage()->write(array());

        $this->service->logout();
        $this->assertNull($auth->getStorage()->read());
    }

    /**
     * @covers \SpiffyUser\Service\LoginService::getAdapterChain
     * @covers \SpiffyUser\Service\LoginService::setAdapterChain
     */
    public function testChainIsLazyLoaded()
    {
        $this->assertInstanceOf(
            'SpiffyUser\Authentication\AdapterChain',
            $this->service->getAdapterChain()
        );
    }

    /**
     * @covers \SpiffyUser\Service\LoginService::getAuthenticationService
     * @covers \SpiffyUser\Service\LoginService::setAuthenticationService
     */
    public function testAuthServiceIsLazyLoaded()
    {
        $this->assertInstanceOf(
            'Zend\Authentication\AuthenticationService',
            $this->service->getAuthenticationService()
        );
    }

    /**
     * @covers \SpiffyUser\Service\LoginService::setLoginForm
     * @covers \SpiffyUser\Service\LoginService::getLoginForm
     */
    public function testSetLoginFormFromListenerOnlyTriggersOnce()
    {
        $this->service->registerPlugin(new LoginListener());
        $form = $this->service->getLoginForm();
        $this->assertCount(4, $form->getElements());

        $form = $this->service->getLoginForm();
        $this->assertCount(4, $form);
    }

    /**
     * @covers \SpiffyUser\Service\LoginService::getLoginForm
     */
    public function testGetLoginForm()
    {
        $this->assertInstanceOf('SpiffyUser\Form\LoginForm', $this->service->getLoginForm());
    }
}
