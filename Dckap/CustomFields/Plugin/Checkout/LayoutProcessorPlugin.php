<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Dckap\CustomFields\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

/**
* Class LayoutProcessorPlugin
* @package Dckap\CustomFields\Plugin\Checkout
*/
class LayoutProcessorPlugin
{
   /**
    * @param LayoutProcessor $subject
    * @param array $jsLayout
    * @return array
    */
   public function afterProcess(
       LayoutProcessor $subject,
       array $jsLayout
   ) {

       $validation['required-entry'] = true;

      /* $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['input_custom_shipping_field'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "input_custom_shipping_field"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[input_custom_shipping_field]',
           'label' => "Input option",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 2,
           'id' => 'custom_shipping_field[input_custom_shipping_field]'
       ];*/

       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

       $country = $objectManager->create('\Magento\Directory\Model\Config\Source\Country');
       $countrydata=$country->toOptionArray();

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['custom-shipping-method-fields']['children']['custom_shipping_method'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'customShippingMethodFields',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'custom_shipping_method',
            ],
            'dataScope' => 'customShippingMethodFields.custom_shipping_field[custom_shipping_method]',
            'label' => 'Shipping Method',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 1,
            'id' => 'custom_shipping_method',
            'options' => [
                [
                    'value' => 'FedEx Third Party',
                    'label' => 'FedEx Third Party',
                ],
                [
                    'value' => 'FedEx Collect',
                    'label' => 'FedEx Collect',
                ],
                [
                    'value' => 'UPS Third Party',
                    'label' => 'UPS Third Party',
                ],
                [
                    'value' => 'UPS Collect',
                    'label' => 'UPS Collect',
                ]
            ]
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['custom-shipping-method-fields']['children']['custom_shipping_service'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'customShippingMethodFields',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'custom_shipping_service',
            ],
            'dataScope' => 'customShippingMethodFields.custom_shipping_field[custom_shipping_service]',
            'label' => 'Shipping Service',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 2,
            'id' => 'custom_shipping_service',
            'options' => [
                [
                    'value' => 'Ground',
                    'label' => 'Ground',
                ],
                [
                    'value' => '2-Day',
                    'label' => '2-Day',
                ],
                [
                    'value' => 'Overnight',
                    'label' => 'Overnight',
                ],
            ]
        ];


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['account_number'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "account_number"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[account_number]',
           'label' => "Account Number",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 3,
           'id' => 'custom_shipping_field[account_number]'
       ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['account_name'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "account_name"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[account_name]',
           'label' => "Account Name",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 4,
           'id' => 'custom_shipping_field[account_name]'
       ];

       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['account_address'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "account_address"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[account_address]',
           'label' => "Account Address",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 5,
           'id' => 'custom_shipping_field[account_address]'
       ];



        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['custom_shipping_city'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "custom_shipping_city"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[custom_shipping_city]',
           'label' => "City",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 6,
           'id' => 'custom_shipping_field[custom_shipping_city]'
       ];

       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['custom_shipping_state'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "custom_shipping_state"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[custom_shipping_state]',
           'label' => "State/Province",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 7,
           'id' => 'custom_shipping_field[custom_shipping_state]'
       ];

       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['custom_shipping_zipcode'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "custom_shipping_zipcode"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[custom_shipping_zipcode]',
           'label' => "Zip Code/Postal Code",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 8,
           'id' => 'custom_shipping_field[custom_shipping_zipcode]'
       ];
       

       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['custom-shipping-method-fields']['children']['custom_shipping_country'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'customShippingMethodFields',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'custom_shipping_country',
            ],
            'dataScope' => 'customShippingMethodFields.custom_shipping_field[custom_shipping_country]',
            'label' => 'Country',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => $validation,
            'sortOrder' => 9,
            'id' => 'custom_shipping_country',
            'options' => $countrydata
        ];

       /* 
       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['date_custom_shipping_field'] = [
           'component' => "Magento_Ui/js/form/element/abstract",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/input",
               'id' => "date_custom_shipping_field"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[date_custom_shipping_field]',
           'label' => "UPS or FedEx Number",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 4,
           'id' => 'custom_shipping_field[date_custom_shipping_field]'
       ];
*/
	
       /*$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
       $baseUrl = $storeManager->getStore()->getBaseUrl().'freight-collect';
       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['input_custom_shipping_field'] = [
	    'component' => 'Magento_Ui/js/form/element/abstract',
		'config' => [
		    'customScope' => 'customShippingMethodFields',
		    'template' => 'ui/form/field',
		    'elementTmpl' => 'Dckap_CustomFields/form/element/checkboxcustom'
		],
		'provider' => 'checkoutProvider',
		'dataScope' => 'customShippingMethodFields.custom_shipping_field[input_custom_shipping_field]',
		'description' => __("I have read the <a href='$baseUrl'>terms of use</a> regarding<br>the use of the SHIP COLLECT Option and agree"),
		'sortOrder' => '10',
		'validation' => [
		    'required-entry' => true,
		]
*/
           /*'component' => "Magento\Ui\Component\Form\Element\Checkbox",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/single-checkbox",
               'id' => "input_custom_shipping_field"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[input_custom_shipping_field]',
           'label' => "Input option",
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 6,
           'id' => 'custom_shipping_field[input_custom_shipping_field]'
       ];    */
      /* $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['select_custom_shipping_field'] = [
           'component' => "Magento_Ui/js/form/element/select",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/select",
               'id' => "select_custom_shipping_field"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[select_custom_shipping_field]',
           'label' => "Select option",
           'options' => $this->getSelectOptions(),
           'caption' => 'Please select',
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 4,
           'id' => 'custom_shipping_field[select_custom_shipping_field]'
       ];*/

       return $jsLayout;
   }

   /**
    * @return array
    */
   protected function getOptions()
   {
	$disabledDays = array("4/30/2019");
       $options = [
           'dateFormat' => 'm/d/Y',
	   'minDate' => +1,
	   'showWeek' => false,
	   'custom' => 'somi'
       ];

       return $options;
   }
  
  

   protected function getSelectOptions()
   {
       $items[1]["value"] = "First Value";
       $items[1]["label"] = "First Label";
      
       $items[2]["value"] = "Second Value";
       $items[2]["label"] = "Second Label";

       return $items;
   }
}
