<?php

namespace ISM\Blog\Observer;

use ISM\Blog\Helper\Image\Upload;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Event\ObserverInterface;

class FlushAllCache implements ObserverInterface
{
    /** @var  Filesystem */
    protected $_filesystem;
    /** @var  Filesystem\Directory\WriteInterface */
    protected $_mediaDirectory;
    public function __construct(
        Filesystem $filesystem
    ) {
        $this->_filesystem = $filesystem;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Flash ISM_BaseRunner cache
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $dir = $this->_mediaDirectory->getAbsolutePath(Upload::UPLOAD_POST_IMAGE_DIR . 'cache/');
        if (is_dir($dir)) {
            $dir = $this->_mediaDirectory->getDriver()->readDirectory($dir);
            foreach ($dir as $file) {
                $this->_mediaDirectory->delete(Upload::UPLOAD_POST_IMAGE_DIR . 'cache/' . basename($file));
            }
        }
    }
}
