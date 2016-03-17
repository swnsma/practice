<?php

class ISM_NewstoreMembers_Block_Adminhtml_Members_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $helper = Mage::helper('ism_newstore_members');
        $this->setForm($form);
        $fields = $form->addFieldset(
            'members_form',
            array('legend' => $helper->__('Member Information')));

        $form->setHtmlPrefix('modulename');

        $fields->addField(
            'unique_key',
            'text',
            array(
                'label' => $helper->__('Newstore Member Code'),
                'name' => 'unique_key',
            ));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );

        $fields->addField(
            'expire_date',
            'date',
            array(
                'label' => $helper->__('Expire Date'),
                'name' => 'expire_date',
                'class' => 'required-entry',
                'required' => true,
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => $dateFormatIso,
            ));
        $fields->addField(
            'post_code',
            'text',
            array(
                'label' => $helper->__('Post Code'),
                'name' => 'post_code',
            ));

        $membersList = $helper->notMembersAsOptionalArray();
        if (Mage::registry('member_data')->getData() || Mage::getSingleton('adminhtml/session')->getMemberData()) {

            if (Mage::registry('member_data')) {
                $data = Mage::registry('member_data');
            } else {
                $data = Mage::getSingleton('adminhtml/session')->getMemberData();
            }
            $member = Mage::getModel('customer/customer')
                ->load($data->getCustomerId());
            $data->setFullname($member->getFirstname().' '.$member->getLastname());
            $membersList[] = array(
                'label' => $data->getFullname(),
                'value' => $data->getCustomerId());
        }
        $fields->addField(
            'customer_id',
            'select',
            array(
                'label' => $helper->__('Customer'),
                'name' => 'customer_id',
                'values' => $membersList,
            ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}