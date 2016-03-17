<?php

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$catalogProduct = 'catalog_product';
$attributeCode = 'ism_newstoremembers_price';

$setup->addAttribute($catalogProduct, $attribute_code, array(
    'input' => 'text',
    'label' => 'Newstore Member Price',
    'backend' => '',
    'frontend_class' => 'validate-number',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'sort_order' => 30,
    'comparable' => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 0,
    'is_configurable' => 1,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,));

//add new attribute in prices attribute set
$groupName = 'prices';
$attributeSetName = 'default';

$attributeSetId = $setup->getAttributeSetId($catalogProduct, $attributeSetName);
$attributeGroupId = $setup->getAttributeGroupId($catalogProduct, $attributeSetId, $groupName);
$attributeId = $setup->getAttributeId($catalogProduct, $attributeCode);

$setup->addAttributeToSet($catalogProduct, $attributeSetId, $attributeGroupId, $attributeId);
$installer->endSetup();

$group = Mage::getModel('customer/group')->setData(
    array('customer_group_code' => 'Newstore Members Group', 'tax_class' => 3))
    ->save();

Mage::getModel('core/config')->saveConfig('ism_newstore_members/newstore_members_group', "0", 'defaut', $group->getId());