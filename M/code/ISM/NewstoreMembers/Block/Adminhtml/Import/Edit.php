<?php
class ISM_NewstoreMembers_Block_Adminhtml_Import_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->removeButton('reset');
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_blockGroup='ism_newstore_members';
        $this->_controller = 'adminhtml_import';
    }
    public function getHeaderText()
    {
        return Mage::helper('ism_newstore_members')->__('Import Prices For Newstore Members');
    }
}