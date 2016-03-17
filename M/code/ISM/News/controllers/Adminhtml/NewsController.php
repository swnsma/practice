<?php

class ISM_News_Adminhtml_NewsController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('news/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title("News");
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ism_news/adminhtml_news'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $newsId = $this->getRequest()->getParam('id');
        $newsModel = Mage::getModel('ism_news/list')->load($newsId);
        if($newsModel->getId() || $newsId == 0) {

            Mage::register('news_data', $newsModel);

            $this->loadLayout();
            $this->_setActiveMenu('news/item');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item Manager'),
                Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item News'),
                Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('ism_news/adminhtml_news_edit'))
                ->_addLeft($this->getLayout()->createBlock('ism_news/adminhtml_news_edit_tabs'));
            $this->renderLayout();
            Mage::dispatchEvent('start_edit', array(
                   'id' => $newsId,
                ));

        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ism_news')->__('Item does not exist'));
            $this->_redirect('*/*/');

            Mage::dispatchEvent('edit_news_error', array(
                'id' => $newsId,
            ));

        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if( $this->getRequest()->getPost() ) {

            try {
                $postData = $this->getRequest()->getPost();
                $newsModel = Mage::getModel('ism_news/list');
                $id= $this->getRequest()->getParam('id');

                $title = $postData['title'];
                $content = $postData['content'];
                $announce = $postData['announce'];
                $published = $postData['published'];
                $publish_date = $postData['publish_date'];

                if(!$publish_date)
                {
                    //$publish_date = Mage::app()->getLocale()->date();
                    $newsModel ->setPublishDate($publish_date);
                }

                $newsModel->setId($id)
                    ->setTitle($title)
                    ->setContent($content)
                    ->setAnnounce($announce)
                    ->setPublished($published)
                    ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setNewsData(false);

                Mage::dispatchEvent(
                    'save_news_news',
                    array(
                        'id' => $id,
                        'title' => $title,
                        '$content' => $content,
                        'announce' => $announce,
                        'published' => $published,
                        'publish_date' => $publish_date,
                    )
                );

                $this->_redirect('*/*/');
                return;
            } catch(Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setNewsData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                Mage::dispatchEvent(
                    'save_news_error',
                    array(
                        'message' => $e->getMessage(),
                    ));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if($this->getRequest()->getParam('id') > 0) {
            try {
                $newsModel = Mage::getModel('ism_news/list');
                $id = $this->getRequest()->getParam('id');
                $newsModel->setId($id)
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));

                Mage::dispatchEvent(
                    'delete_news',
                    array(
                        'id' => $id,
                    ));

                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                Mage::dispatchEvent(
                    'delete_news_error',
                    array(
                        'message' => $e->getMessage(),
                    ));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $news = $this->getRequest()->getParam('news_id');

        if(is_array($news) && sizeof($news) > 0) {
            try{
                foreach($news as $id)
                {
                    Mage::getModel('ism_news/list')->setId($id)->delete();
                }

                Mage::dispatchEvent(
                    'mass_delete_news',
                    array(
                        'ids' => $news,
                    ));

                $this->_getSession()->addSuccess($this->__('News have been successfully deleted.'));
            } catch(Exception $e) {

                Mage::dispatchEvent(
                    'mass_delete_news_error',
                    array(
                        'message' => $e->getMessage(),
                    ));

                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($this->__("Please, select news"));
        }
        $this->_redirect('*/*');
    }

}