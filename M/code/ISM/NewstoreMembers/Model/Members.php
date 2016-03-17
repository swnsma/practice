<?php
class ISM_NewstoreMembers_Model_Members extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ism_newstore_members/members');
    }
    public function getCustomersIdAsArray()
    {
        return $this->getCollection()->customersId()->toArray();
    }

    public function loadByField($field, $value, $condition = '=')
    {
        return $this->setData($this->getResource()->loadByField($field, $value, $condition));
    }

    public function isMemberExists($customerId)
    {
        return (bool)($this->load($customerId, 'customer_id')->getId());
    }
    public function activate($customerId)
    {
        $this->load($customerId, 'customer_id')
            ->setActivated(1)
            ->save();
    }
    public function isMemberDateValid($customerId)
    {
        $dateModel = Mage::getModel('core/date');
        $this->load($customerId, 'customer_id');
        return $dateModel->timestamp($this->getExpireDate()) >
            $dateModel->timestamp(time());
    }

    public function isMemberActive($customerId)
    {
        return (bool)($this->load($customerId, 'customer_id')->getActivated());
    }
}