<?php
class ISM_NewstoreMembers_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();

    }

    public function preDispatch()
    {
        parent::preDispatch();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if(!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }
    public function activateAction()
    {
        $post = $this->getRequest();

        if ($post->getPost() && Mage::getSingleton('customer/session')->isLoggedIn()) {

            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $member = Mage::getModel('ism_newstore_members/members')
                ->loadByField('customer_id', $customerData->getId());

            if($post->get('code') == $member->getUniqueKey()){
                Mage::getModel('ism_newstore_members/members')
                    ->activate($customerData->getId());

            } else {
                Mage::getSingleton('core/session')->addError('Invalid code.');
            }
        }

        $this->_redirectReferer();
    }
}