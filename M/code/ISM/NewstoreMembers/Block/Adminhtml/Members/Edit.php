<?php

class ISM_NewstoreMembers_Block_Adminhtml_Members_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId='id';
        $this->_blockGroup='ism_newstore_members';
        $this->_controller = 'adminhtml_members';

        $this->_updateButton('save', 'label', Mage::helper('ism_newstore_members')->__('Save Member'));
        $this->_updateButton('delete', 'label', Mage::helper('ism_newstore_members')->__('Delete Member'));
    }

    public function getHeaderText()
    {
        if (Mage::registry('member_data') && Mage::registry('member_data')->getId() ) {
            return Mage::helper('ism_newstore_members')->__("Edit Member '%s'", $this->htmlEscape(Mage::registry('member_data')->getFullName()));
        } else {
            return Mage::helper('ism_newstore_members')->__('Add Member');
        }
    }
}