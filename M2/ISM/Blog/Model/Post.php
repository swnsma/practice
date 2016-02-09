<?php
namespace ISM\Blog\Model;

use ISM\Blog\Api\Data\PostInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use \Magento\Framework\Model\Context;

class Post extends AbstractModel implements IdentityInterface
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const CACHE = 'ism_blog_post';

    protected $_cacheTag = 'ism_blog_post';

    protected $_eventPrefix = 'ism_blog_post';

    protected $_postDataFactory;

    protected $_dataObjectHelper;

    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \ISM\Blog\Model\ResourceModel\Post $resource = null,
        \ISM\Blog\Model\ResourceModel\Post\Collection $resourceCollection = null,
        \ISM\Blog\Api\Data\PostInterfaceFactory $postDataFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        array $data = array())
    {
        $this->_postDataFactory = $postDataFactory;
        $this->_dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Resource model initialization.
     */
    protected function _construct()
    {
        $this->_init('ISM\Blog\Model\ResourceModel\Post');
        parent::_construct();
    }

    public function checkUrlKey($urlKey)
    {
        return $this->_getResource()->checkUrlKey($urlKey);
    }
    /**
     * Prepare post's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_DISABLED => __('Disabled'), self::STATUS_ENABLED => __('Enabled')];
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE.'_'.$this->getId()];
    }

    public function getDataModel()
    {
        $postData = $this->getData();
        $postDataObject = $this->_postDataFactory->create();
        $this->_dataObjectHelper->populateWithArray(
            $postDataObject,
            $postData,
            '\ISM\Blog\Api\Data\PostInterface'
        );
        return $postDataObject;
    }
}