<?php
// Include Mage.php
require_once('app/Mage.php');
umask(0);
Mage::app();
 
//Set memory limit
ini_set("memory_limit","1024M");
 
$_customersData[] = array(
    'Email',
    'Created At',
);
 
$page_num = 1;
$per_page_items = 1000;
 
// Get the customer data collection
$customers = Mage::getModel('customer/customer')->getCollection();
$customers->addAttributeToSelect('*');
$customers->setPage($page_num, $per_page_items);
$customers->setOrder('entity_id', 'ASC');
 
// Customer data loop
foreach ($customers as $key => $customer) {
    // Load customer all data from Id
    $customer = Mage::getModel("customer/customer")->load($customer->getId());
 
    // Get customer group name
   
    $_customersData[] = array(

        $customer->getData('email'),
        $customer->getData('created_at'),
    );
}
 
// Magento builtin class that will save the data as CSV
$csv = new Varien_File_Csv();
$path_to_save = Mage::getBaseDir('var'). DS . 'data.csv';
$csv->saveData($path_to_save, $_customersData);
