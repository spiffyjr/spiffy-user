<?php

namespace SpiffyUser\Extension;

use Doctrine\Common\Persistence\ObjectManager;

class Doctrine extends AbstractExtension
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'doctrine';
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}