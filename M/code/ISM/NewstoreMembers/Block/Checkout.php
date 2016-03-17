<?php

class ISM_NewstoreMembers_Block_Checkout extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        $member = Mage::getModel('ism_newstore_members/members');
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $this->setCustomerId($customerData->getId());
        $this->setIsMember($member->isMemberExists($this->getCustomerId()));

        if ($this->getIsMember()) {

            $member->load($this->getCustomerId(), 'customer_id');
            $this->setExpireDate($member->getExpireDate());
            $this->setPostCode($member->getPostCode());
            $this->setCode($member->getUniqueKey());
            return parent::_toHtml();
        }
    }
}