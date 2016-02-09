<?php

namespace ISM\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class MassEnable extends MassActionAbstract
{
    protected $_message = 'A total of %1 record(s) have been processes.';

    protected function _processAction(AbstractCollection $collection)
    {
        $count = 0;
        /** @var  \ISM\Blog\Model\Post $model */
        foreach ($collection->getItems() as $model) {
            $model->setIsActive(true);
            $model->save();
            ++$count;
        }
        return $count;
    }
}