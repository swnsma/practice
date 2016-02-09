<?php
namespace ISM\Blog\Model\ResourceModel\Post;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('ISM\Blog\Model\Post', 'ISM\Blog\Model\ResourceModel\Post');
        parent::_construct();
    }
}