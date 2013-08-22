<?php

return array(
    'invokables' => array(
        'SpiffyUser\Form\LoginForm'    => 'SpiffyUser\Form\LoginForm',
        'SpiffyUser\Form\RegisterForm' => 'SpiffyUser\Form\RegisterForm',
    ),

    'factories' => array(
        'SpiffyUser\Extension\Authentication' => 'SpiffyUser\Extension\AuthenticationFactory',
        'SpiffyUser\Extension\Doctrine'       => 'SpiffyUser\Extension\DoctrineFactory',
        'SpiffyUser\Extension\Manager'        => 'SpiffyUser\Extension\ManagerFactory',
        'SpiffyUser\ModuleOptions'            => 'SpiffyUser\ModuleOptionsFactory',

        'Zend\Authentication\AuthenticationService' => 'SpiffyUser\Authentication\AuthenticationServiceFactory',
    )
);