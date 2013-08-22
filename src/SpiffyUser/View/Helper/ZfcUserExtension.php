<?php

namespace SpiffyUser\View\Helper;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use SpiffyUser\Extension\Manager;

class SpiffyUserExtension extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param null|$name
     * @return Manager|\SpiffyUser\Extension\ExtensionInterface
     */
    public function __invoke($name = null)
    {
        if ($name) {
            return $this->getManager()->get($name);
        }
        return $this->getManager();
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array(array($this->getManager(), $name), $arguments);
    }

    /**
     * @param \SpiffyUser\Extension\Manager $manager
     * @return $this
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * @return \SpiffyUser\Extension\Manager
     */
    public function getManager()
    {
        if (!$this->manager instanceof Manager) {
            $this->manager = $this->getServiceLocator()->get('SpiffyUser\Extension\Manager');
        }
        return $this->manager;
    }

    /**
     * {@inheritDoc}
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof AbstractPluginManager) {
            $this->serviceLocator = $serviceLocator->getServiceLocator();
        } else {
            $this->serviceLocator = $serviceLocator;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}