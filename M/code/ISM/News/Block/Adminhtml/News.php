<?php

class ISM_News_Block_Adminhtml_News extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _prepareLayout()
    {
        $return = parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
        return $return;
    }
    public function __construct()
    {
        $this->_controller = 'adminhtml_news';
        $this->_blockGroup = 'ism_news';
        $this->_headerText = Mage::helper('ism_news')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('ism_news')->__('Add Item');
        parent::__construct();
    }
}