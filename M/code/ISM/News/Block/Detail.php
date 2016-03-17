<?php
class ISM_News_Block_Detail extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        $newId = (int) $this->getRequest()->getParam('id');
        $news = Mage::getModel('ism_news/list')->load($newId)->getNews();

        $this->setTitle($news['title']);
        $this->setContent($news['content']);
        $this->setAnnounce($news['announce']);
        $this->setDate($news['publish_date']);

        return parent::_toHtml();
    }
}