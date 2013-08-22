<?php

return array(
    'controller_plugins' => array(
        'factories' => array(
            'zfcUserExtension' => 'SpiffyUser\Controller\Plugin\ExtensionFactory'
        )
    ),

    'controllers' => include 'controller.config.php',

    'router' => include 'router.config.php',

    'service_manager' => include 'service.config.php',

    'view_helpers' => array(
        'invokables' => array(
            'zfcUserExtension' => 'SpiffyUser\View\Helper\SpiffyUserExtension'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'zfc-user/partial/form'      => __DIR__ . '/../view/zfc-user/partial/form.phtml',
            'zfc-user/user/index'        => __DIR__ . '/../view/zfc-user/user/index.phtml',
            'zfc-user/login/login'       => __DIR__ . '/../view/zfc-user/login/login.phtml',
            'zfc-user/register/register' => __DIR__ . '/../view/zfc-user/register/register.phtml',
        )
    ),

    // See SpiffyUser\ModuleOptions for a list of all options.
    'zfc_user' => array(
        'extensions' => array(
            'authentication' => 'SpiffyUser\Extension\Authentication',

            'password' => array(
                'type' => 'SpiffyUser\Extension\Password',
                'options' => array(
                    'cost' => 14,
                    'salt' => 'change_the_default_salt!'
                )
            ),

            'register' => 'SpiffyUser\Extension\Register',

            'user' => array(
                'type' => 'SpiffyUser\Extension\User',
                'options' => array(
                    'entity_class' => 'Application\Entity\User'
                )
            )
        )
    ),
);