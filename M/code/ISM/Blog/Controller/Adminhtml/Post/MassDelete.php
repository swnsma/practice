<?php
namespace ISM\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class MassDelete extends MassActionAbstract
{
    protected $_message = 'A total of %1 record(s) have been deleted.';

    /**
     * Delete all records in collection. Return count of successful operations.
     *
     * @param AbstractCollection $collection
     *
     * @return int
     */
    protected function _processAction(AbstractCollection $collection)
    {
        $count = 0;
        /** @var \ISM\Blog\Model\Post $model */
        foreach ($collection->getItems() as $model) {
            $model->delete();
            ++$count;
        }

        return $count;
    }
}