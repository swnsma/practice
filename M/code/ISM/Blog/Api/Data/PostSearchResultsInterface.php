<?php

namespace ISM\Blog\Api\Data;

interface PostSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get posts list.
     *
     * @api
     * @return \ISM\Blog\Api\Data\PostInterface[]
     */
    public function getItems();

    /**
     * Set posts list.
     *
     * @api
     * @param \ISM\Blog\Api\Data\PostInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}