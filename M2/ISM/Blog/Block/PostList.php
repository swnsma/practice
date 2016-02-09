<?php
namespace ISM\Blog\Block;

use ISM\Blog\Api\Data\PostInterface;
use ISM\Blog\Helper\Image\Upload;
use ISM\Blog\Model\Post;
use ISM\Blog\Model\ResourceModel\Post\Collection;
use ISM\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;

class PostList extends Template implements IdentityInterface
{
    /**
     * @var \ISM\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_collectionFactory;

    public function __construct(Template\Context $context, array $data, CollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    public function getPostImageUrl($img) {
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .
        Upload::UPLOAD_POST_IMAGE_DIR . $img;
    }

    public function getPosts()
    {
        if (!$this->hasData('posts')) {
            $collection = $this->_collectionFactory->create();
            $posts = $collection
                ->addOrder(PostInterface::CREATED_AT,
                    Collection::SORT_ORDER_DESC);
            $this->setData('posts', $posts);
        }

        return $this->getData('posts');
    }

    /**
     * Return identifiers for produced content;
     *
     * @return array
     */
    public function getIdentities()
    {
        return [Post::CACHE . '_' . 'list'];
    }
}