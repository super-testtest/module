
<?php
require_once('app/Mage.php');
 Mage::app();

class Customer
{
    /**#@+
     * Exploded password hash keys
     */
    const PASSWORD_HASH = 0;
    const PASSWORD_SALT = 1;
    /**#@-*/

    /**
     * @var ResourceModel\Source
     */
    function customerdata(){

			$dataarr= array(132,133
			);
			$alldata = array();
			$file = fopen("finalcsv/"."customer".".csv","w");

			
			foreach ($dataarr as $key => $value) {
				# code...
			/*$order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($value);
			$orderItems = count($order->getAllItems())."<br>";*/
			$orderids = $value;
			$customerData = Mage::getModel('customer/customer')->load($value);
			$finalData = array();
				//$finalData[] =  array($key=>$key);
			$finalData['email'] = $customerData->getData('email');
			$website = Mage::getModel('core/website')->load($customerData->getData('website_id'));
			$finalData['_website'] = $website['code'];
			 $store = Mage::getModel('core/store')->load($customerData->getData('store_id'));
			$finalData['_store'] = $store['code'];
			$finalData['confirmation'] = $customerData->getData('confirmation');
			$finalData['created_at'] =  $newDate = date("Y-m-d H:i:s", strtotime($customerData->getData('created_at')));
			$finalData['created_in'] = $customerData->getData('created_in');
			$finalData['disable_auto_group_change'] = $customerData->getData('disable_auto_group_change');
			$finalData['dob'] = $customerData->getData('dob');
			$finalData['failures_num'] = '';
			$finalData['firstname'] = $customerData->getData('firstname');
			$finalData['first_failure'] = '';
			$finalData['gender'] = $customerData->getData('gender');
			$finalData['group_id'] = $customerData->getData('group_id');
			$finalData['lastname'] = $customerData->getData('lastname');
			$finalData['lock_expires'] = '';
			$finalData['middlename'] = ($customerData->getData('middlename'))? $customerData->getData('middlename') : '';
			$finalData['password_hash'] = $this->upgradeCustomerHash($customerData->getData('password_hash'));
			$finalData['prefix'] = $customerData->getData('prefix');
			$finalData['rp_token'] = $customerData->getData('rp_token');
			$finalData['rp_token_created_at'] = $customerData->getData('rp_token_created_at');
			$finalData['store_id'] = $customerData->getData('store_id');
			$finalData['suffix'] = $customerData->getData('suffix');
			$finalData['taxvat'] = $customerData->getData('taxvat');
			$finalData['updated_at'] = $customerData->getData('updated_at');
			$finalData['website_id'] = $customerData->getData('website_id');
			$finalData['password'] = '';

			array_push($alldata, $finalData);


		}

		foreach ($alldata as $key => $value) {
			fputcsv($file,$value);
		}
		fclose($file);
    }
   
    function upgradeCustomerHash($hash)
    {
        if (isset($hash)) {
            $hashExploded = $this->explodePasswordHash($hash);
            if (strlen($hashExploded[self::PASSWORD_HASH]) == 32) {
                $hash = implode(':', [$hashExploded[self::PASSWORD_HASH], $hashExploded[self::PASSWORD_SALT], '0']);
            } elseif (strlen($hashExploded[self::PASSWORD_HASH]) == 64) {
                $hash = implode(':', [$hashExploded[self::PASSWORD_HASH], $hashExploded[self::PASSWORD_SALT], '1']);
            }
        }
        return $hash;
    }

    /**
     * Split password hash to hash part and salt part
     *
     * @param string $passwordHash
     * @return array
     */
    function explodePasswordHash($passwordHash)
    {
        $explodedPassword = explode(':', $passwordHash, 2);
        $explodedPassword[self::PASSWORD_SALT] = isset($explodedPassword[self::PASSWORD_SALT])
            ? $explodedPassword[self::PASSWORD_SALT]
            : ''
        ;
        return $explodedPassword;
    }
}


    $apple = new Customer();
    $apple->customerdata();