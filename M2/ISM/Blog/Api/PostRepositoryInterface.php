<?php
namespace ISM\Blog\Api;

interface PostRepositoryInterface
{
    public function save(\ISM\Blog\Api\Data\PostInterface $post);

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    public function get($id);

    public function getByUrl($url);

    public function delete(\ISM\Blog\Api\Data\PostInterface $post);

    public function deleteById($id);
}