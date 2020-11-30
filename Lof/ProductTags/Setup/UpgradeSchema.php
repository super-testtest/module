<?php
/**
 * Copyright (c) 2019  Landofcoder
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Lof\ProductTags\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
		if (version_compare($context->getVersion(), "1.0.3", "<")) {
            

            $table = $installer->getConnection()
                ->newTable($installer->getTable('customer_related_tags'))
                ->addColumn(
                    'id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Entity Id'
                )->addColumn(
                    'customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Customer Id'
                )->addColumn(
                    'first_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true,'default' => null],
                    'First Name'
                )->addColumn(
                    'middle_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true,'default' => null],
                    'Middle Name'
                )->addColumn(
                    'last_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true,'default' => null],
                    'Last Name'
                )->addColumn(
                    'product_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true,'default' => null],
                    'Product Name'
                )->addColumn(
                    'product_sku',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true,'default' => null],
                    'Product Sku'
                )->addColumn(
                    'product_id_related',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true,'default' => null],
                    'Product Id'
                )->addColumn(
                    'is_approve',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true,'default' => null],
                    'Status'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false],
                    'Created At'
                )->setComment(
                    'Customer Related Tags'
                );

            $installer->getConnection()->createTable($table);
        }
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
           
            $tableName = $installer->getTable('customer_related_tags');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                   
                     'relatedtag' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => false,
                        'comment' => 'Related Tag',
                    ]];

                    $columns = [
                   
                     'tag_id' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'nullable' => true,
                        'unsigned' => true,
                        'comment' => 'foreign key All Tag ID',
                    ]];
                $connection = $installer->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }
        $installer->endSetup();
    }
}
