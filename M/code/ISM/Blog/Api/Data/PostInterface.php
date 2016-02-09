<?php

namespace ISM\Blog\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PostInterface extends ExtensibleDataInterface
{
    const ID = 'id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const IMAGE_URL = 'image_url';
    const URL_KEY = 'url_key';
    const IS_ACTIVE = 'is_active';


    public function getData();
    /**
     * Get ID.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Get create time.
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Get update time.
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Get image url.
     *
     * @return string|null
     */
    public function getImageUrl();

    /**
     * Is active.
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Return url key.
     *
     * @return string
     */
    public function getUrlKey();

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setId($id);

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setTitle($title);

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setDescription($description);

    /**
     * Set create time.
     *
     * @param string $createdAt
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setCreatedAt($createdAt);

    /**
     * Set update time.
     *
     * @param string $updatedAt
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Set image url.
     *
     * @param string $imageUrl
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setImageUrl($imageUrl);

    /**
     * Set is active.
     *
     * @param bool $isActive
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setIsActive($isActive);

    /**
     * Set url key.
     *
     * @param string $urlKey
     *
     * @return \ISM\Blog\Api\Data\PostInterface;
     */
    public function setUrlKey($urlKey);
}