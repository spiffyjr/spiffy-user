<?php

namespace SpiffyUser;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Attempt to detect the page the user was previously on and redirect them there after login. Can not redirect
     * to fragments, and may conflict with heavily javascript-based applications if Content-Types are not set properly.
     *
     * @var bool
     */
    protected $loginRedirect = true;

    /**
     * A service manager configuration to be registered with the extension manager.
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * @var string
     */
    protected $adapterService = 'doctrine.authenticationadapter.orm_default';

    /**
     * @var string
     */
    protected $objectManagerService = 'Doctrine\ORM\EntityManager';

    /**
     * @param boolean $loginRedirect
     */
    public function setLoginRedirect($loginRedirect)
    {
        $this->loginRedirect = $loginRedirect;
    }

    /**
     * @return boolean
     */
    public function getLoginRedirect()
    {
        return $this->loginRedirect;
    }

    /**
     * @param string $adapterService
     * @return $this
     */
    public function setAdapterService($adapterService)
    {
        $this->adapterService = $adapterService;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdapterService()
    {
        return $this->adapterService;
    }

    /**
     * @param string $objectManagerService
     * @return $this
     */
    public function setObjectManagerService($objectManagerService)
    {
        $this->objectManagerService = $objectManagerService;
        return $this;
    }

    /**
     * @return string
     */
    public function getObjectManagerService()
    {
        return $this->objectManagerService;
    }

    /**
     * @param array $extensions
     * @return $this
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}
