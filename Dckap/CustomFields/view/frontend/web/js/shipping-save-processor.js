/*
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/
define(
   [
       'ko',
       'Magento_Checkout/js/model/quote',
       'Magento_Checkout/js/model/resource-url-manager',
       'mage/storage',
       'Magento_Checkout/js/model/payment-service',
       'Magento_Checkout/js/model/payment/method-converter',
       'Magento_Checkout/js/model/error-processor',
       'Magento_Checkout/js/model/full-screen-loader',
       'Magento_Checkout/js/action/select-billing-address'
   ],
   function (
       ko,
       quote,
       resourceUrlManager,
       storage,
       paymentService,
       methodConverter,
       errorProcessor,
       fullScreenLoader,
       selectBillingAddressAction
   ) {
       return {
           saveShippingInformation: function () {
               var payload;

               var shippingMethod = quote.shippingMethod().method_code+'_'+quote.shippingMethod().carrier_code;

               var custom_shipping_method = null;
               var custom_shipping_service = null;
               var account_number = null;
               var account_name = null;
               var account_address = null;
               var custom_shipping_city = null;
               var custom_shipping_state = null;
               var custom_shipping_zipcode = null;
               var custom_shipping_country = null;

               
               
               if(shippingMethod == "express_express") {
                 custom_shipping_method = jQuery('[name="custom_shipping_field[custom_shipping_method]"]').val();
                 custom_shipping_service = jQuery('[name="custom_shipping_field[custom_shipping_service]"]').val();
                 account_number = jQuery('[name="custom_shipping_field[account_number]"]').val();
                 account_name = jQuery('[name="custom_shipping_field[account_name]"]').val();
                 account_address = jQuery('[name="custom_shipping_field[account_address]"]').val();
                 custom_shipping_city = jQuery('[name="custom_shipping_field[custom_shipping_city]"]').val();
                 custom_shipping_state = jQuery('[name="custom_shipping_field[custom_shipping_state]"]').val();
                 custom_shipping_zipcode = jQuery('[name="custom_shipping_field[custom_shipping_zipcode]"]').val();
                 custom_shipping_country = jQuery('[name="custom_shipping_field[custom_shipping_country]"]').val();
                  /*input_custom_shipping_field = jQuery('[name="custom_shipping_field[input_custom_shipping_field]"]').val();
                  date_custom_shipping_field = jQuery('[name="custom_shipping_field[date_custom_shipping_field]"]').val();
                  if(date_custom_shipping_field == '')
                  {
                  alert('Please enter UPS or FedEx Number'); return false;
                  }
                  select_custom_shipping_field = jQuery('[name="custom_shipping_field[select_custom_shipping_field]"]').val();*/
               }
          
               if (!quote.billingAddress()) {
                   selectBillingAddressAction(quote.shippingAddress());
               }
               payload = {
                   addressInformation: {
                       shipping_address: quote.shippingAddress(),
                       billing_address: quote.billingAddress(),
                       shipping_method_code: quote.shippingMethod().method_code,
                       shipping_carrier_code: quote.shippingMethod().carrier_code,
                       extension_attributes: {
                           custom_shipping_method : custom_shipping_method,
                           custom_shipping_service : custom_shipping_service,
                           account_number : account_number,
                           account_name : account_name,
                           account_address : account_address,
                           custom_shipping_city : custom_shipping_city,
                           custom_shipping_state : custom_shipping_state,
                           custom_shipping_zipcode : custom_shipping_zipcode,
                           custom_shipping_country : custom_shipping_country,
                           
                       }
                   }
               };
               fullScreenLoader.startLoader();

               return storage.post(
                   resourceUrlManager.getUrlForSetShippingInformation(quote),
                   JSON.stringify(payload)
               ).done(
                   function (response) {
                       quote.setTotals(response.totals);
                       paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                       fullScreenLoader.stopLoader();
                   }
               ).fail(
                   function (response) {
                       errorProcessor.process(response);
                       fullScreenLoader.stopLoader();
                   }
               );
           }
       };
   }
);
