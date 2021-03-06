<?php

namespace SpiffyUser\Extension;

use Zend\Crypt\Password\Bcrypt;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;
use SpiffyUser\Entity\UserInterface;
use SpiffyUser\Form\PasswordStrategy;
use SpiffyUser\Form\RegisterForm;

class Register extends AbstractExtension
{
    const EVENT_PREPARE_FORM = 'register.prepareForm';
    const EVENT_REGISTER     = 'register.register';

    /**
     * @return string
     */
    public function getName()
    {
        return 'register';
    }

    /**
     * @var RegisterForm
     */
    protected $form;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var UserInterface
     */
    protected $userPrototype;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Manager::EVENT_EXTENSION_LOAD, array($this, 'onLoad'));
    }

    /**
     * @param EventInterface $e
     */
    public function onLoad(EventInterface $e)
    {
        /** @var \SpiffyUser\Extension\Password $password */
        $password = $this->getManager()->get('password');

        $hydrator = new ClassMethods();
        $hydrator->addStrategy('password', new PasswordStrategy($password));

        $this->setHydrator($hydrator);
    }

    /**
     * Register a new user using the registration form and registration
     * mapper.
     *
     * @param array $data
     * @throws Exception\InvalidUserException
     * @return null|UserInterface
     */
    public function register(array $data)
    {
        /** @var \SpiffyUser\Extension\User $userExtension */
        $userExtension = $this->getManager()->get('user');

        $form    = $this->getForm();
        $manager = $this->getManager();
        $event   = $manager->getEvent();

        $form->bind(clone $userExtension->getPrototype());
        $form->setData($data);

        if (!$form->isValid()) {
            return null;
        }

        /** @var \SpiffyUser\Entity\UserInterface $user */
        $user = $form->getData();

        if (!$user instanceof UserInterface) {
            throw new Exception\InvalidUserException(
                'user must be an instance of SpiffyUser\Entity\UserInterface'
            );
        }

        $event->setTarget($user);
        $event->setParams($data);
        $manager->getEventManager()->trigger(static::EVENT_REGISTER, $event);

        return $user;
    }

    /**
     * @param \SpiffyUser\Form\RegisterForm $form
     * @return $this
     */
    public function setForm(RegisterForm $form)
    {
        $manager = $this->getManager();
        $event   = $manager->getEvent();
        $event->setTarget($form);

        $manager->getEventManager()->trigger(static::EVENT_PREPARE_FORM, $event);
        $this->form = $form;
        return $this;
    }

    /**
     * @return \SpiffyUser\Form\RegisterForm
     */
    public function getForm()
    {
        if (!$this->form) {
            $this->setForm(new RegisterForm());
            $this->form->setHydrator($this->getHydrator());
        }
        return $this->form;
    }

    /**
     * @param \Zend\Stdlib\Hydrator\HydratorInterface $hydrator
     * @return $this
     */
    public function setHydrator($hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     * @return \Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }
}