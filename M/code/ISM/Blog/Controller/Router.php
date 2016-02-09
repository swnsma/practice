<?php
namespace ISM\Blog\Controller;

use ISM\Blog\Model\PostFactory;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Url;

class Router implements RouterInterface
{
    /**
     * Action factory.
     *
     * @var ActionFactory
     */
    protected $_actionFactory;

    /**
     * Post factory.
     *
     * @var PostFactory
     */
    protected $_postFactory;

    public function __construct(
        ActionFactory $actionFactory,
        PostFactory $postFactory
    )
    {
        $this->_actionFactory = $actionFactory;
        $this->_postFactory = $postFactory;
    }

    /**
     * Validate and Match Blog Post and modify request
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function match(RequestInterface $request)
    {
        $urlKey = str_replace("/blog/", "", $request->getPathInfo());
        $urlKey = rtrim($urlKey, '/');
        $urlKey = ltrim($urlKey, '/');

        /** @var \ISM\Blog\Model\Post $post */
        $post = $this->_postFactory->create();
        $id = $post->checkUrlKey($urlKey);
        if (!$id) {
            return null;
        }

        $request->setModuleName('blog')->setControllerName('view')->setActionName('index')->setParam('id', $id);
        $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $urlKey);

        return $this->_actionFactory->create('Magento\Framework\App\Action\Forward');

    }
}