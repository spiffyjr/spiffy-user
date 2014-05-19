<?php

namespace SpiffyUser;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\Mvc\MvcEvent $e */
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        /** @var \SpiffyUser\ModuleOptions $options */
        $options = $sm->get('SpiffyUser\ModuleOptions');
        if ($options->getLoginRedirect()) {
            $app->getEventManager()->attach(MvcEvent::EVENT_FINISH, [$this, 'onFinish']);
        }

        // Load extensions (fires load event for each registered extension)
        $sm->get('SpiffyUser\Extension\Manager')->loadExtensions();
    }

    /**
     * {@inheritDoc}
     */
    public function onFinish(EventInterface $e)
    {
        /** @var \Zend\Http\Request $request */
        $request = $e->getRequest();
        /** @var \Zend\Http\Response $response */
        $response = $e->getResponse();
        $route = $e->getRouteMatch();

        if (!$route) {
            return;
        }

        // Non-html routes rendered by ZF2 (such as an AJAX response) will be considered valid targets for redirect
        // unless we check the Content-Type and prevent this behavior as best we can.
        if ($response->getHeaders()->has('Content-Type')) {
            $mediaType = $response->getHeaders()->get('Content-Type')->getFieldValue('mediaType');
            if (strpos($mediaType, 'text/html') === false) {
                return;
            }
        }

        if ($route->getMatchedRouteName() != 'spiffy_user/login') {
            $uriSession = new Container('uri');
            $uriSession->return = $request->getUriString();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
