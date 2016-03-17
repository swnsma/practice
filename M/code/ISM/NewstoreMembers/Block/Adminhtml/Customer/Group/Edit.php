<?php
class ISM_NewstoreMembers_Block_Adminhtml_Customer_Group_Edit extends Mage_Adminhtml_Block_Customer_Group_Edit
{
    public function __construct()
    {
        parent::__construct();
        if (Mage::registry('current_group')->getId() ==
            Mage::helper('ism_newstore_members')->getNewstoreMembersGroupId()) {

            $this->_removeButton('delete');
        }
    }
}