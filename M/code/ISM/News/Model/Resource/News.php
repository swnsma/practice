<?php

class ISM_News_Model_Resource_News extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('ism_news/news', 'news_id');
    }
}