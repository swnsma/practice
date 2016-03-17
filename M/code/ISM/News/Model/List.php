<?php
class ISM_News_Model_List extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ism_news/news');
    }

    public function getPublishedNews($limit)
    {
        $collection = $this->getCollection()->addFieldToFilter('published', true);
        $collection->getSelect()->limit($limit);

        return $collection;
    }

    public function getNews($key='', $index=null)
    {
        $item = parent::getData($key, $index);

        if($item['published'])
        {
            return $item;
        }

        return false;
    }
}