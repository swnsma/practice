<?php
namespace ISM\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ADMIN_RESOURCE = 'ISM_Blog::post';

    protected $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('ISM_Blog::post');
        $resultPage->addBreadcrumb(__('Blog Posts'), __('Blog Posts'));
        $resultPage->addBreadcrumb(__('Manage Blog Posts'), __('Manage Blog Posts'));
        $resultPage->getConfig()->getTitle()->prepend(__('Blog Posts'));

        return $resultPage;
    }
}