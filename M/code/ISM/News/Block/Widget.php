<?php
class ISM_News_Block_Widget extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{

    protected function _toHtml()
    {
        $datasets=Mage::getModel('ism_news/list')->getPublishedNews($this->getData('format'));
        $this->setDatasets(($datasets));

        return parent::_toHtml();
    }
}