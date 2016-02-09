<?php
namespace ISM\Blog\Helper\Image;

use ISM\Blog\Model\ImageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends AbstractHelper
{
    /** @var \ISM\Blog\Model\Post */
    protected $_post;
    protected $width = 75;
    protected $height = 75;
    /** @var StoreManagerInterface  */
    protected $_storeManager;
    /** @var ImageFactory  */
    protected $_imageFactory;
    /** @var Filesystem\Directory\WriteInterface  */
    protected $_mediaDirectory;
    protected $_assetRepo;

    protected $_cacheDir = Upload::UPLOAD_POST_IMAGE_DIR . 'cache/';

    /**
     * Thumbnail constructor.
     *
     * @param Context               $context
     * @param StoreManagerInterface $storeManager
     * @param ImageFactory          $imageFactory
     * @param Filesystem            $filesystem
     * @param Repository            $assetRepo
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ImageFactory $imageFactory,
        Filesystem $filesystem,
        Repository $assetRepo
    )
    {
        $this->_assetRepo = $assetRepo;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_imageFactory   = $imageFactory;
        $this->_storeManager   = $storeManager;

        parent::__construct($context);
    }

    /**
     * Get thumbnail src.
     *
     * @return string
     */
    public function getSrc()
    {
        if ($this->checkAndCreate()) {
            $url = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) .
                $this->_cacheDir .
                $this->_post->getImageUrl();
        } else {
            $url = $this->_assetRepo->getUrl(
                "ISM_Blog::images/blog/placeholder/thumbnail.jpg"
            );
        }

        return $url;
    }

    /**
     * Return alt label.
     *
     * @return mixed|string
     */
    public function getLabel()
    {
        return $this->_post->getTitle();
    }

    /**
     * Return src of original image.
     *
     * @return string
     */
    public function getOriginalSrc()
    {
        if($this->_post->getImageUrl()){
            $url = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) .
            Upload::UPLOAD_POST_IMAGE_DIR .
            $this->_post->getImageUrl();
        } else {
            $url = $this->_assetRepo->getUrl(
                "ISM_Blog::images/blog/placeholder/image.jpg"
            );
        }

        return $url;
    }

    /**
     * Check thumbnail and create it if need.
     *
     * @return bool
     */
    public function checkAndCreate()
    {
        if ($this->_mediaDirectory->isFile($this->_cacheDir . $this->_post->getImageUrl())) {
            return true;
        }

        if (!$this->_mediaDirectory->isFile(Upload::UPLOAD_POST_IMAGE_DIR . $this->_post->getImageUrl())) {
            return false;
        }

        $image = $this->_imageFactory->create(array(
            'file' => $this->_mediaDirectory->getAbsolutePath(Upload::UPLOAD_POST_IMAGE_DIR . $this->_post->getImageUrl())));
        $image->setWH(75, 75);
        $image->resize();
        $image->save(DirectoryList::PUB . DIRECTORY_SEPARATOR .
            DirectoryList::MEDIA . DIRECTORY_SEPARATOR .
            $this->_cacheDir . $this->_post->getImageUrl());

        return true;
    }

    /**
     * Init helper.
     *
     * @param $post
     */
    public function init($post)
    {
        $this->_post = $post;
    }

    protected function setPost($post)
    {
        $this->_post = $post;

        return $this;
    }
}