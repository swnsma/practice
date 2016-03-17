<?php
class ISM_NewstoreMembers_Block_Adminhtml_Members extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_members';
        $this->_blockGroup = 'ism_newstore_members';
        $this->_headerText = Mage::helper('ism_newstore_members')->__('Members Manager');
        $this->_addButtonLabel = Mage::helper('ism_newstore_members')->__('Invite New Member');
        parent::__construct();
    }
}