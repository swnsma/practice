<?php

class ISM_News_Block_Adminhtml_News_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('newsGrid');
        //primary key of table
        $this->setDefaultSort('news_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {

        $collection = Mage::getModel('ism_news/list')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'news_id',
            array(
                'header' => Mage::helper('ism_news')->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'type' => 'number',
                'index' => 'news_id',
            ));
        $this->addColumn(
            'title',
            array(
                'header' => Mage::helper('ism_news')->__('Title'),
                'index' => 'title',
            ));
        $this->addColumn(
            'content',
            array(
                'header' => Mage::helper('ism_news')->__('Content'),
                'index' => 'content',
            ));
        $this->addColumn(
            'announce',
            array(
                'header' => Mage::helper('ism_news')->__('Announce'),
                'index' => 'announce',
            ));
        $this->addColumn(
            'publish_date',
            array(
                'header'    => Mage::helper('ism_news')->__('Date'),
                'index'     => 'publish_date',
                'type'      => 'datetime',
            ));
        $this->addColumn(
            'published',
            array(
                'header' => Mage::helper('ism_news')->__('Published'),
                'index' => 'published',
                'type' => 'options',
                'options'=>array(
                    0 => 'Not published',
                    1 => 'Published'
                ),
            ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction(){

        $this->setMassactionIdField('news_listing_id');
        $this->getMassactionBlock()->setFormFieldName('news_id');
        $this->getMassactionBlock()->setUseSelectAll(true);

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => $this->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => $this->__('Are you sure to delete the selected items?'),
            ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));

    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}