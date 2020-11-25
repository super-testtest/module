<?php


namespace Dynamic\Banners\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
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

		/**
		 * Creating table Dynamic_banners
		 */
		$table = $installer->getConnection()->newTable(
			$installer->getTable('banners')
		)->addColumn(
			'banners_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
			'Entity Id'
		)->addColumn(
			'name',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => true],
			'Name'
		)->addColumn(
			'email',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => true],
			'Email'
		)->addColumn(
			'image',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			null,
			['nullable' => true,'default' => null],
			'Image'
		
		)->addColumn(
			'doc',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			null,
			['nullable' => true],
			'Doc'
		
		)->addColumn(
			'status',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false , 'default' => '0'],
			'Status'
		)->addColumn(
			'sort_order',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => true,'default' => null],
			'Sort Order'	
		)->addColumn(
			'created_at',
			\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
			null,
			['nullable' => false],
			'Created At'
		)->addColumn(
			'updated_at',
			\Magento\Framework\DB\Ddl\Table::TYPE_DATE,
			null,
			['nullable' => true,'default' => null],
			'Updated At'
		)->addIndex(
			$installer->getIdxName(
				'Dynamic_banners',
				['updated_at'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
			),
			['updated_at'],
			['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
		)->setComment(
			'Banners'
		);
		$installer->getConnection()->createTable($table);
		$installer->endSetup();
	}
}