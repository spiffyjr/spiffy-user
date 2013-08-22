<?php

namespace SpiffyUserTest\Form;

use SpiffyUser\Form\LoginForm;

class LoginFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers SpiffyUser\Form\LoginForm::__construct
     */
    public function testFormConstruction()
    {
        $form = new LoginForm();
        $this->assertCount(3, $form->getElements());
    }

    /**
     * @covers SpiffyUser\Form\LoginForm::getInputFilterSpecification
     */
    public function testFormFilter()
    {
        $form   = new LoginForm();
        $filter = $form->getInputFilterSpecification();
        $this->assertCount(2, $filter);
    }
}