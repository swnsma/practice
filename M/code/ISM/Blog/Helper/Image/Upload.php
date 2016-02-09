<?php
namespace ISM\Blog\Helper\Image;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;


class Upload
{

    const UPLOAD_POST_IMAGE_DIR = 'ism/images/';
    const TYPES_ALLOWED = array(
        'png',
        'jpg',
        'jpeg',
        'gif'
    );

    protected $_maxSize = 2048;
    /** @var Filesystem\Directory\WriteInterface  */
    protected $_mediaDirectory;
    /** @var UploaderFactory  */
    protected $_uploaderFactory;
    /** @var Filesystem  */
    protected $_filesystem;


    /**
     * Upload constructor.
     *
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem      $filesystem
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    ) {
        $this->_filesystem = $filesystem;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Remove image.
     *
     * @param $file
     */
    public function removeImage($file)
    {
        $this->_mediaDirectory->delete(self::UPLOAD_POST_IMAGE_DIR . $file );
    }

    /**
     * Save new image and remove old image.
     *
     * @param $file string new file
     * @param $old  string old file
     *
     * @return string file name
     * @throws LocalizedException
     */
    public function saveImage($file, $old)
    {
        $uploadDir = $this->_mediaDirectory->getAbsolutePath(self::UPLOAD_POST_IMAGE_DIR);
        try {
            $uploader = $this->_uploaderFactory->create(['fileId' => $file]);
            $uploader->setAllowedExtensions(self::TYPES_ALLOWED);
            $uploader->setAllowRenameFiles(true);
            $uploader->addValidateCallback('size', $this, 'validateMaxSize');
            $result = $uploader->save($uploadDir);
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }

        if ($old) {
            $this->removeImage($old);
        }

        return $result['file'];
    }

    /**
     * Validate image size.
     *
     * @param $filePath
     *
     * @throws LocalizedException
     */
    public function sizeValidate($filePath)
    {
        $directory = $this->_filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        if ($this->_maxSize > 0 && $directory->stat(
                $directory->getRelativePath($filePath)
            )['size'] > $this->_maxSize * 1024
        ) {
            throw new LocalizedException(
                __('The file you\'re uploading exceeds the server size limit of %1 kilobytes.', $this->_maxSize)
            );
        }
    }

}