<?php
/**
 * Dynamicsoft Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0).
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to obtain it through the world-wide-web, please send
 * an email to support@mage-addons.com so we can send you a copy immediately.
 *
 * @category   Dynamic
 * @package    Dynamic_Brand
 * @author     DynamicSoft Team
 * @copyright  Copyright (c) 2010-2012 Dynamic Co. (http://mage-addons.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Dynamic_Brand_Model_Status extends Varien_Object {
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	static public function getOptionArray() {
		return array(
			self::STATUS_ENABLED => Mage::helper('dynamic_brand')->__('Enabled'),
			self::STATUS_DISABLED => Mage::helper('dynamic_brand')->__('Disabled')
		);
	}
}