<?php
class ISM_NewstoreMembers_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('newstore_members')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Import Prices'),
                Mage::helper('adminhtml')->__('Import Prices'));

        return $this;
    }
    public function indexAction()
    {
        $this->_title("Import Newstore Member Prices");
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ism_newstore_members/adminhtml_import_edit'));
        $this->renderLayout();
    }

    public function importAction()
    {
        try{
            Mage::getModel('ism_newstore_members/import')->upload()
                ->import();

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ism_newstore_members')->__('Import finished'));
            $this->_redirectReferer();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e);
        }
    }
}