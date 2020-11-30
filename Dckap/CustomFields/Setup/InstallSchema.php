<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Dckap\CustomFields\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
* Class InstallSchema
* @package Dckap\CustomFields\Setup
*/
class InstallSchema implements InstallSchemaInterface
{

   /**
    * {@inheritdoc}
    * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
    */
   public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
   {
       $installer = $setup;

       $installer->startSetup();

       /* While module install, creates columns in quote_address and sales_order_address table */

       $eavTable1 = $installer->getTable('quote');
       $eavTable2 = $installer->getTable('sales_order');

       $columns = [
/*           'input_custom_shipping_field' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Input option',
           ],*/
           'custom_shipping_method' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Custom Shipping Method',
           ],
           'custom_shipping_service' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Custom Shipping Service',
           ],
           'account_number' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Account Number',
           ],
           'account_name' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Account Name',
           ],
           'account_address' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Account Address',
           ],
           'custom_shipping_city' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Custom Shipping City',
           ],
           'custom_shipping_state' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Custom Shipping State',
           ],
           'custom_shipping_zipcode' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Custom Shipping Zipcode',
           ],
           'custom_shipping_country' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Custom Shipping Country',
           ],


          /* 'date_custom_shipping_field' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Date Ui component',
           ],

           'select_custom_shipping_field' => [
               'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
               'nullable' => true,
               'comment' => 'Select option',
           ],*/
       ];

       $connection = $installer->getConnection();
       foreach ($columns as $name => $definition) {
          $connection->addColumn($eavTable1, $name, $definition);
          $connection->addColumn($eavTable2, $name, $definition);
       }
       $installer->endSetup();
   }
}
