<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $spec = Zend_Json_Decoder::decode($this->_getParam('spec'));
        $data = Zend_Json_Decoder::decode($this->_getParam('data'));

        $this->view->assign('spec', $spec);
        $this->view->assign('data', $data);

    }


}
