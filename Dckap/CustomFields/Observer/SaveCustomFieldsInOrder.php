<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Dckap\CustomFields\Observer;

/**
* Class SaveCustomFieldsInOrder
* @package Dckap\CustomFields\Observer
*/
class SaveCustomFieldsInOrder implements \Magento\Framework\Event\ObserverInterface
{
   /**
    * @param \Magento\Framework\Event\Observer $observer
    * @return $this
    */
   public function execute(\Magento\Framework\Event\Observer $observer)
  {

     $order = $observer->getEvent()->getOrder();
     $quote = $observer->getEvent()->getQuote();

       /*$order->setData("input_custom_shipping_field",$quote->getInputCustomShippingField());
       $order->setData("date_custom_shipping_field",$quote->getDateCustomShippingField());
       $order->setData("select_custom_shipping_field",$quote->getSelectCustomShippingField());*/
        $order->setData("custom_shipping_method",$quote->getCustomShippingMethod());
        $order->setData("custom_shipping_service",$quote->getCustomShippingService());
        $order->setData("account_number",$quote->getAccountNumber());
        $order->setData("account_name",$quote->getAccountName());
        $order->setData("account_address",$quote->getAccountAddress());
        $order->setData("custom_shipping_city",$quote->getCustomShippingCity());
        $order->setData("custom_shipping_state",$quote->getCustomShippingState());
        $order->setData("custom_shipping_zipcode",$quote->getCustomShippingZipcode());
        $order->setData("custom_shipping_country",$quote->getCustomShippingCountry());

     return $this;
  }
}
