<?php

class ISM_News_Block_Adminhtml_News_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('news_form', array('legend' => Mage::helper('ism_news')->__('Item information')));

        $form->setHtmlIdPrefix('modulename');
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
        array(
            'tab_id' => 'form_section',
            'add_widgets' => false,
            'add_variables' => false,
            'add_images' => false,
        ));

        $fieldset->addField(
            'title',
            'text',
            array(
                'label' => Mage::helper('ism_news')->__('Title'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'title',
            ));

        $fieldset->addField(
            'content',
            'editor',
            array(
                'name' => 'content',
                'label' => Mage::helper('ism_news')->__('Content'),
                'title' => Mage::helper('ism_news')->__('Content'),
                'style' => 'width: 98%; height: 300px',
                'wysiwyg' => true,
                'required' => true,
                'config' => $wysiwygConfig,
            ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );

        $fieldset->addField(
            'publish_date',
            'date',
            array(
                'label'        => Mage::helper('ism_news')->__('Date'),
                'name'         => 'publish_date',
                'time' => true,
                'image'        => $this->getSkinUrl('images/grid-cal.gif'),
                'format'       => $dateFormatIso
            ));


        $fieldset->addField(
            'announce',
            'editor',
            array(
                'name' => 'announce',
                'label' => Mage::helper('ism_news')->__('Announce'),
                'title' => Mage::helper('ism_news')->__('Announce'),
                'style' => 'width: 98%; height: 200px',
                'required' => false,
            ));

        $fieldset->addField(
            'published',
            'select',
            array(
                'label' => Mage::helper('ism_news')->__('Published'),
                'name' => 'published',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('ism_news')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('ism_news')->__('No'),
                    ),
                ),
            ));

        if( Mage::getSingleton('adminhtml/session')->getNewsData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
            Mage::getSingleton('adminhtml/session')->setNewsData(null);
        } elseif ( Mage::registry('news_data') ) {
            $form->setValues(Mage::registry('news_data')->getData());
        }
        return parent::_prepareForm();
    }

}