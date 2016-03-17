<?php
class ISM_News_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function detailAction()
    {
        $newId = (int) $this->getRequest()->getParam('id');
        $news = Mage::getModel('ism_news/list')->load($newId)->getNews();

        if(!$news) {
            $this->_forward('defaultNoRoute');
        } else {
            $this->loadLayout();
            $this->renderLayout();
        }
    }
}