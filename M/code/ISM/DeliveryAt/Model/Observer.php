<?php
class ISM_DeliveryAt_Model_Observer
{
   public function deliveryAtToQuote(Varien_Event_Observer $observer)
   {
       $observer->getQuote()->setDeliveryAt($observer->getRequest()->getPost('delivery_at'));
   }
}