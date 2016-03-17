<?php

class ISM_NewstoreMembers_Block_Adminhtml_Members_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('membersGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ism_newstore_members/members')->getCollection()->members();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'header' => Mage::helper('ism_newstore_members')->__('ID'),
                'align' => 'right',
                'width' => '50px',
                'type' => 'number',
                'index' => 'id',
            ));
        $this->addColumn(
            'label',
            array(
                'header' => Mage::helper('ism_newstore_members')->__('Name'),
                'index' => 'fullname',
            ));
        $this->addColumn(
            'unique_key',
            array(
                'header' => Mage::helper('ism_newstore_members')->__('Newstore Member Code'),
                'index' => 'unique_key',
            ));
        $this->addColumn(
            'email',
            array(
                'header' => Mage::helper('ism_newstore_members')->__('Email'),
                'index' => 'email',
            ));
        $this->addColumn(
            'post_code',
            array(
                'header' => Mage::helper('ism_newstore_members')->__('Post Code'),
                'index' => 'post_code',
            ));
        $this->addColumn(
            'expire_date',
            array(
                'header' => Mage::helper('ism_newstore_members')->__('Expire date'),
                'type' => 'date',
                'index' => 'expire_date',
            ));
        $this->addColumn(
            'activated',
            array(
                'header' => Mage::helper('ism_newstore_members')->__('Activated'),
                'index' => 'activated',
                'type' => 'options',
                'options' => array(
                    0 => 'Not active',
                    1 => 'Activated',
                ),
            ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('members_listing_id');
        $this->getMassactionBlock()->setFormFieldName('id');
        $this->getMassactionBlock()->setUseSelectAll(true);

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => Mage::helper('ism_newstore_members')->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => $this->__('Are you sure to remove selected members from group?'),
            ));

        return $this;
    }
    public function getRowUrl($model)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $model->getId(),
        ));
    }
}