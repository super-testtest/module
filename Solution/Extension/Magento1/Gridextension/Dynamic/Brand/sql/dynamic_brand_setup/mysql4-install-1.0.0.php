<?php
/**
 * Dynamic Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0).
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to obtain it through the world-wide-web, please send
 * an email to info@dynamic.com so we can send you a copy immediately.
 *
 * @category   Dynamic
 * @package    Dynamic_Brand
 * @copyright  Copyright (c) 2010-2012 Dynamic Co. (http://dynamic.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Installation script
 *
 * @category   Dynamic
 * @package    Dynamic_Brand
 * @author     Tien Nguyen <tiennd@dynamicsoft.com>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getTable('dynamic_brand');

$installer->run("
	DROP TABLE IF EXISTS {$table};
    CREATE TABLE IF NOT EXISTS {$table} (
        brand_id int(11) unsigned not null auto_increment,
        brand_name TEXT not null,
        image TEXT not null,
        category TEXT not null,
		url varchar(255) DEFAULT NULL,
        sort_order varchar(10) DEFAULT NULL,
        PRIMARY KEY(brand_id)
    ) engine=InnoDB default charset=utf8;
");

$installer->endSetup();
