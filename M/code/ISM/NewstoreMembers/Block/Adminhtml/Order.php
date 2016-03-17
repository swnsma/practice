<?php
class ISM_NewstoreMembers_Block_Adminhtml_Order extends Mage_Core_Block_Template
{
    public function getCode()
    {
        return Mage::getModel('sales/order')
            ->load($this->getOrder()->getId())
            ->getNewstoreMemberCode();
    }
    public function getOrder()
    {
        return Mage::registry('current_order');
    }
}