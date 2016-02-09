<?php
namespace ISM\Blog\Helper;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\Result\PageFactory;

class Post extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var  \ISM\Blog\Model\Post  */
    protected $_post;

    /** @var  PageFactory */
    protected $_pageFactory;

    public function __construct(Context $context, \ISM\Blog\Model\Post $post, PageFactory $pageFactory)
    {
        $this->_post = $post;
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function prepareResultPost(Action $action, $id = null)
    {
        if($id !== null && $id !== $this->_post->getId()) {
            $delimiterPosition = strpos($id, '|');
            if ($delimiterPosition) {
                $id = substr($id, 0, $delimiterPosition);
            }

            if (!$this->_post->load($id)) {
                return false;
            }
        }

        if (!$this->_post->getId()) {
            return false;
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $page = $this->_pageFactory->create();
        $page->addHandle('blog_post_view');
        $page->addPageLayoutHandles(['id' => $this->_post->getId()]);
        $this->_eventManager->dispatch(
            'ism_blog_post_render',
            ['post' => $this->_post, 'controller_action' => $action]
        );

        return $page;
    }
}