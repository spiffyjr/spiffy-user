<?php

namespace SpiffyUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
    /**
     * @return array|\Zend\Http\Response
     */
    public function indexAction()
    {
        if (!$this->identity()) {
            return $this->redirect()->toRoute('zfc_user/login');
        }
        return array();
    }
}