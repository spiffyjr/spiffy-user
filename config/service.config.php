<?php

return array(
    'invokables' => array(
        'SpiffyUser\Form\LoginForm'    => 'SpiffyUser\Form\LoginForm',
        'SpiffyUser\Form\RegisterForm' => 'SpiffyUser\Form\RegisterForm',
    ),

    'factories' => array(
        'SpiffyUser\Extension\Authentication' => 'SpiffyUser\Extension\AuthenticationFactory',
        'SpiffyUser\Extension\Manager'        => 'SpiffyUser\Extension\ManagerFactory',
        'SpiffyUser\ModuleOptions'            => 'SpiffyUser\ModuleOptionsFactory',
    )
);