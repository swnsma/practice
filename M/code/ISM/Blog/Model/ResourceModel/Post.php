<?php
namespace ISM\Blog\Model\ResourceModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Post extends AbstractDb
{

    protected $_date;

    protected function _construct()
    {
        $this->_init('ism_blog', 'id');
    }

    public function __construct(Context $context, DateTime $date, $connectionName = null)
    {
        $this->_date = $date;
        parent::__construct($context, $connectionName);
    }

    /**
     * Process post data before saving.
     *
     * @param AbstractModel $object
     *
     * @return $this
     * @throws LocalizedException
     */
    public function _beforeSave(AbstractModel $object)
    {
        if (!$this->isValidPostUrlKey($object)) {
            throw new LocalizedException(
                __('The post URL key contains capital letters or disallowed symbols.')
            );
        }

        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt($this->_date->gmtDate());
        }

        $object->setUpdatedAt($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }

    /**
     * Load an object using 'url_key' field if there's no filter specified and value isn't numeric.
     *
     * @param AbstractModel $object
     * @param mixed         $value
     * @param null          $field
     *
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'url_key';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Retrieve load select with filter by url_key and activity.
     *
     * @param string  $urlKey
     * @param int $isActive
     *
     * @return \Magento\Framework\DB\Select
     * @throws LocalizedException
     */
    protected function  _getLoadByUrlKeySelect($urlKey, $isActive = null) {
        $select = $this->getConnection()->select()->from(
            ['bp' => $this->getMainTable()]
        )->where(
            'bp.url_key = ?',
            $urlKey
        );

        return $select;
    }

    /**
     * Check whether post url key is numeric.
     *
     * @param AbstractModel $object
     *
     * @return int
     */
    protected function isNumericPostUrlKey(AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     * Check whether post url key is valid.
     *
     * @param AbstractModel $object
     *
     * @return int
     */
    protected function isValidPostUrlKey(AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }

    /**
     * Check if post url key exists.
     * return post id if post exists.
     *
     * @param $urlKey
     *
     * @return string
     */
    public function checkUrlKey($urlKey)
    {
        $select = $this->_getLoadByUrlKeySelect($urlKey, 1);
        $select->reset(\Zend_Db_Select::COLUMNS)->columns('bp.id')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }
}