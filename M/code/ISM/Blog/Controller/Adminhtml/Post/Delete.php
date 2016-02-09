<?php
namespace ISM\Blog\Controller\Adminhtml\Post;

use ISM\Blog\Helper\Image\Upload;
use ISM\Blog\Model\ImageFactory;
use ISM\Blog\Model\PostFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Webapi\Exception;

class Delete extends Action
{

    /** @var  \ISM\Blog\Model\PostFactory */
    protected $_postFactory;
    protected $_imageFactory;

    /**
     * Delete constructor.
     *
     * @param Action\Context $context
     * @param PostFactory    $postFactory
     * @param ImageFactory   $imageFactory
     */
    public function __construct(
        Action\Context $context,
        PostFactory $postFactory,
        ImageFactory $imageFactory
    )
    {
        $this->_imageFactory = $imageFactory;
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    /**
     * Check access.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ISM_Blog::post_delete');
    }

    /**
     * Execute post delete.
     *
     * @return $this
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $post = $this->_postFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        $post->load($id);
        $img = $post->getImageUrl();
        $image = $this->_imageFactory->create();

        if(!$post->getId()){
            return $resultRedirect->setPath('*/*/');
        }

        try{
            $post->delete();
        } catch(Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        if ($img) {
            $image->remove(Upload::UPLOAD_POST_IMAGE_DIR . $img);
        }
        return $resultRedirect->setPath('*/*/');
    }
}