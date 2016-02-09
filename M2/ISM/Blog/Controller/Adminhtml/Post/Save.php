<?php
namespace ISM\Blog\Controller\Adminhtml\Post;

use ISM\Blog\Helper\Image\Upload;
use ISM\Blog\Model\PostFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    protected $postFactory;
    protected $uploadHelper;

    public function __construct(
        Action\Context $context,
        PostFactory $postFactory,
        Upload $uploadHelper)
    {
        $this->uploadHelper = $uploadHelper;
        $this->postFactory = $postFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ISM_Blog::save');
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }
        /** @var \ISM\Blog\Model\Post $model */
        $model = $this->postFactory->create();

        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model->load($id);
        }
        $image = $model->getImageUrl();

        $model->setData($data);

        if (!empty($_FILES) && !empty($_FILES['img'])) {
            try {
                $image = $this->uploadHelper->saveImage($_FILES['img'], $image);
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $model->setImageUrl($image);
        $this->_eventManager->dispatch(
            'blog_post_prepare_save',
            ['post' => $model, 'request' => $this->getRequest()]
        );

        try {
            $model->save();
            $this->messageManager->addSuccess(__('Post has been saved.'));
            $this->_getSession()->setFormData(false);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving the post.'));
        }

        $this->_getSession()->setFormData($data);
        return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
    }
}