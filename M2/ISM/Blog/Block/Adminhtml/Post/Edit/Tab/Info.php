<?php
namespace ISM\Blog\Block\Adminhtml\Post\Edit\Tab;


use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;


class Info extends Generic implements TabInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        Config $wysiwygConf,
        FormFactory $formFactory,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConf;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('blog_post');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Post Title'), 'title' => __('Post Title'), 'required' => true]
        );
        $fieldset->addField(
            'url_key',
            'text',
            [
                'name'     => 'url_key',
                'label'    => __('URL Key'),
                'title'    => __('URL Key'),
                'required' => true,
                'class'    => 'validate-xml-identifier'
            ]
        );
        $fieldset->addField(
            'post_image',
            'image',
            [
                'name'     => 'img',
                'label'    => __('URL Key'),
                'title'    => __('URL Key'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label'    => __('Status'),
                'title'    => __('Status'),
                'name'     => 'is_active',
                'required' => true,
                'options'  => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $fieldset->addField(
            'description',
            'editor',
            [
                'name'     => 'description',
                'label'    => __('Content'),
                'title'    => __('Content'),
                'style'    => 'height:36em',
                'required' => true,
                'config'   => $this->_wysiwygConfig->getConfig()
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return __('Post Info');
    }

    public function getTabTitle()
    {
        return __('Post Info');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}