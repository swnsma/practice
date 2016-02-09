<?php
namespace ISM\Blog\Block\Adminhtml\Post;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

class Edit extends Container
{
    protected $_coreRegistry;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'ISM_Blog';
        $this->_controller = 'adminhtml_post';

        parent::_construct();

        if($this->_isAllowedAction('ISM_Blog::save')) {
            $this->buttonList->update('save', 'label', __('Save Blog Post'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if($this->_isAllowedAction('ISM_Blog::post_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Post'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('blog_post')->getId()) {
            return __("Edit Post '%1'", $this->escapeHtml($this->_coreRegistry->registry('blog_post')->getTitle()));
        } else {
            return __('New Post');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('blog/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }

    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
        function toggleEditor() {
            if(tinyMCE.getInstanceById('description') == null) {
                tinyMCE.execCommand('mceAddControl', false, 'description');
            } else {
                tinyMCE.execCommand('mceRemoveControl', false, 'description');
            }
        };
        ";
        return parent::_prepareLayout();
    }
}