<?php

namespace SpiffyUser\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

class LoginController extends AbstractActionController
{
    /**
     * @return Response
     */
    public function logoutAction()
    {
        if ($this->identity()) {
            $this->getAuthExtension()->logout();
        }

        /** @var \SpiffyUser\ModuleOptions $options */
        $options = $this->getServiceLocator()->get('SpiffyUser\ModuleOptions');
        $uriContainer = new Container('uri');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $uri = $request->getQuery()->toArray();

        $return = false;
        if (isset($uri['return']) && !parse_url($uri['return'], PHP_URL_SCHEME)) {
            $return = rawurldecode($uri['return']);
        } elseif (isset($uriContainer->return)) {
            $return = $uriContainer->return;
        }

        if ($options->getLoginRedirect() && $return) {
            return $this->redirect()->toUrl($return);
        } else {
            return $this->redirect()->toRoute('spiffy_user/login');
        }
    }

    /**
     * @return array|Response
     */
    public function loginAction()
    {
        /** @var \SpiffyUser\ModuleOptions $options */
        $options = $this->getServiceLocator()->get('SpiffyUser\ModuleOptions');
        $uriContainer = new Container('uri');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $uri = $request->getQuery()->toArray();

        $return = false;
        if (isset($uri['return']) && !parse_url($uri['return'], PHP_URL_SCHEME)) {
            $return = rawurldecode($uri['return']);
        } elseif (isset($uriContainer->return)) {
            $return = $uriContainer->return;
        }

        $ext = $this->getAuthExtension();

        if ($this->identity()) {
            $current = sprintf('//%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);

            if ($options->getLoginRedirect() && $return && !strstr($return, $current)) {
                return $this->redirect()->toUrl($return);
            } else {
                return $this->redirect()->toRoute('spiffy_user');
            }
        }

        $prg = $this->prg();
        $form = $this->getAuthExtension()->getLoginForm();
        $params = array('form' => $form, 'result' => null);

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $result = $ext->login($prg);
            if ($result->isValid()) {
                if ($options->getLoginRedirect() && $return) {
                    return $this->redirect()->toUrl($return);
                } else {
                    return $this->redirect()->toRoute('spiffy_user');
                }
            }
            $params['result'] = $result;
        }
        return $params;
    }

    /**
     * @return \SpiffyUser\Extension\Authentication
     */
    public function getAuthExtension()
    {
        return $this->plugin('zfcUserExtension')->get('authentication');
    }
}
