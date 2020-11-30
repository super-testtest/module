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
 
class Dynamic_Brand_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

   public function render(Varien_Object $row){
        $mediaurl=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $value = $row->getData($this->getColumn()->getIndex());
        return '<p style="text-align:center;padding-top:10px;"><img src="'.$mediaurl.DS.$value.'"  style="width:100px;height:200px;text-align:center;"/></p>';
    }
}
