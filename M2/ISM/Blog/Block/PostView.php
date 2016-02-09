<?php
namespace ISM\Blog\Block;

use ISM\Blog\Helper\Image\Upload;
use ISM\Blog\Model\Post;
use ISM\Blog\Model\PostFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class PostView extends Template implements IdentityInterface
{

    protected $_post;

    protected $_postFactory;

    public function __construct(
        Context $context,
        Post $post,
        PostFactory $postFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_post = $post;
        $this->_postFactory = $postFactory;
    }

    /**
     * @return \ISM\Blog\Model\Post
     */
    public function getPost()
    {
        if (!$this->hasData('post')) {
            if ($this->getPostId()) {
                $post = $this->_postFactory->create();

            } else {
                $post = $this->_post;
            }
            $this->setData('post', $post);
        }

        return $this->getData('post');
    }

    public function getPostImageUrl() {
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .
        Upload::UPLOAD_POST_IMAGE_DIR . $this->getPost()->getImageUrl();
    }

    public function getIdentities()
    {
        return $this->getPost()->getIdentities();
    }
}