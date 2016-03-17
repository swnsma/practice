<?php
class ISM_News_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getNewsUrl($news)
    {
        if ($news) {
            return $this->_getUrl('news/index/detail', array('id' => $news));
        }

        return false;
    }

    public function getListNewsUrl()
    {
        return $this->_getUrl('news/');
    }
}