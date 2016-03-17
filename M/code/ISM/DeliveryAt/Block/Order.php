<?php
class ISM_DeliveryAt_Block_Order extends Mage_Core_Block_Template
{
    public function getDeliveryAt()
    {
        return Mage::helper('core')
            ->formatDate(Mage::getModel('sales/order')
            ->load($this->getOrder()->getId())->getDeliveryAt(), 'full');
    }

    public function getOrder()
    {
        return Mage::registry('current_order');
    }
}