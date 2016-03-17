<?php

class ISM_NewstoreMembers_Model_Resource_Members extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('ism_newstore_members/newstore_members', 'id');
    }

    public function loadByField($field, $value, $condition = '=')
    {
        $table = $this->getMainTable();
        $where = $this->_getReadAdapter()->quoteInto("$field $condition ?", $value);
        $select = $this->_getReadAdapter()
            ->select()
            ->from($table)
            ->where($where);
        return $this->_getReadAdapter()->fetchRow($select);
    }
}