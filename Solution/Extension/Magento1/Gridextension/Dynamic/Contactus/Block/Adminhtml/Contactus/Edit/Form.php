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
 
class Dynamic_Contactus_Block_Adminhtml_Contactus_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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

        $fieldset = $form->addFieldset('contactus_form', array(
            'legend'	  => Mage::helper('dynamic_contactus')->__('Contactus'),
            'class'		=> 'fieldset-wide',
            )
        );

        $contactus_name = array();
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','contactus');
        $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
        $attributeOptions = $attribute->getSource()->getAllOptions();
        foreach($attributeOptions as $each){
        $contactus_name []= array(
                "label" =>$each["label"],
                "value" =>$each["label"]
            );
        }

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('dynamic_contactus')->__('Name'),
            'readonly' => true,
        ));
        $fieldset->addField('telephone', 'text', array(
            'name'      => 'telephone',
            'label'     => Mage::helper('dynamic_contactus')->__('Telephone'),
            'readonly' => true,
        ));
         $fieldset->addField('email', 'text', array(
            'name'      => 'email',
            'label'     => Mage::helper('dynamic_contactus')->__('Email'),
            'readonly' => true,
        ));
         $fieldset->addField('referralcode', 'text', array(
            'name'      => 'referralcode',
            'label'     => Mage::helper('dynamic_contactus')->__('Referral Code'),
            'readonly' => true,
        ));
          $fieldset->addField('know', 'text', array(
            'name'      => 'know',
            'label'     => Mage::helper('dynamic_contactus')->__('How did you hear about us?'),
            'readonly' => true,
        ));
        $fieldset->addField('comment', 'textarea', array(
            'name'      => 'comment',
            'label'     => Mage::helper('dynamic_contactus')->__('Inquiry'),
            'readonly' => true,
        ));
        $fieldset->addField('no', 'text', array(
            'name'      => 'no',
            'label'     => Mage::helper('dynamic_contactus')->__('Anti-Spam* What does 3 plus 5 equal?'),
            'readonly' => true,
        ));
		
        if (Mage::registry('dynamic_contactus')) {
            $form->setValues(Mage::registry('dynamic_contactus')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
