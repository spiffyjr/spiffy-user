<?php

namespace SpiffyUser\Authentication;

use SpiffyUser\Authentication\ChainEvent;
use Zend\EventManager\ListenerAggregateInterface;

interface ChainableAdapterInterface extends ListenerAggregateInterface
{
    /**
     * Authenticates in a chain.
     *
     * @param ChainEvent $e
     * @return void
     */
    public function onAuthenticate(ChainEvent $e);
}