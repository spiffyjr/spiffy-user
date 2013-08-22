<?php

return array(
    'controller_plugins' => array(
        'factories' => array(
            'zfcUserExtension' => 'SpiffyUser\Controller\Plugin\ExtensionFactory'
        )
    ),

    'controllers' => include 'controller.config.php',

    'doctrine' => array(
        'authentication' => array(
            'orm_default' => array(
                'object_manager'      => 'Doctrine\ORM\EntityManager',
                'identity_class'      => 'Application\Entity\User',
                'identity_property'   => 'email',
                'credential_property' => 'password'
            )
        ),
    ),

    'router' => include 'router.config.php',

    'service_manager' => include 'service.config.php',

    'view_helpers' => array(
        'invokables' => array(
            'zfcUserExtension' => 'SpiffyUser\View\Helper\SpiffyUserExtension'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'spiffy-user/partial/form'      => __DIR__ . '/../view/spiffy-user/partial/form.phtml',
            'spiffy-user/user/index'        => __DIR__ . '/../view/spiffy-user/user/index.phtml',
            'spiffy-user/login/login'       => __DIR__ . '/../view/spiffy-user/login/login.phtml',
            'spiffy-user/register/register' => __DIR__ . '/../view/spiffy-user/register/register.phtml',
        )
    ),

    // See SpiffyUser\ModuleOptions for a list of all options.
    'spiffy_user' => array(
        'extensions' => array(
            'authentication' => 'SpiffyUser\Extension\Authentication',

            'doctrine' => 'SpiffyUser\Extension\Doctrine',

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
                    'entity_class' => 'SpiffyUser\Entity\Orm\User'
                )
            )
        )
    ),
);