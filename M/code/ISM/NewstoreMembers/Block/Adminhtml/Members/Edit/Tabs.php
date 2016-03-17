<?php

class ISM_NewstoreMembers_Block_Adminhtml_Members_Edit_Tabs extends  Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('members_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ism_newstore_members')->__('Members Information'));
    }
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_section',
            array(
                'label' => Mage::helper('ism_newstore_members')->__('Member Information'),
                'title' => Mage::helper('ism_newstore_members')->__('Member Information'),
                'content' => $this->getLayout()->createBlock('ism_newstore_members/adminhtml_members_edit_tab_form')->toHtml(),
            ));

        return parent::_beforeToHtml();
    }
}