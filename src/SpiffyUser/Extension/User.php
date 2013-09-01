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
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        /** @var \SpiffyUser\Extension\Doctrine $doctrine */
        $doctrine = $this->getManager()->get('doctrine');
        $om       = $doctrine->getObjectManager();

        return $om->getRepository($this->options['entity_class']);
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