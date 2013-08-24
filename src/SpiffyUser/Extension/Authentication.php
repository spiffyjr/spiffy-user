<?php

namespace SpiffyUser\Extension;

use SpiffyUser\Authentication\DoctrineAdapter;
use SpiffyUser\Authentication\AdapterChain;
use SpiffyUser\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class Authentication extends AbstractExtension
{
    const EVENT_LOGIN_PRE          = 'authenticate.login.pre';
    const EVENT_LOGIN_POST         = 'authenticate.login.post';
    const EVENT_LOGIN_PREPARE_FORM = 'authenticate.login.prepareForm';
    const EVENT_LOGOUT_PRE         = 'authenticate.logout.pre';
    const EVENT_LOGOUT_POST        = 'authenticate.logout.post';

    /**
     * @var AdapterChain
     */
    protected $adapterChain;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * @var \Zend\Form\FormInterface
     */
    protected $loginForm;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'authentication';
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Authentication::EVENT_LOGIN_PRE, array($this, 'onLogin'));
        $this->listeners[] = $events->attach(Register::EVENT_REGISTER, array($this, 'onRegister'));
    }

    /**
     * @param EventInterface $e
     */
    public function onLogin(EventInterface $e)
    {
        /** @var \SpiffyUser\Extension\Password $password */
        $password = $this->getManager()->get('password');
        $adapter  = new DoctrineAdapter($this->authenticationService->getAdapter(), $password);

        /** @var \SpiffyUser\Extension\Authentication $auth */
        $auth = $this->getManager()->get('authentication');
        $auth->getAdapterChain()->addAdapter($adapter);
    }

    /**
     * @param EventInterface $e
     */
    public function onRegister(EventInterface $e)
    {
        /** @var \SpiffyUser\Entity\UserInterface $user */
        $user = $e->getTarget();

        /** @var \SpiffyUser\Extension\Doctrine $doctrine */
        $doctrine = $this->getManager()->get('doctrine');
        $om       = $doctrine->getObjectManager();

        $om->persist($user);
        $om->flush();
    }

    /**
     * Takes an array of parameters which gets passed directly to the pre and post login events.
     * It's up to each adapter to ignore the auth attempt if the parameters they are expecting aren't available.
     *
     * @param array $data
     * @triggers static::EVENT_LOGIN_PRE
     * @triggers static::EVENT_LOGIN_POST
     * @return \Zend\Authentication\Result
     */
    public function login(array $data)
    {
        $manager = $this->getManager();
        $event   = $manager->getEvent();
        $event->setParams($data);

        $manager->getEventManager()->trigger(static::EVENT_LOGIN_PRE, $event);

        $authService = $this->getAuthenticationService();
        $adapter     = $this->getAdapterChain();
        $adapter->setEventParams($data);

        $result = $authService->authenticate($adapter);

        $event->setParams(array('result' => $result));
        $manager->getEventManager()->trigger(static::EVENT_LOGIN_POST, $event);

        return $result;
    }

    /**
     * Logout the identity.
     */
    public function logout()
    {
        $manager = $this->getManager();
        $event   = $manager->getEvent();

        $manager->getEventManager()->trigger(static::EVENT_LOGOUT_PRE, $event);

        $this->getAuthenticationService()->clearIdentity();

        $manager->getEventManager()->trigger(static::EVENT_LOGOUT_POST, $event);
    }

    /**
     * @param AdapterChain $adapterChain
     * @return $this
     */
    public function setAdapterChain(AdapterChain $adapterChain)
    {
        $this->adapterChain = $adapterChain;
        return $this;
    }

    /**
     * @return AdapterChain
     */
    public function getAdapterChain()
    {
        if (!$this->adapterChain instanceof AdapterChain) {
            $this->setAdapterChain(new AdapterChain());
        }
        return $this->adapterChain;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     * @return \Zend\Form\FormInterface
     */
    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm(new LoginForm());
        }
        return $this->loginForm;
    }

    /**
     * @param LoginForm $loginForm
     * @triggers static::EVENT_LOGIN_PREPARE_FORM
     * @return $this
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->getManager()->getEventManager()->trigger(static::EVENT_LOGIN_PREPARE_FORM, $loginForm);
        $this->loginForm = $loginForm;
        return $this;
    }
}