<?php

class ISM_NewstoreMembers_Block_Adminhtml_Members_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getURl(
                    '*/*/save',
                    array ('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
            ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}