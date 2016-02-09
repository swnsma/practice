<?php

namespace ISM\Blog\Controller\Adminhtml\Post;
use ISM\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


abstract class MassActionAbstract extends Action
{
    const REDIRECT_URL = '*/*/';
    /** @var CollectionFactory  */
    protected $collectionFactory;

    /** @var Filter */
    protected $filter;

    /** @var  string */
    protected $_message;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    /**
     * Execute controller.
     *
     * @return $this
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        try {
            $this->_allAction($collection);
        } catch(\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        /** @var  \Magento\Backend\Model\View\Result\Redirect  $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath(static::REDIRECT_URL);
    }

    /**
     * Process all selected posts.
     *
     * @param AbstractCollection $collection
     */
    protected function _allAction(AbstractCollection $collection)
    {
        $count = $this->_processAction($collection);

        $this->setSuccessMessage($count);
    }

    abstract protected function _processAction(AbstractCollection $collection);

    /**
     * Add success message.
     *
     * @param $count
     */
    protected function setSuccessMessage($count)
    {
        $this->messageManager->addSuccess(__($this->_message, $count));
    }
}