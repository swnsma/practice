<?php
namespace ISM\Blog\Model\ResourceModel;

use ISM\Blog\Api\Data\PostInterface;
use ISM\Blog\Api\PostRepositoryInterface;
use ISM\Blog\Model\PostFactory;
use ISM\Blog\Model\PostRegistry;
use ISM\Blog\Model\ResourceModel\Post as PostResourceModel;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\InputException;

class PostRepository implements PostRepositoryInterface
{
    /** @var PostFactory  */
    protected $_postFactory;

    /** @var PostRegistry  */
    protected $_postRegistry;

    /** @var PostResourceModel */
    protected $_resourceModel;

    protected $_eventManager;

    protected $_extensibleDataObjectConverter;

    protected $_searchResultFactory;

    public function __construct(
        PostRegistry $postRegistry,
        PostFactory $postFactory,
        PostResourceModel $resourceModel,
        ManagerInterface $eventManager,
        \ISM\Blog\Api\Data\PostSearchResultsInterfaceFactory $searchResultFactory,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    )
    {
        $this->_searchResultFactory = $searchResultFactory;
        $this->_extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->_resourceModel = $resourceModel;
        $this->_postRegistry = $postRegistry;
        $this->_postFactory = $postFactory;
        $this->_eventManager = $eventManager;
    }

    public function get($id)
    {
        $postModel = $this->_postRegistry->retrieve($id);
        return $postModel->getDataModel();
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \ISM\Blog\Api\Data\PostSearchResultInterface $searchResult */
        $searchResult = $this->_searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);

        $collection = $this->_postFactory->create()->getCollection();
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $searchResult->setTotalCount($collection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
           foreach ($sortOrders as $sortOrder) {
               $collection->addOrder(
                   $sortOrder->getField(),
                   ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
               );
           }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $posts = [];
        foreach ($collection as $postModel) {
            $posts[] = $postModel->getDataModel();
        }
        $searchResult->setItems($posts);
        return $searchResult;
    }

    public function getByUrl($url)
    {
        $postModel = $this->_postRegistry->retrieveByUrl($url);
        return $postModel->getDataModel();
    }

    public function delete(\ISM\Blog\Api\Data\PostInterface $post)
    {
        return $this->deleteById($post->getId());
    }

    public function deleteById($id)
    {
        $postModel = $this->_postRegistry->retrieve($id);
        $postModel->delete();
        $this->_postRegistry->remove($id);
        return true;
    }

    public function save(PostInterface $post)
    {
        $this->validate($post);

        $postData = $post->getData();
        $postModel = $this->_postFactory->create();
        $postModel->load($post->getId());
        $postModel->setData($postData);
        $postModel->save();
        $this->_postRegistry->push($postModel);
        $savedPost = $this->getByUrl($post->getUrlKey());
        $this->_eventManager->dispatch(
            'post_save_after_data_object',
            ['post_data_object' => $savedPost, 'orig_post_data_object' => $post ]
        );
        return $savedPost;

    }

    private function validate(PostInterface $post)
    {
        $exception = new InputException();

        if (!\Zend_Validate::is(trim($post->getTitle()), 'NotEmpty')) {
            $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'title']));
        }

        if (!\Zend_Validate::is(trim($post->getUrlKey()), 'NotEmpty')) {
            $exception->addError(__(InputException::REQUIRED_FIELD, ['fieldName' => 'url_key']));
        }
        $id = $this->_resourceModel->checkUrlKey($post->getUrlKey());

        if ($id && $id != $post->getId()) {
            $exception->addError(__(InputException::DEFAULT_MESSAGE));
        }

        if ($exception->wasErrorAdded()) {
            throw $exception;
        }
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \ISM\Blog\Model\ResourceModel\Post\Collection $collection
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \ISM\Blog\Model\ResourceModel\Post\Collection $collection
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = array($condition => $filter->getValue());
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }
}