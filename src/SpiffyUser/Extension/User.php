<?php

namespace SpiffyUser\Extension;

use SpiffyUser\Entity\UserInterface;

class User extends AbstractExtension
{
    /**
     * @var array
     */
    protected $options = array(
        'entity_class' => 'Application\Entity\User',
    );

    /**
     * @var UserInterface
     */
    protected $prototype;

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

    /**
     * @throws Exception\InvalidUserException
     * @return string
     */
    public function getPrototype()
    {
        if (!$this->prototype) {
            $userClass = $this->options['entity_class'];
            if (!class_exists($userClass)) {
                throw new Exception\InvalidUserException(
                    sprintf(
                        'class %s could not be found',
                        $userClass
                    )
                );
            }
            $this->prototype = new $userClass();
            if (!$this->prototype instanceof UserInterface) {
                throw new Exception\InvalidUserException(
                    'user must be an instance of SpiffyUser\Entity\UserInterface'
                );
            }
        }
        return $this->prototype;
    }
}