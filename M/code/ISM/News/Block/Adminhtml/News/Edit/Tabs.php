<?php

class ISM_News_Block_Adminhtml_News_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('news_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ism_news')->__('News Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_section',
            array(
                'label' => Mage::helper('ism_news')->__('Item Information'),
                'title' => Mage::helper('ism_news')->__('Item Information'),
                'content' => $this->getLayout()->createBlock('ism_news/adminhtml_news_edit_tab_form')->toHtml(),
            ));

        return parent::_beforeToHtml();
    }
}