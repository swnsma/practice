<?php

$installer = $this;
$newstoreMembersTable = $installer->getTable('ism_newstore_members/newstore_members');
$installer->startSetup();

$installer->getConnection()->dropTable($newstoreMembersTable);

$table = $installer->getConnection()
    ->newTable($newstoreMembersTable)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ))
    ->addColumn('unique_key', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
        'nullable' => false,
    ))
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER,  array(
        'nullable' => false,
    ))
    ->addColumn('expire_date', Varien_Db_Ddl_Table::TYPE_DATE, array(
        'nullable' => false
    ))
    ->addColumn('post_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
        'nullable' => false,
        'default' => '',
    ))
    ->addColumn('activated', Varien_Db_Ddl_table::TYPE_TINYINT, null, array(
        'nullable' => false,
        'defalut' => 0,
    ))
    ->addForeignKey(
        $installer->getFkName(
            'ism_newstore_members/newstore_members',
            'customer_id',
            'customer/entity',
            'entity_id'
        ),
        'customer_id',
        $installer->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_SET_NULL);

$installer->getConnection()->createTable($table);

$installer->getConnection()
    ->addColumn(
        'sales_flat_quote', 'newstore_member_code', array(
            'nullable' => true,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length' => 256,
            'comment' => 'Newstore Meber Code',
        ));

$installer->getConnection()
    ->addColumn(
        'sales_flat_order', 'newstore_member_code', array(
            'nullable' => true,
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length' => 256,
            'comment' => 'Newstore Member Code',
        ));

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('customer', 'prev_group_id', array(
    'type' => 'int'
));

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'prev_group_id',
    '999'
);

$installer->endSetup();