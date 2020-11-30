<?php
namespace Dckap\CustomFields\Model;

class AdditionalConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
	public function __construct(
		\Dckap\CustomFields\Helper\Data $helperData
	) {
		$this->helperData = $helperData;
	}
	public function getConfig()
 	{
		$output['dd_shipping_status'] = $this->helperData->getConfigValue(\Dckap\CustomFields\Helper\Data::XML_SHIPPING_STATUS);
 		$output['dd_shipping_text'] = $this->helperData->getConfigValue(\Dckap\CustomFields\Helper\Data::XML_SHIPPING_DISPLAY_TEXT);
		$output['dd_shipping_rates'] = $this->helperData->getConfigValue(\Dckap\CustomFields\Helper\Data::XML_SHIPPING_RATES);
		// $output['status'] = 'Test Config';
		return $output;
 	}
}