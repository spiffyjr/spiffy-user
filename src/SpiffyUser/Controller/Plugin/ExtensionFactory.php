<?php

namespace SpiffyUser\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExtensionFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Extension
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyUser\Extension\Manager $manager */
        $manager   = $serviceLocator->getServiceLocator()->get('SpiffyUser\Extension\Manager');
        $extension = new Extension($manager);

        return $extension;
    }
}