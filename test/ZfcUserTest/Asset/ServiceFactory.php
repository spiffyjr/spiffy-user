<?php

namespace SpiffyUserTest\Asset;

use Zend\ServiceManager\ServiceLocatorInterface;
use SpiffyUser\Service\AbstractServiceFactory;

class ServiceFactory extends AbstractServiceFactory
{
    /**
     * @var string
     */
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->get($serviceLocator, $this->name);
    }
}
