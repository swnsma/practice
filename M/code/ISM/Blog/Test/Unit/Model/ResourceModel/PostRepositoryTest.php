<?php
namespace ISM\Blog\Test\Unit\Model\ResourceModel;

class PostRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \ISM\Blog\Model\PostFactory| \PHPUnit_Framework_MockObject_MockObject  */
    protected $_postFactory;
    /** @var \ISM\Blog\Model\PostRegistry| \PHPUnit_Framework_MockObject_MockObject  */
    protected $_postRegistry;
    /** @var \ISM\Blog\Model\ResourceModel\Post| \PHPUnit_Framework_MockObject_MockObject  */
    protected $_resourceModel;
    /** @var \Magento\Framework\Event\ManagerInterface| \PHPUnit_Framework_MockObject_MockObject  */
    protected $_eventManager;
    /** @var \Magento\Framework\Api\ExtensibleDataObjectConverter| \PHPUnit_Framework_MockObject_MockObject  */
    protected $_extensibleDataObjectConverter;
    /** @var \ISM\Blog\Api\Data\PostSearchResultsInterfaceFactory| \PHPUnit_Framework_MockObject_MockObject  */
    protected $_searchResultFactory;
    /** @var \ISM\Blog\Api\Data\PostInterface| \PHPUnit_Framework_MockObject_MockObject  */
    protected $_post;
    /** @var \ISM\Blog\Model\ResourceModel\PostRepository| \PHPUnit_Framework_MockObject_MockObject  */
    protected $model;

    public function setUp()
    {
        $this->_resourceModel = $this->getMock('ISM\Blog\Model\ResourceModel\Post', [], [], '', false);
        $this->_postRegistry = $this->getMock('ISM\Blog\Model\PostRegistry', [], [], '', false);
        $this->_postFactory = $this->getMock('ISM\Blog\Model\PostFactory', ['create'], [], '', false);
        $this->_searchResultFactory = $this->getMock(
            'ISM\Blog\Api\Data\PostSearchResultsInterfaceFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->_extensibleDataObjectConverter = $this->getMock(
            'Magento\Framework\Api\ExtensibleDataObjectConverter',
            [],
            [],
            '',
            false
        );
        $this->_eventManager = $this->getMockForAbstractClass(
            'Magento\Framework\Event\ManagerInterface',
            [],
            '',
            false
        );
        $this->_post = $this->getMockForAbstractClass(
            'ISM\Blog\Api\Data\PostInterface',
            [],
            '',
            false
        );

        $this->model = new \ISM\Blog\Model\ResourceModel\PostRepository(
            $this->_postRegistry,
            $this->_postFactory,
            $this->_resourceModel,
            $this->_eventManager,
            $this->_searchResultFactory,
            $this->_extensibleDataObjectConverter
        );
    }

    protected function prepareMocksForValidation($isValid = false)
    {
        $this->_post->expects($this->once())->method('getTitle')->willReturn($isValid ? 'Title' : false);
        $this->_post->expects($this->atLeastOnce())->method('getUrlKey')->willReturn($isValid ? 'test_url' : false);
    }

    public function testSave()
    {
        $postId = 1;
        $urlKey = 'test_url';
        $this->prepareMocksForValidation(true);
        $postModel = $this->getMock(
            'ISM\Blog\Model\Post',
            [
                'getId',
                'save',
                'getDataModel',
                'setData',
                'load'
            ],
            [],
            '',
            false
        );
        $postModel->expects($this->once())->method('getDataModel')->willReturn($this->_post);
        $this->_post->expects($this->any())->method('getId')->willReturn($postId);
        $this->_post->expects($this->atLeastOnce())->method('getUrlKey')->willReturn($urlKey);
        $this->_post->expects($this->once())->method('getData')->willReturn(['postData']);
        $this->_resourceModel->expects($this->once())->method('checkUrlKey')->with($urlKey)->willReturn($postId);
        $this->_postRegistry->expects($this->once())
            ->method('push')
            ->with($postModel);
        $this->_postFactory->expects($this->once())
            ->method('create')
            ->willReturn($postModel);
        $postModel->expects($this->once())
            ->method('load')
            ->with($postId);
            //->willReturn($postModel);
        $postModel->expects($this->once())
            ->method('setData')
            ->with(['postData'])
            ->willReturn($postId);
        $this->_eventManager->expects($this->once())
            ->method('dispatch')
            ->with(
                'post_save_after_data_object',
                ['post_data_object' => $this->_post, 'orig_post_data_object' => $this->_post ]
            );
        $this->_postRegistry->expects($this->once())
            ->method('retrieveByUrl')->with($urlKey)->willReturn($postModel);
        $this->model->save($this->_post);
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     */
    public function testSaveWithException()
    {
        $this->prepareMocksForValidation(false);
        $this->model->save($this->_post);
    }

    public function testDelete()
    {
        $postId=2;
        $postModel = $this->getMock(
            'ISM\Blog\Model\Post',
            ['delete'],
            [],
            '',
            false
        );
        $this->_post->expects($this->once())
            ->method('getId')
            ->willReturn($postId);
        $postModel->expects($this->once())
            ->method('delete');
        $this->_postRegistry->expects($this->once())
            ->method('remove')
            ->with($postId);
        $this->_postRegistry->expects($this->once())
            ->method('retrieve')
            ->with($postId)
            ->willReturn($postModel);
        $this->assertTrue($this->model->delete($this->_post));

    }

    public function testDeleteById()
    {
        $postId = 1;
        $postModel = $this->getMock(
            'ISM\Blog\Model\Post',
            ['delete'],
            [],
            '',
            false
        );
        $this->_postRegistry->expects($this->once())->method('retrieve')->with($postId)->willReturn($postModel);
        $postModel->expects($this->once())->method('delete');
        $this->_postRegistry->expects($this->once())->method('remove')->with($postId);
        $this->assertTrue($this->model->deleteById($postId));
    }

    public function testGetList()
    {
        $sortOrder = $this->getMock('Magento\Framework\Api\SortOrder', [], [], '', false);
        $filter = $this->getMock('Magento\Framework\Api\Filter', [], [], '', false);
        $collection = $this->getMock('ISM\Blog\Model\ResourceModel\Post\Collection', [], [], '', false);
        $filterGroup = $this->getMock('Magento\Framework\Api\Search\FilterGroup', [], [], '', false);

        $searchResult = $this->getMockForAbstractClass(
            'ISM\Blog\Api\Data\PostSearchResultsInterface',
            [],
            '',
            false);
        $searchCriteria = $this->getMockForAbstractClass(
            'Magento\Framework\Api\SearchCriteriaInterface',
            [],
            '',
            false
        );
        $postModel = $this->getMock(
            'ISM\Blog\Model\Post',
            [
                'getDataModel',
                'getCollection'
            ],
            [],
            'postModel',
            false
        );
        $this->_searchResultFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($searchResult);
        $searchResult->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteria);
        $this->_postFactory->expects($this->once())
            ->method('create')
            ->willReturn($postModel);
        $postModel->expects($this->once())
            ->method('getCollection')
            ->willReturn($collection);
        $searchCriteria->expects($this->once())
            ->method('getFilterGroups')
            ->willReturn([$filterGroup]);
        $filterGroup->expects($this->once())
            ->method('getFilters')
            ->willReturn([$filter]);
        $filter->expects($this->once())
            ->method('getConditionType')
            ->willReturn(false);
        $filter->expects($this->once())
            ->method('getField')
            ->willReturn('Field');
        $filter->expects($this->atLeastOnce())
            ->method('getValue')
            ->willReturn('Value');
        $searchCriteria->expects($this->once())
            ->method('getSortOrders')
            ->willReturn([$sortOrder]);
        $collection->expects($this->once())
            ->method('addOrder')
            ->with('Field', 'ASC');
        $sortOrder->expects($this->once())
            ->method('getDirection')
            ->willReturn('ASC');
        $sortOrder->expects($this->once())
            ->method('getField')
            ->willReturn('Field');
        $collection->expects($this->once())
            ->method('setCurPage')
            ->with(1);
        $collection->expects($this->once())
            ->method('setPageSize')
            ->with(30);
        $searchCriteria->expects($this->once())
            ->method('getCurrentPage')
            ->willReturn(1);
        $searchCriteria->expects($this->once())
            ->method('getPageSize')
            ->willReturn(30);
        $searchResult->expects($this->once())
            ->method('setTotalCount')
            ->with(25);
        $collection->expects($this->once())
            ->method('getSize')
            ->willReturn(25);
        $postModel->expects($this->atLeastOnce())
            ->method('getDataModel')
            ->willReturn($this->_post);
        $searchResult->expects($this->once())
            ->method('setItems')
            ->with([$this->_post]);
        $collection->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator([$postModel]));

        $this->assertSame($searchResult, $this->model->getList($searchCriteria));
    }
}