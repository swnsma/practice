<?php
namespace ISM\Blog\Ui\Component\Listing\Column;

use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    const NAME = 'image_url';

    const ALT_FIELD = 'name';

    /**
     * Thumbnail constructor.
     *
     * @param ContextInterface                 $context
     * @param UiComponentFactory               $uiComponentFactory
     * @param \ISM\Blog\Helper\Image\Thumbnail $thumbnail
     * @param UrlInterface  $urlBuilder
     * @param array                            $components
     * @param array                            $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \ISM\Blog\Helper\Image\Thumbnail $thumbnail,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->thumbnail = $thumbnail;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $post = new DataObject($item);
                $this->thumbnail->init($post);
                $item[$fieldName . '_src'] = $this->thumbnail->getSrc();//$imageHelper->getUrl();
                $item[$fieldName . '_alt'] = $this->thumbnail->getLabel();
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'blog/post/edit',
                    ['id' => $post->getId()]
                );
                $item[$fieldName . '_orig_src'] = $this->thumbnail->getOriginalSrc();
            }
        }

        return $dataSource;
    }

}