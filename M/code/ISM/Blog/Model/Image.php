<?php
namespace ISM\Blog\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\Factory;

class Image
{
    protected $_imageFactory;
    protected $_name;
    /** @var  \Magento\Framework\Image */
    protected $_processor;
    protected $_filesystem;
    protected $_mediaDirectory;

    protected $_width;
    protected $_height;

    /**
     * Image constructor.
     *
     * @param Factory    $imageFactory
     * @param Filesystem $filesystem
     * @param null       $file
     */
    public function __construct(
        Factory $imageFactory,
        Filesystem $filesystem,
        $file = null
    ) {
        $this->_filesystem = $filesystem;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_imageFactory = $imageFactory;
        if (!is_null($file)) {
           $this->init($file);
        }
    }

    public function init($file)
    {
        $this->setName($file);
        $this->_processor = $this->_imageFactory->create($file);
        $this->_height = $this->_processor->getOriginalHeight();
        $this->_width = $this->_processor->getOriginalWidth();
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setWH($w, $h)
    {
        $this->_width = $w;
        $this->_height = $h;
    }


    public function setName($name)
    {
        if(is_file($name)) {
            $this->_name = $name;
        }
    }

    public function resize()
    {
        $this->_processor->resize($this->_width, $this->_height);
    }

    public function save($destination = null, $newName = null)
    {
        $this->_processor->save($destination, $newName);
    }

    public function remove($file = '')
    {
        if (empty($file)) {
            $file = $this->_name;
        }
        $this->_mediaDirectory->delete($file);

        return $this;
    }
}