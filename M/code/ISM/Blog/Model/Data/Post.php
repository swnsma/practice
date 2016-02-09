<?php
namespace ISM\Blog\Model\Data;

class Post extends \Magento\Framework\Api\AbstractExtensibleObject implements \ISM\Blog\Api\Data\PostInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUrlKey()
    {
        return $this->_get(self::URL_KEY);
    }

    public function getData()
    {
        return $this->_data;
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getImageUrl()
    {
        return $this->_get(self::IMAGE_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function setImageUrl($imageUrl)
    {
        return $this->setData(self::IMAGE_URL, $imageUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return (bool)$this->_get(self::IS_ACTIVE);
    }
}