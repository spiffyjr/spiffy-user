<?php

return array(
    'routes' => array(
        'spiffy_user' => array(
            'type' => 'literal',
            'options' => array(
                'route'    => '/user',
                'defaults' => array(
                    'controller' => 'SpiffyUser\Controller\UserController',
                    'action'     => 'index',
                ),
            ),
            'may_terminate' => true,
            'child_routes' => array(
                'login' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/login',
                        'defaults' => array(
                            'controller' => 'SpiffyUser\Controller\LoginController',
                            'action'     => 'login'
                        )
                    )
                ),
                'logout' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/logout',
                        'defaults' => array(
                            'controller' => 'SpiffyUser\Controller\LoginController',
                            'action'     => 'logout'
                        )
                    )
                ),
                'register' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/register',
                        'defaults' => array(
                            'controller' => 'SpiffyUser\Controller\RegisterController',
                            'action'     => 'register'
                        )
                    )
                )
            )
        )
    )
);