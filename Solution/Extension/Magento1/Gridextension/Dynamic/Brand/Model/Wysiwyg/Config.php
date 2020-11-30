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
 
class Dynamic_Brand_Model_Wysiwyg_Config extends Mage_Cms_Model_Wysiwyg_Config
{

    /**
     * Return Wysiwyg config as Varien_Object
     *
     * Config options description:
     *
     * enabled:                 Enabled Visual Editor or not
     * hidden:                  Show Visual Editor on page load or not
     * use_container:           Wrap Editor contents into div or not
     * no_display:              Hide Editor container or not (related to use_container)
     * translator:              Helper to translate phrases in lib
     * files_browser_*:         Files Browser (media, images) settings
     * encode_directives:       Encode template directives with JS or not
     *
     * @param $data Varien_Object constructor params to override default config values
     *
     * @return Varien_Object
     */
    public function getConfig($data = array())
    {
		$config = parent::getConfig($data);
		$config->setData('files_browser_window_url',Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index/'));
		$config->setData('directives_url',Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'));
		$config->setData('directives_url_quoted', preg_quote($config->getData('directives_url')));
		$config->setData('widget_window_url',Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'));
		return $config;
    }

}