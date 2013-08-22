<?php

namespace SpiffyUser\Authentication;

use DoctrineModule\Authentication\Adapter\ObjectRepository;
use SpiffyUser\Authentication\AdapterChain;
use SpiffyUser\Authentication\ChainableAdapterInterface;
use SpiffyUser\Authentication\ChainEvent;
use SpiffyUser\Entity\UserInterface;
use SpiffyUser\Extension\Password;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

class DoctrineAdapter extends AbstractListenerAggregate implements ChainableAdapterInterface
{
    /**
     * @var ObjectRepository
     */
    protected $adapter;

    /**
     * @var Password
     */
    protected $passwordExtension;

    /**
     * @param ObjectRepository $adapter
     * @param Password $passwordExtension
     */
    public function __construct(ObjectRepository $adapter, Password $passwordExtension)
    {
        $this->adapter           = $adapter;
        $this->passwordExtension = $passwordExtension;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(AdapterChain::EVENT_AUTHENTICATE, array($this, 'onAuthenticate'));
        $events->attach(AdapterChain::EVENT_TEARDOWN, array($this, 'onTeardown'));
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticate(ChainEvent $e)
    {
        if (!$e->getParam('email') || !$e->getParam('password')) {
            return;
        }

        $this->adapter->setIdentity($e->getParam('email'));
        $this->adapter->setCredential($e->getParam('password'));

        $passwordExtension = $this->passwordExtension;
        $this->adapter->getOptions()->setCredentialCallable(
            function (UserInterface $user, $password) use ($passwordExtension) {
                return $passwordExtension->verify($password, $user->getPassword());
            }
        );

        $result = $this->adapter->authenticate();
        $e->setCode($result->getCode());

        if (!$result->isValid()) {
            $e->addMessages($result->getMessages());
            return;
        }

        $e->setIdentity($result->getIdentity());
        $e->clearMessages();
    }

    /**
     * @param ChainEvent $e
     */
    public function onTeardown(ChainEvent $e)
    {
        $this->adapter->setIdentity(null);
        $this->adapter->setCredential(null);
    }
}
