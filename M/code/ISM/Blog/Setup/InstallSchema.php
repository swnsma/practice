<?php
namespace ISM\Blog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use ISM\Blog\Api\Data\PostInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable('ism_blog');

        if (!$setup->getConnection()->isTableExists($tableName)) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    PostInterface::ID,
                    Table::TYPE_INTEGER,
                    null,
                    array(
                        'nullable' => false,
                        'identity' => true,
                        'primary' => true,
                        'unsigned' => true,
                    ),
                    'ID'
                )->addColumn(
                    PostInterface::TITLE,
                    Table::TYPE_TEXT,
                    255,
                    array('nullable' => false),
                    'Post Title'
                )->addColumn(
                    PostInterface::DESCRIPTION,
                    table::TYPE_TEXT,
                    500,
                    array('nullable' => true),
                    'Post Description'
                )->addColumn(
                    PostInterface::CREATED_AT,
                    Table::TYPE_DATETIME,
                    null,
                    array('nullable' => false),
                    'Created At'
                )->addColumn(
                    PostInterface::UPDATED_AT,
                    Table::TYPE_DATETIME,
                    null,
                    array('nullable' => false),
                    'Updated At'
                )->addColumn(
                    PostInterface::IMAGE_URL,
                    Table::TYPE_TEXT,
                    255,
                    array('nullable' => true),
                    'Image'
                )->addColumn(
                    PostInterface::URL_KEY,
                    Table::TYPE_TEXT,
                    255,
                    array('nullable' => false),
                    'URL Key'
                )->addColumn(
                    PostInterface::IS_ACTIVE,
                    Table::TYPE_BOOLEAN,
                    null,
                    array('nullable' => false, 'default' => '1'),
                    'Is Post Active?'
                )->addIndex($setup->getIdxName('ism_blog', ['url_key']), ['url_key'])
                ->setComment('ISM Blog Posts');
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}