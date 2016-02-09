<?php
namespace ISM\Blog\Controller\Adminhtml\Post;

use ISM\Blog\Model\PostFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    protected $_coreRegistry;

    protected $_resultPageFactory;

    protected $_postFactory;

    /**
     * Edit constructor.
     *
     * @param Action\Context $context
     * @param PageFactory    $resultPageFactory
     * @param Registry       $registry
     * @param PostFactory    $postFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        PostFactory $postFactory
    ) {
        $this->_postFactory = $postFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Check access.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ISM_Blog::save');
    }

    /**
     * Init menu.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('ISM_Blog::post')
            ->addBreadcrumb(__('Blog'), __('Blog'))
            ->addBreadcrumb(__('Manage Blog Posts'), __('Manage Blog Posts'));
        return $resultPage;
    }

    /**
     * Execute edit action.
     *
     * @return $this|\Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \ISM\Blog\Model\Post $model */
        $model = $this->_postFactory->create();

        if ($id) {
            $model->load($id);
            if(!$model->getId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data =  $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('blog_post', $model);

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Blog Post') : __('New Blog Post'),
            $id ? __('Edit Blog Post') : __('New Blog Post')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Blog Post'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId()? $model->getTitle() : __('New Blog Post'));

        return $resultPage;
    }
}