<?php
namespace ISM\Blog\Model;

use ISM\Blog\Model\Post;
use ISM\Blog\Model\PostFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class PostRegistry
{
    /** @var PostFactory  */
    private $postFactory;

    /** @var Post[] */
    private $postRegistryById = [];

    /** @var Post[] */
    private $postRegistryByUrl = [];

    public function __construct(PostFactory $postFactory)
    {
        $this->postFactory = $postFactory;
    }

    /**
     * @param $postId$this->_extensibleDataObjectConverter->toNestedArray(
            $post,
            [],
            '\ISM\Blog\Api\Data\PostInterface'
        );
     *
     * @return Post
     * @throws NoSuchEntityException
     */
    public function retrieve($postId)
    {
        if (isset($this->postRegistryById[$postId])) {
           return $this->postRegistryById[$postId];
        }
        /** @var $post Post $post */
        $post = $this->postFactory->create()->load($postId);

        if (!$post->getId()) {
           throw NoSuchEntityException::singleField('postId', $postId);
        } else {
            $url = $post->getUrlKey();
            $this->postRegistryById[$postId] = $post;
            $this->postRegistryByUrl[$url] = $post;

            return $post;
        }
    }

    public function retrieveByUrl($url)
    {
        if(isset($this->postRegistryByUrl[$url])) {
            return $this->postRegistryByUrl[$url];
        }
        /** @var $post Post $post */
        $post = $this->postFactory->create();

        $id = $post->checkUrlKey($url);

        if (!$id ) {
            throw NoSuchEntityException::singleField('url_key', $url);
        } else {
            $post->load($id);
            $this->postRegistryById[$id] = $post;
            $this->postRegistryByUrl[$url] = $post;

            return $post;
        }
    }

    public function remove($id)
    {
        if (isset($this->postRegistryById[$id])) {
            /** @var \ISM\Blog\Model\Post $post */
            $post = $this->postRegistryById[$id];
            unset($this->postRegistryById[$post->getId()]);
            unset($this->postRegistryByUrl[$post->getUrlKey()]);
        }
    }

    public function removeByUrl($url)
    {
        if(isset($this->postRegistryByUrl[$url])) {
            $post = $this->postRegistryByUrl[$url];
            unset($this->postRegistryById[$post->getId()]);
            unset($this->postRegistryByUrl[$post->getUrlKey()]);
        }
    }

    public function push(Post $post)
    {
        $this->postRegistryById[$post->getId()] = $post;
        $this->postRegistryByUrl[$post->getUrlKey()] = $post;

        return $this;
    }
}