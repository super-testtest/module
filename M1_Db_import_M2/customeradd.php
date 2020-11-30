
<?php
require_once('app/Mage.php');
 Mage::app();

class Customer
{
    
    function customerdata(){

			$dataarr= array(132,133)
			;

			$alldata = array();
			$file = fopen("finalcsv/"."customeradd".".csv","w");

			
			foreach ($dataarr as $key => $value) {
				# code...
			/*$order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($value);
			$orderItems = count($order->getAllItems())."<br>";*/

				$customer = Mage::getModel('customer/customer')->load( $value);
				$website = Mage::getModel('core/website')->load($customer->getData('website_id'));
				 $billing = $customer->getDefaultBilling();
				 $shipping = $customer->getDefaultShipping();
				 $email = $customer->getData('email');
				 $customerId = $customer->getData('entity_id');
				 if(count($customer->getAddresses())){
					foreach ($customer->getAddresses() as $customerData)
					{
						
						$finalData = array();
						//$finalData[] =  array($key=>$key);
						$finalData['_website'] = $website['code'];
						$finalData['_email'] = $email;
						$finalData['_entity_id'] = $customerId;
						$finalData['city'] = $customerData->getData('city');
						$finalData['company'] = $customerData->getData('company');
						$finalData['country_id'] = $customerData->getData('country_id');
						$finalData['firstname'] = $customerData->getData('firstname');
						$finalData['lastname'] = $customerData->getData('lastname');
						$finalData['middlename'] = $customerData->getData('middlename');
						$finalData['postcode'] = $customerData->getData('postcode');
						$finalData['prefix'] = $customerData->getData('prefix');
						$finalData['region'] = $customerData->getData('region');
						$finalData['region_id'] = $customerData->getData('region_id');
						$finalData['street'] = $customerData->getData('street');
						$finalData['suffix'] = $customerData->getData('suffix');
						$finalData['telephone'] = $customerData->getData('telephone');
						$finalData['vat_id'] = $customerData->getData('vat_id');
						$finalData['vat_is_valid'] = $customerData->getData('vat_is_valid');
						$finalData['vat_request_date'] = $customerData->getData('vat_request_date');
						$finalData['vat_request_id'] = $customerData->getData('vat_request_id');
						$finalData['vat_request_date'] = $customerData->getData('vat_request_date');
						$finalData['vat_request_success'] = $customerData->getData('vat_request_success');
						if($billing==$shipping && $billing==$customerData->getData('entity_id')){
							$finalData['_address_default_billing_'] = 1;
							$finalData['_address_default_shipping_'] = 1;
						}elseif($billing!=$shipping && $billing==$customerData->getData('entity_id')){
							$finalData['_address_default_billing_'] = 1;
							$finalData['_address_default_shipping_'] = '';
						}elseif($billing!=$shipping && $shipping==$customerData->getData('entity_id')){
							$finalData['_address_default_billing_'] = '';
							$finalData['_address_default_shipping_'] = 1;
						}else{
							$finalData['_address_default_billing_'] = '';
							$finalData['_address_default_shipping_'] = '';
						}
						
						array_push($alldata, $finalData);
						
					}
				}
			}
						


		foreach ($alldata as $key => $value) {
			fputcsv($file,$value);
		}
		fclose($file);
    }
   
    
}


    $apple = new Customer();
    $apple->customerdata();