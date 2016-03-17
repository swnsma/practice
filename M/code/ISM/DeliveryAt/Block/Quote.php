<?php
class ISM_DeliveryAt_Block_Quote extends Mage_Core_Block_Template
{
    public function getDeliveryAtByQuote($quote)
    {
        return  Mage::getModel('sales/quote')->load($quote)->getDeliveryAt();
    }
}