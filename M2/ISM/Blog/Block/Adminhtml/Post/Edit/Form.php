<?php

namespace ISM\Blog\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Form extends Generic
{
    protected $_systemStore;
    protected $_wysiwygConfig;

    /**
     * Form constructor.
     *
     * @param Context     $context
     * @param Registry    $registry
     * @param FormFactory $formFactory
     * @param array       $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = [])
    {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('post_form');
        $this->setTitle(__('Post Information'));
    }

    protected function _prepareForm()
    {
        $form = $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}