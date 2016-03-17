<?php

class ISM_NewstoreMembers_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function notMembersAsOptionalArray()
    {
        $membersIds = Mage::getModel('ism_newstore_members/members')->getCustomersIdAsArray();
        $collection = Mage::getModel('customer/customer')
            ->getCollection()
            ->addNameToSelect()
            ->addAttributeToFilter(
                'entity_id',
                array('nin' => $membersIds['items']));
        $values = array();

        if (!isset($collection)) {
            $values[] = array('label' => 'NONE', 'value' => 0 );
        }

        foreach ($collection as $item) {
            $values[] = array('label' => $item->getName(), 'value' => $item->getId());
        }

        return $values;
    }

    public function isCodeUnique($code, $customerId)
    {
        $model = Mage::getModel('ism_newstore_members/members')
            ->getCollection()
            ->addFieldToFilter('customer_id', array('neq' => $customerId))
            ->addFieldToFilter('unique_key', array('eq' => $code));

        return !($model->getFirstItem()->getData());
    }

    public function isMemberValid($customerId)
    {
        $member = Mage::getModel('ism_newstore_members/members');
        return $member->isMemberExists($customerId) &&
            $member->isMemberDateValid($customerId) &&
            $member->isMemberActive($customerId);
    }

    public function getNewstoreMembersGroupId()
    {
        return Mage::getStoreConfig('ism_newstore_members/newstore_members_group');
    }


    public function isMemberAddress($customerId)
    {
        $quote = Mage::getModel('checkout/cart')->getQuote();
        $customer = Mage::getModel('ism_newstore_members/members')->load($customerId, 'customer_id');
        if (!$customer->getPostCode()) {
            return true;
        }
        $billingPostcode = $quote->getBillingAddress()->getPostcode();
        $shippingPostcode = $quote->getShippingAddress()->getPostocde();

        if (is_null($billingPostcode) || is_null($shippingPostcode)) {
            return true;
        }
        return $billingPostcode == $shippingPostcode &&
        $shippingPostcode == $customer->getPostCode();

    }

}