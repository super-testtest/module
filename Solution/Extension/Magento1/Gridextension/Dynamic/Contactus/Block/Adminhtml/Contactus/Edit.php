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
 
class Dynamic_Contactus_Block_Adminhtml_Contactus_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'dynamic_contactus';
        $this->_controller = 'adminhtml_contactus';
        $this->_removeButton('save');
        $this->_removeButton('delete');
        $this->_removeButton('reset');
        if( $this->getRequest()->getParam($this->_objectId) ) {
            $model = Mage::getModel('dynamic_contactus/contactus')->load($this->getRequest()->getParam($this->_objectId));
            Mage::register('dynamic_contactus', $model);
        }
    }

    /**
     * Get header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if( Mage::registry('dynamic_contactus') && Mage::registry('dynamic_contactus')->getId() ) {
            return Mage::helper('dynamic_contactus')->__('Edit Contactus');
        } else {
            return Mage::helper('dynamic_contactus')->__('Add Contactus');
        }
    }
}
