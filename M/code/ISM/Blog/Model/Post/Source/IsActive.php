<?php
namespace ISM\Blog\Model\Post\Source;

use ISM\Blog\Model\Post;
use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    /** @var  Post */
    protected $post;

    public function __construct(Post $post) {
        $this->post = $post;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->post->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }

        return $options;
    }
}