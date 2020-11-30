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
  
class Dynamic_Contactus_Block_Adminhtml_Contactus extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_contactus';
        $this->_blockGroup = 'dynamic_contactus';
        $this->_headerText = Mage::helper('dynamic_contactus')->__('Manage Contactuss');

        parent::__construct();
        $this->_removeButton('add');
    }

}
