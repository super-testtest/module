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
 
class Dynamic_Brand_Block_Adminhtml_Brand_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'dynamic_brand';
        $this->_controller = 'adminhtml_brand';

        $this->_updateButton('save', 'label', Mage::helper('dynamic_brand')->__('Save Brand'));
        $this->_updateButton('delete', 'label', Mage::helper('dynamic_brand')->__('Delete Brand'));

        if( $this->getRequest()->getParam($this->_objectId) ) {
            $model = Mage::getModel('dynamic_brand/brand')->load($this->getRequest()->getParam($this->_objectId));
            Mage::register('dynamic_brand', $model);
        }
    }

    /**
     * Get header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if( Mage::registry('dynamic_brand') && Mage::registry('dynamic_brand')->getId() ) {
            return Mage::helper('dynamic_brand')->__('Edit Brand');
        } else {
            return Mage::helper('dynamic_brand')->__('Add Brand');
        }
    }
}
