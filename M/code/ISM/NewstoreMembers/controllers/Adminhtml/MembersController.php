<?php
class ISM_NewstoreMembers_Adminhtml_MembersController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('newstore_members')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Members Manager'),
                Mage::helper('adminhtml')->__('Members Manager'));

        return $this;
    }

    public function indexAction()
    {
        $this->_title("Newstore Members");
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ism_newstore_members/adminhtml_members'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $member = Mage::getModel('ism_newstore_members/members')->load($id);
        if ($id == 0 || $member->getId()) {
           Mage::register('member_data', $member);

            $this->loadLayout();
            $this->_setActiveMenu('newstore_members');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Members Manager'),
                Mage::helper('adminhtml')->__('Members Manager'));
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Member'),
                Mage::helper('adminhtml')->__('Member'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent(
                    $this->getLayout()
                    ->createBlock('ism_newstore_members/adminhtml_members_edit'))
                ->_addLeft(
                    $this->getLayout()
                    ->createBlock('ism_newstore_members/adminhtml_members_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('ism_newstore_members')->__('Member does not exists'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {

            try{
                $postData = $this->getRequest();
                $member = Mage::getModel('ism_newstore_members/members');
                $id = $this->getRequest()->getParam('id');

                $code = $postData->get('unique_code');
                if (!$code) {
                    $code = Mage::helper('core')->getRandomString(10);
                }

                $customerId = $postData->get('customer_id');
                $postCode = $postData->get('post_code');
                $expireDate = $postData->get('expire_date');

                if (Mage::helper('ism_newstore_members')->isCodeUnique($code, $customerId)) {
                    $member->load($id)
                        ->setCustomerId($customerId)
                        ->setPostCode($postCode)
                        ->setExpireDate($expireDate)
                        ->setUniqueKey($code)
                        ->save();
                    if (!isset($id)) {
                    $customer =  Mage::getModel('customer/customer')->load($customerId);

                    $customer->setPrevGroupId($customer->getGroupId())
                        ->setGroupId(Mage::helper('ism_newstore_members')->getNewstoreMembersGroupId())
                        ->save();
                    }

                    Mage::getSingleton('adminhtml/session')
                        ->addSuccess(
                            Mage::helper('adminhtml')->__('Item was successfully saved.'));
                    Mage::getSingleton('adminhtml/session')
                        ->setMemberData(false);

                    $this->_redirect('*/*/');
                    return;
                } else {
                    throw new Exception('Newstore Member Code is not unique! Enter unique code or leave this field empty. If you leave field empty, the Newstore Member Code will be created automatically.');
                }
            } catch(Exception $e) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')
                    ->setMemberData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {

           try{
               $member = Mage::getModel('ism_newstore_members/members');
               $id = $this->getRequest()->getParam('id');
               $customer = Mage::getModel('customer/customer')
                   ->load($member->load($id)->getCustomerId());

               $customer->setGroupId($customer->getPrevGroupId())
                   ->save();

               $member->setId($id)->delete();

               Mage::getSingleton('adminhtml/session')
                   ->addSuccess(Mage::helper('adminhtml')->__('Member was successfully removed.'));
           } catch (Exception $e) {
               Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
               $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
           }
        }

        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $list = $this->getRequest()->getParam('id');
        $member = Mage::getModel('ism_newstore_members/members');

        if( is_array($list) && sizeof($list)) {
            try{
                foreach ($list as $memberId)
                {
                    $customer = Mage::getModel('customer/customer')
                        ->load($member->load($memberId)->getCustomerId());
                    $customer->setGroupId($customer->getPrevGroupId())
                        ->save();
                    $member->setId($memberId)->delete();
                }

                $this->_getSession()
                    ->addSuccess($this->__('Members have been successfully removed.'));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($this->__('Please, select members'));
        }

        $this->_redirect('*/*/');
    }
}