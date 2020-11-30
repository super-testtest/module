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
 
class Dynamic_Brand_Block_Adminhtml_Brand_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Preparing global layout
     *
     * You can redefine this method in child classes for changin layout
     *
     * @return Mage_Core_Block_Abstract
     */

     const REPEATER = '_';
    const PREFIX_END = '';
    protected $_options = array();


    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('brand_form', array(
            'legend'	  => Mage::helper('dynamic_brand')->__('Brand'),
            'class'		=> 'fieldset-wide',
            )
        );

        $brand_name = array();
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','brand');
        $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
        $attributeOptions = $attribute->getSource()->getAllOptions();
        foreach($attributeOptions as $each){
        $brand_name []= array(
                "label" =>$each["label"],
                "value" =>$each["label"]
            );
        }

         $fieldset->addField('brand_name', 'select', array(
            'name'  => 'brand_name',
            'label' => Mage::helper('dynamic_brand')->__('Brand Name'),
            'title'     => Mage::helper('dynamic_brand')->__('Brand Name'),
            'values'   => $brand_name,
            'required'  => true
        ));
        $fieldset->addField('image', 'image', array(
            'name'      => 'image',
            'label'     => Mage::helper('dynamic_brand')->__('Image'),
        ));
	
        $fieldset->addField('sort_order', 'text', array(
            'name'      => 'sort_order',
            'label'     => Mage::helper('dynamic_brand')->__('Sort Order'),
            'style'     => 'width:100px !important',
        ));
        $fieldset->addField('category', 'multiselect',
        array(
            'label' => Mage::helper('dynamic_brand')->__('Category'),
            'class' => 'required-entry',
            'required' => true,
            'values' => $this->getOptionArray(),
            'name' => 'category',

        ));
        $fieldset->addField('url', 'text', array(
            'name'      => 'url',
            'label'     => Mage::helper('dynamic_brand')->__('URL'),
            'required'  => true,
        ));
       
		/*$fieldset->addField('brand_status', 'select', array(
				'label' => Mage::helper('dynamic_brand')->__('Status'),
				'class' => 'required-entry',
				'name' => 'brand_status',			
				'values' => array(
					array(
						'value' => 1,
						'label' => Mage::helper('dynamic_brand')->__('Enabled'),
					),
					array(
						'value' => 2,
						'label' => Mage::helper('dynamic_brand')->__('Disabled'),
					),
				),
			));*/
		
        if (Mage::registry('dynamic_brand')) {
            $form->setValues(Mage::registry('dynamic_brand')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getOptionArray($parentId = 1, $recursionLevel = 3)
    {
        $recursionLevel = (int)$recursionLevel;
        $parentId       = (int)$parentId;
        $category = Mage::getModel('catalog/category');
        /* @var $category Mage_Catalog_Model_Category */
        $storeCategories = $category->getCategories($parentId, $recursionLevel, TRUE, FALSE, TRUE);
        foreach ($storeCategories as $node) {
            /* @var $node Varien_Data_Tree_Node */
            $this->_options[] = array(
                'label' => $node->getName(),
                'value' => $node->getName()
            );
            if ($node->hasChildren()) {
                $this->_getChildOptions($node->getChildren());
            }
        }
        return $this->_options;
    }
    /**
     * @param Varien_Data_Tree_Node_Collection $nodeCollection
     */
    protected function _getChildOptions(Varien_Data_Tree_Node_Collection $nodeCollection)
    {
        foreach ($nodeCollection as $node) {
            /* @var $node Varien_Data_Tree_Node */
            $prefix = str_repeat(self::REPEATER, $node->getLevel() * 1) . self::PREFIX_END;
            $this->_options[] = array(
                'label' => $prefix . $node->getName(),
                'value' => $node->getName()
            );
            if ($node->hasChildren()) {
                $this->_getChildOptions($node->getChildren());
            }
        }
    }

}
