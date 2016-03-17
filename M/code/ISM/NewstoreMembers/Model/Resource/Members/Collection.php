<?php

class ISM_NewstoreMembers_Model_Resource_Members_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ism_newstore_members/members');
    }

    public function members()
    {
        $firstName = Mage::getModel('eav/entity_attribute')
            ->loadByCode('1', 'firstname');
        $lastName = Mage::getModel('eav/entity_attribute')
            ->loadByCode('1', 'lastname');

        $this->getSelect()
            ->columns(new Zend_Db_Expr("CONCAT(`cev1`.`value`, ' ',"
                . "`cev2`.`value`) AS fullname"))
            ->joinLeft(array('ce' => 'customer_entity'),
                'ce.entity_id=main_table.customer_id',
                array('email' => 'email'))
            ->joinRight(array('cev1' => 'customer_entity_varchar'),
                'cev1.entity_id=main_table.customer_id',
                array('firstname' => 'value'))
            ->joinRight(array('cev2' => 'customer_entity_varchar'),
                'cev2.entity_id=main_table.customer_id',
                array('lastname' => 'value'))
            ->where('cev1.attribute_id=' . $firstName->getAttributeId())
            ->where('cev2.attribute_id=' . $lastName->getAttributeId());

        return $this;
    }
    public function customersId()
    {
        $this->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('customer_id');
        return $this;
    }

}