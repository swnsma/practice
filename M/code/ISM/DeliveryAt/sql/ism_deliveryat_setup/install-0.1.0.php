<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->addColumn(
        "sales_flat_order", "delivery_at", array(
            'nullable' => true,
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment' => 'Delivery At'
        )
    );

$installer->getConnection()
    ->addColumn(
        "sales_flat_quote", "delivery_at", array(
            'nullable' => true,
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment' => 'Delivery At'
        )
    );
$installer->endSetup();