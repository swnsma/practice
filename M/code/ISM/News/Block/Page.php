<?php
class ISM_News_Block_Page extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        $collection = Mage::getModel('ism_news/list')->getCollection();
        $this->setDatasets($collection);

        return parent::_toHtml();
    }
}