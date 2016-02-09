<?php
namespace ISM\Blog\Model\Data;

use \Magento\Framework\Api\ObjectFactory;

class PostBuilder extends \Magento\Framework\Api\AbstractSimpleObjectBuilder
{
    public function __construct(ObjectFactory $objectFactory, $data = [])
    {
        parent::__construct($objectFactory);
        $this->data = $data;
    }

    public function create($data = null)
    {
        if (isset($data)) {
            $this->data = $data;
        }

        return parent::create();
    }

}