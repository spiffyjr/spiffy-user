## Getting Started

### 1) Install Module

Installation of SpiffyUser is performed via [Composer](http://getcomposer.org).

Add these entries to the `require` section of your `composer.json` file:

```
"spiffy/spiffy-user": "dev-master"
```

then run `composer update` from the command line to install SpiffyUser and it's dependencies.  Once Composer has installed everything, enable the module by adding `SpiffyUser` to the `modules` section of the application configuration file (`config/application.config.php`)

### 2) Add User Entity Class

SpiffyUser does not provide a built-in user entity class, so you must provide your own which implements `SpiffyUser\Entity\UserInterface`.  A trait containing the basic account fields is provided for ease of integration.

Example:

```
<?php
namespace MyModule\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="account")
 */
class User
{
    use \SpiffyUser\Entity\Orm\UserTrait;
}
```

> NOTE: if you are targeting PHP 5.3.x then you will need to copy the contents of `SpiffyUser\Entity\Orm\UserTrait` into the body of your User entity class, as PHP <5.4 does not support traits. 

If you haven't done so already, you must also register your entity namespace with Doctrine ORM by adding the following to your module configuration file (`config/module.config.php`):

```
'doctrine' => array(
    'driver' => array(
        'my-module_entity' => array(
            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'paths' => __DIR__ . '/../src/MyModule/Entity',
        ),
        'orm_default' => array(
            'drivers' => array(
                'MyModule\Entity' => 'my-module_entity',
            ),
        ),
    ),
),
```

Now run DoctrineModule from the command line to create the database schema:

```
./vendor/bin/doctrine-module orm:schema-tool:update --force
```

###  3) Configure SpiffyUser

Once your user entity has been registered it's time to tell SpiffyUser about it.  Add the following to your module's configuration file:

```
'spiffy_user' => array(
    'login_redirect' => true,
    'extensions' => array(
        'user' => array(
            'options' => array(
                'entity_class' => 'MyModule\Entity\User'
            ),
        ),
    ),
),
```

We also have to tell Doctrine to use your User entity as the identity class during authentication.  All the following within the 'doctrine' block of your module's configuration file:

```
'authentication' => array(
    'orm_default' => array(
        'object_manager'      => 'Doctrine\ORM\EntityManager',
        'identity_class'      => 'MyModule\Entity\User',
        'identity_property'   => 'email',
        'credential_property' => 'password'
    ),
),
```

If you are using a JavaScript-heavy application with many AJAX requests, you may wish to toggle the ``login_remember`` option if you are not properly specifying Content-Types. This option toggles the module's ability to detect the previous page the user was on and redirect them there after signing in. It will store a return URL in a session variable if the Content-Type is ``text/html`` and the user is not current browsing the login route. This behavior is not compatible with fragments on URLs as they are client-side.

You can manually override the auto-detected page with a ``/user/login?return=/some/path#fragment`` query string parameter on the login URL to support fragments.