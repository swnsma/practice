<?php
class ISM_NewstoreMembers_Model_Product extends Mage_Catalog_Model_Product
{
    public function getFinalPrice($qty=null)
    {

        if (Mage::getSingleton('customer/session')->isLoggedIn()){

            $helper = Mage::helper('ism_newstore_members');
            $price = Mage::getModel('catalog/product')->load($this->getId())->_getData('ism_newstoremembers_price');
            $customerId = Mage::getSingleton('customer/session')->getId();

            if ($price !== null &&
                $helper->isMemberValid($customerId) &&
                 $helper->isMemberAddress($customerId)) {
                   return $price;
            }

        }
        return parent::getFinalPrice($qty);
    }
}