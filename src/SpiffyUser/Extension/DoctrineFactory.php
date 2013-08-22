<?php

namespace SpiffyUser\Extension;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctrineFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyUser\ModuleOptions $options */
        $options = $serviceLocator->get('SpiffyUser\ModuleOptions');

        return new Doctrine($serviceLocator->get($options->getObjectManagerService()));
    }
}