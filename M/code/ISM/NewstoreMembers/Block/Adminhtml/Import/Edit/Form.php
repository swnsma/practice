<?php
class ISM_NewstoreMembers_Block_Adminhtml_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/import'),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'));

        $fields = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('ism_newstore_members')->__('Import Settings')));
        $fields->addField(
            Mage_ImportExport_Model_Import::FIELD_NAME_SOURCE_FILE,
            'file',
            array(
                'name' => Mage_ImportExport_Model_Import::FIELD_NAME_SOURCE_FILE,
                'title' => Mage::helper('ism_newstore_members')->__('Select File'),
                'label' => Mage::helper('ism_newstore_members')->__('Select File'),
                'required' => true,
            ));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}