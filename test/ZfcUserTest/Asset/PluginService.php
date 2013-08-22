<?php

namespace SpiffyUserTest\Asset;

use SpiffyUser\Service\AbstractPluginService;

class PluginService extends AbstractPluginService
{
    protected $allowedPluginInterfaces = array(
        'SpiffyUser\Plugin\LoginPluginInterface'
    );
}
