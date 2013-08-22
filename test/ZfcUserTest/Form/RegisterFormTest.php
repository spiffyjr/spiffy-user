<?php

namespace SpiffyUserTest\Form;

use SpiffyUser\Form\RegisterForm;

class RegisterFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers SpiffyUser\Form\RegisterForm::__construct
     */
    public function testFormConstruction()
    {
        $form = new RegisterForm();
        $this->assertCount(5, $form->getElements());
    }

    /**
     * @covers SpiffyUser\Form\RegisterForm::getInputFilterSpecification
     */
    public function testFormFilter()
    {
        $form   = new RegisterForm();
        $filter = $form->getInputFilterSpecification();
        $this->assertCount(3, $filter);
    }
}