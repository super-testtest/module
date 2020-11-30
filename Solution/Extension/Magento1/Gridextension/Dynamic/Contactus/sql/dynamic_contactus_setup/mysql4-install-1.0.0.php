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
 * @package    Dynamic_Contactus
 * @copyright  Copyright (c) 2010-2012 Dynamic Co. (http://dynamic.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Installation script
 *
 * @category   Dynamic
 * @package    Dynamic_Contactus
 * @author     Tien Nguyen <tiennd@dynamicsoft.com>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getTable('dynamic_contactus');
$installer->run("
	DROP TABLE IF EXISTS {$table};
    CREATE TABLE IF NOT EXISTS {$table} (
        contactus_id int(11) unsigned not null auto_increment,
        name TEXT not null,
        telephone TEXT not null,
        email TEXT not null,
		referralcode varchar(255) NOT NULL default '',
        know TEXT not null,
        comment TEXT not null,
        realno varchar(100) NOT NULL default '',
        no TEXT not null,
        created_time timestamp default current_timestamp,
        PRIMARY KEY(contactus_id)
    ) engine=InnoDB default charset=utf8;
");

$installer->endSetup();
