<?php
namespace ISM\Blog\Controller\View;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;

class Index extends Action
{
    /**
     * @var ForwardFactory
     */
    protected $_forwardFactory;

    public function __construct(Context $context, ForwardFactory $forwardFactory)
    {
        $this->_forwardFactory = $forwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id', $this->getRequest()->getParam('id', false));

        /** @var \ISM\Blog\Helper\Post $helper */
        $helper = $this->_objectManager->get('ISM\Blog\Helper\Post');
        $result = $helper->prepareResultPost($this, $id);

        if (!$result) {
            /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
            $resultForward = $this->_forwardFactory->create();
            return $resultForward->forward('noroute');
        }

        return $result;
    }
}