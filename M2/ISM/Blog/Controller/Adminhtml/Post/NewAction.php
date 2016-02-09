<?php
namespace ISM\Blog\Controller\Adminhtml\Post;
use \Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\ForwardFactory;

class NewAction extends Action
{
    protected $resultForwardFactory;

    public function __construct(
        Action\Context $context,
        ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ISM_Blog::save');
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}