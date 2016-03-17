<?php

class ISM_News_Model_Resource_News_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ism_news/list');
    }
}