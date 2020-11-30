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
 * @package    Dynamic_Contactus
 * @author     DynamicSoft Team
 * @copyright  Copyright (c) 2010-2012 Dynamic Co. (http://mage-addons.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Dynamic_Contactus_Model_Contactus extends Mage_Core_Model_Abstract
{

    /**
     * Internal constructor not depended on params. Can be used for object initialization
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('dynamic_contactus/contactus');
    }

	public function validate()
	{
		$errors = array();
		$helper = Mage::helper('dynamic_contactus');
		$data = Mage::app()->getRequest()->getParam('contactus');
		if (!Zend_Validate::is($data['name'], 'NotEmpty')) {
			$errors[] = $helper->__('Name is a required field');
		}
		if (!Zend_Validate::is($data['email'], 'NotEmpty')) {
			$errors[] = $helper->__('Email a required field');
		}
		if (!Zend_Validate::is($data['content'], 'NotEmpty')) {
			$errors[] = $helper->__('Content is a required field');
		}
		if (empty($errors)) {
			return true;
		}
		return $errors;
	}
	
}