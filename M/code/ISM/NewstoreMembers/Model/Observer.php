<?php
class ISM_NewstoreMembers_Model_Observer
{
    public function newstoreMemberCheck(Varien_Event_Observer $observer)
    {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();

            if (Mage::helper('ism_newstore_members')->isMemberValid($customerId)) {

                $customer = Mage::getModel('ism_newstore_members/members')
                    ->load($customerId, 'customer_id');

                if ( Mage::helper('ism_newstore_members')->isMemberAddress($customerId)) {

                    $observer->getQuote()->setNewstoreMemberCode($customer->getUniqueKey());
                    return;
                }
            }
        }
        $observer->getQuote()->setNewstoreMemberCode(null);
    }
}