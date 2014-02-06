<?php

namespace SpiffyUser\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

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
        return $this->redirect()->toRoute('spiffy_user/login');
    }

    /**
     * @return array|Response
     */
    public function loginAction()
    {
        $ext = $this->getAuthExtension();

        if ($this->identity()) {
            return $this->redirect()->toRoute('spiffy_user');
        }
        $prg  = $this->prg();
        $form = $this->getAuthExtension()->getLoginForm();
        $params = array('form' => $form, 'result' => null);

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $result = $ext->login($prg);
            if ($result->isValid()) {
                return $this->redirect()->toRoute('spiffy_user');
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
