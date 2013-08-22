<?php

namespace SpiffyUserTest\Controller;

class UserControllerTest extends AbstractControllerTestCase
{
    /**
     * @covers \SpiffyUser\Controller\UserController::indexAction
     */
    public function testIndexAction()
    {
        $this->dispatch('/user');
        $this->assertControllerName('SpiffyUser\Controller\UserController');
        $this->assertActionName('index');
        $this->assertRedirectTo('/user/login');

        $this->loginUser();

        $this->dispatch('/user');
        $this->assertActionName('index');
        $this->assertControllerName('SpiffyUser\Controller\UserController');
        $this->assertNotRedirect();
    }
}